<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\HomepageSection;
use App\Models\InstagramPost;
use App\Models\MarketingSection;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api as RazorpayApi;
use Razorpay\Api\Errors\SignatureVerificationError;

class StorefrontController extends Controller
{
    public function home()
    {
        $sections = HomepageSection::enabled()->get();
        $marketingSections = MarketingSection::enabled()->with('items')->get();

        $topSellers = Product::active()->topSellers()
            ->with(['colors.images', 'category'])
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $collections = Collection::active()->take(4)->get();

        $instagramPosts = InstagramPost::active()->take(9)->get();

        return view('storefront.home', compact(
            'sections', 'marketingSections', 'topSellers', 'collections', 'instagramPosts'
        ));
    }

    public function shop()
    {
        $filter     = request('filter');
        $collection = request('collection');

        $query = Product::active()
            ->with(['colors.images', 'category', 'collections'])
            ->orderBy('sort_order');

        if ($filter === 'women') {
            $query->whereHas('category', fn ($q) => $q->where('slug', 'womens'));
        } elseif ($filter === 'men') {
            $query->whereHas('category', fn ($q) => $q->where('slug', 'mens'));
        }

        if ($collection) {
            $query->whereHas('collections', fn ($q) => $q->where('slug', $collection));
        }

        $products = $query->paginate(12)->withQueryString();

        $activeCollection = $collection
            ? \App\Models\Collection::where('slug', $collection)->first()
            : null;

        return view('storefront.shop', compact('products', 'filter', 'activeCollection'));
    }

    public function product(string $slug)
    {
        $product = Product::active()
            ->with(['colors.images', 'sizes', 'videos', 'category', 'collections', 'variants'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('storefront.product', compact('product'));
    }

    public function story()
    {
        $brandStory = SiteSetting::get('brand_story', '');
        return view('storefront.story', compact('brandStory'));
    }

    public function founder()
    {
        return view('storefront.founder');
    }

    // ─── Checkout ────────────────────────────────────────────────────────────

    public function checkout()
    {
        $razorpayKey = config('razorpay.key_id');
        return view('storefront.checkout', compact('razorpayKey'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
            'cart'                => 'required|array|min:1',
            'customer_name'       => 'required|string|max:200',
            'customer_email'      => 'required|email|max:200',
            'customer_phone'      => 'nullable|string|max:30',
            'shipping_address'    => 'required|string|max:1000',
            'notes'               => 'nullable|string|max:500',
        ]);

        // Verify Razorpay signature
        $razorpay = new RazorpayApi;
        $razorpay->setApiKey(config('razorpay.key_secret'));

        try {
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];
            $razorpay->utility->verifyPaymentLink($attributes);
        } catch (SignatureVerificationError $e) {
            return response()->json(['error' => 'Payment verification failed.'], 422);
        }

        // Process order in a transaction
        $order = DB::transaction(function () use ($request) {
            $cart = $request->cart;
            $subtotal = 0;
            $items = [];

            foreach ($cart as $item) {
                $product = Product::active()->find($item['id'] ?? null);
                if (!$product) abort(422, 'Product not found: ' . ($item['name'] ?? 'unknown'));

                $variant = null;
                if (!empty($item['variant_id'])) {
                    $variant = ProductVariant::where('id', $item['variant_id'])
                        ->where('product_id', $product->id)
                        ->first();
                }

                $qty = max(1, (int) ($item['qty'] ?? 1));
                $unitPrice = $variant ? $variant->effective_price : $product->price;
                $lineTotal = $unitPrice * $qty;

                // Check stock
                if ($variant) {
                    if ($variant->stock < $qty) {
                        abort(422, 'Insufficient stock for ' . $product->name . ' (' . ($item['color'] ?? '') . ' ' . ($item['size'] ?? '') . ')');
                    }
                    $variant->decrement('stock', $qty);
                }

                $subtotal += $lineTotal;
                $items[] = [
                    'product_id'         => $product->id,
                    'product_variant_id' => $variant?->id,
                    'product_name'       => $product->name,
                    'color_name'         => $item['color'] ?? null,
                    'size_label'         => $item['size'] ?? null,
                    'quantity'           => $qty,
                    'unit_price'         => $unitPrice,
                    'total_price'        => $lineTotal,
                ];
            }

            $order = Order::create([
                'order_number'      => Order::generateOrderNumber(),
                'customer_name'     => $request->customer_name,
                'customer_email'    => $request->customer_email,
                'customer_phone'    => $request->customer_phone,
                'shipping_address'  => $request->shipping_address,
                'notes'             => $request->notes,
                'subtotal'          => $subtotal,
                'total'             => $subtotal,
                'payment_method'    => 'razorpay',
                'payment_status'    => 'paid',
                'payment_id'        => $request->razorpay_payment_id,
                'payment_order_id'  => $request->razorpay_order_id,
                'status'            => 'pending',
            ]);

            foreach ($items as $itemData) {
                $order->items()->create($itemData);
            }

            return $order;
        });

        return response()->json([
            'success' => true,
            'redirect' => route('storefront.checkout.success', $order->order_number),
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id'   => 'required|string',
            'razorpay_order_id'     => 'required|string',
            'razorpay_signature'    => 'required|string',
        ]);

        $razorpay = new RazorpayApi;
        $razorpay->setApiKey(config('razorpay.key_secret'));

        try {
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];
            $razorpay->utility->verifyPaymentLink($attributes);
            return response()->json(['valid' => true]);
        } catch (SignatureVerificationError $e) {
            return response()->json(['valid' => false, 'error' => 'Invalid signature'], 422);
        }
    }

    public function checkoutSuccess(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('storefront.checkout-success', compact('order'));
    }
}
