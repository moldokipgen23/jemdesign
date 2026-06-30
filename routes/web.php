<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// ─── Public Storefront ────────────────────────────────────────────────────────
Route::get('/',           [StorefrontController::class, 'home'])->name('storefront.home');
Route::get('/shop',       [StorefrontController::class, 'shop'])->name('storefront.shop');
Route::get('/products/{slug}', [StorefrontController::class, 'product'])->name('storefront.product');
Route::get('/story',      [StorefrontController::class, 'story'])->name('storefront.story');
Route::get('/founder',    [StorefrontController::class, 'founder'])->name('storefront.founder');

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Catalog
    Route::resource('products',    Admin\ProductController::class);
    Route::resource('categories',  Admin\CategoryController::class);
    Route::resource('collections', Admin\CollectionController::class);

    // Attributes
    Route::get('attributes', [Admin\AttributeController::class, 'index'])->name('attributes.index');
    Route::post('attributes', [Admin\AttributeController::class, 'store'])->name('attributes.store');
    Route::delete('attributes/{attribute}', [Admin\AttributeController::class, 'destroy'])->name('attributes.destroy');
    Route::post('attributes/{attribute}/values', [Admin\AttributeController::class, 'storeValue'])->name('attributes.values.store');
    Route::delete('attributes/{attribute}/values/{value}', [Admin\AttributeController::class, 'destroyValue'])->name('attributes.values.destroy');

    // Product Variations (new attribute-based system)
    Route::post('products/{product}/variations', [Admin\ProductController::class, 'storeVariations'])->name('products.variations.store');
    Route::get('products/{product}/variations', [Admin\ProductController::class, 'getVariations'])->name('products.variations.index');

    // Colors (nested under product)
    Route::prefix('products/{product}/colors')->name('products.colors.')->group(function () {
        Route::post('/',         [Admin\ProductColorController::class, 'store'])->name('store');
        Route::patch('{color}',  [Admin\ProductColorController::class, 'update'])->name('update');
        Route::delete('{color}', [Admin\ProductColorController::class, 'destroy'])->name('destroy');
    });

    // Images (nested under color)
    Route::prefix('colors/{color}/images')->name('colors.images.')->group(function () {
        Route::get('/',         [Admin\ProductImageController::class, 'index'])->name('index');
        Route::post('/',         [Admin\ProductImageController::class, 'store'])->name('store');
        Route::delete('{image}', [Admin\ProductImageController::class, 'destroy'])->name('destroy');
        Route::post('reorder',   [Admin\ProductImageController::class, 'updateOrder'])->name('reorder');
    });

    // Videos (nested under product)
    Route::prefix('products/{product}/videos')->name('products.videos.')->group(function () {
        Route::post('/',         [Admin\ProductVideoController::class, 'store'])->name('store');
        Route::delete('{video}', [Admin\ProductVideoController::class, 'destroy'])->name('destroy');
    });

    // Sizes (nested under product)
    Route::prefix('products/{product}/sizes')->name('products.sizes.')->group(function () {
        Route::post('/',        [Admin\ProductSizeController::class, 'store'])->name('store');
        Route::patch('{size}',  [Admin\ProductSizeController::class, 'update'])->name('update');
        Route::delete('{size}', [Admin\ProductSizeController::class, 'destroy'])->name('destroy');
    });

    // Variants (nested under product)
    Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
        Route::get('/',         [Admin\ProductVariantController::class, 'index'])->name('index');
        Route::post('/',         [Admin\ProductVariantController::class, 'store'])->name('store');
        Route::patch('{variant}', [Admin\ProductVariantController::class, 'update'])->name('update');
        Route::delete('{variant}', [Admin\ProductVariantController::class, 'destroy'])->name('destroy');
        Route::post('bulk',      [Admin\ProductVariantController::class, 'bulkUpdate'])->name('bulk');
    });

    // Content
    Route::get('instagram',                            [Admin\InstagramPostController::class, 'index'])->name('instagram.index');
    Route::resource('instagram', Admin\InstagramPostController::class)->except(['index'])->names([
        'create'  => 'instagram.create',
        'store'   => 'instagram.store',
        'show'    => 'instagram.show',
        'edit'    => 'instagram.edit',
        'update'  => 'instagram.update',
        'destroy' => 'instagram.destroy',
    ]);

    Route::get('homepage-sections',  [Admin\HomepageSectionController::class, 'index'])->name('homepage.index');
    Route::post('homepage-sections', [Admin\HomepageSectionController::class, 'update'])->name('homepage.update');
    Route::post('homepage-sections/{section}/image', [Admin\HomepageSectionController::class, 'updateImage'])->name('homepage.updateImage');

    // Marketing
    Route::get('marketing',                     [Admin\MarketingSectionController::class, 'index'])->name('marketing.index');
    Route::get('marketing/create',              [Admin\MarketingSectionController::class, 'create'])->name('marketing.create');
    Route::post('marketing',                    [Admin\MarketingSectionController::class, 'store'])->name('marketing.store');
    Route::get('marketing/{marketingSection}/edit', [Admin\MarketingSectionController::class, 'edit'])->name('marketing.edit');
    Route::patch('marketing/{marketingSection}', [Admin\MarketingSectionController::class, 'update'])->name('marketing.update');
    Route::delete('marketing/{marketingSection}', [Admin\MarketingSectionController::class, 'destroy'])->name('marketing.destroy');
    Route::post('marketing/reorder',            [Admin\MarketingSectionController::class, 'reorder'])->name('marketing.reorder');
    Route::post('marketing/{marketingSection}/toggle', [Admin\MarketingSectionController::class, 'toggle'])->name('marketing.toggle');

    // Testimonials
    Route::get('testimonials',                    [Admin\TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('testimonials/create',             [Admin\TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('testimonials',                   [Admin\TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('testimonials/{testimonial}/edit', [Admin\TestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::patch('testimonials/{testimonial}',    [Admin\TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('testimonials/{testimonial}',   [Admin\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    Route::post('testimonials/{testimonial}/toggle', [Admin\TestimonialController::class, 'toggle'])->name('testimonials.toggle');

    // Orders
    Route::get('inquiries',                    [Admin\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/{inquiry}',          [Admin\InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('inquiries/{inquiry}/status', [Admin\InquiryController::class, 'markStatus'])->name('inquiries.markStatus');

    // Paid Orders
    Route::get('orders',                       [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}',               [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status',      [Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Settings
    Route::get('settings',  [Admin\SiteSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [Admin\SiteSettingController::class, 'update'])->name('settings.update');
});

// Redirect /dashboard → admin
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Public API ───────────────────────────────────────────────────────────────
Route::post('/api/inquiry', [InquiryController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('inquiry.store');

// ─── Checkout ────────────────────────────────────────────────────────────────
Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('storefront.checkout');
Route::post('/checkout', [StorefrontController::class, 'processCheckout'])->name('storefront.checkout.process');
Route::get('/checkout/success/{order}', [StorefrontController::class, 'checkoutSuccess'])->name('storefront.checkout.success');
Route::post('/api/payment/verify', [StorefrontController::class, 'verifyPayment'])->name('api.payment.verify');
Route::post('/api/razorpay/create-order', [RazorpayController::class, 'createOrder'])->name('api.razorpay.create-order')->middleware('throttle:10,1');

// ─── Profile ──────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
