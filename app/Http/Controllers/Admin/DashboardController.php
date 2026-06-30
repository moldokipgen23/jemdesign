<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ─── Core Stats ───────────────────────────────────────
        $totalProducts    = Product::count();
        $newInquiries     = Inquiry::where('status', 'new')->count();
        $totalCollections = Collection::where('is_active', true)->count();
        $topSellers       = Product::where('is_top_seller', true)->count();

        // ─── Order Stats ──────────────────────────────────────
        $totalOrders  = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $avgOrder     = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // This week
        $weekStart     = now()->startOfWeek();
        $weekOrders    = Order::where('created_at', '>=', $weekStart)->where('payment_status', 'paid')->count();
        $weekRevenue   = Order::where('created_at', '>=', $weekStart)->where('payment_status', 'paid')->sum('total');

        // Last 30 days revenue chart
        $revenueChart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $revenueValues = [];
        $orderValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M j');
            $revenueValues[] = (float) ($revenueChart->get($date)?->revenue ?? 0);
            $orderValues[] = (int) ($revenueChart->get($date)?->orders ?? 0);
        }

        // Monthly revenue (last 12 months)
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyLabels = [];
        $monthlyValues = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $key = $m->format('Y-m');
            $monthlyLabels[] = $m->format('M Y');
            $monthlyValues[] = (float) ($monthlyRevenue->firstWhere('month', $key)?->revenue ?? 0);
        }

        // ─── Top Products by Revenue ──────────────────────────
        $topByRevenue = OrderItem::query()
            ->select('product_name', DB::raw('SUM(total_price) as revenue'), DB::raw('SUM(quantity) as qty'))
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
            ->groupBy('product_name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        // ─── Recent Inquiries ─────────────────────────────────
        $recentInquiries = Inquiry::latest()->take(5)->get();

        // ─── Inquiries Chart (last 30 days) ───────────────────
        $inquiryChart = Inquiry::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $inquiryLabels = [];
        $inquiryValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $inquiryLabels[] = now()->subDays($i)->format('M j');
            $inquiryValues[] = (int) ($inquiryChart->get($date)?->total ?? 0);
        }

        return view('admin.dashboard', compact(
            'totalProducts', 'newInquiries', 'totalCollections', 'topSellers',
            'totalOrders', 'totalRevenue', 'avgOrder',
            'weekOrders', 'weekRevenue',
            'chartLabels', 'revenueValues', 'orderValues',
            'monthlyLabels', 'monthlyValues',
            'topByRevenue', 'recentInquiries',
            'inquiryLabels', 'inquiryValues'
        ));
    }
}
