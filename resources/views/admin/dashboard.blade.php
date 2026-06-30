@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title" style="background:linear-gradient(135deg, var(--gold) 0%, #D97706 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Welcome back</h1>
        <p class="admin-page-header__sub" style="font-size:13px;color:var(--text-dim)">Jem Designs & Co. — {{ now()->format('l, j F Y') }}</p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('admin.products.create') }}" class="btn-admin btn-admin--gold">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Product
        </a>
        <a href="{{ route('admin.inquiries.index') }}" class="btn-admin btn-admin--outline">
            @if($newInquiries > 0)
                <span style="display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;background:var(--gold);color:#fff;border-radius:50%;font-size:10px;font-weight:700">{{ $newInquiries }}</span>
            @endif
            Inquiries
        </a>
    </div>
</div>

{{-- ═══ STAT CARDS ═══ --}}
<div class="admin-stats">
    <div class="admin-stat-card admin-stat-card--gold">
        <div class="admin-stat-card__icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div class="admin-stat-card__label">Total Revenue</div>
        <div class="admin-stat-card__value">₹{{ number_format($totalRevenue) }}</div>
        <div class="admin-stat-card__meta">{{ $totalOrders }} order(s) total</div>
    </div>
    <div class="admin-stat-card admin-stat-card--blue">
        <div class="admin-stat-card__icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div class="admin-stat-card__label">This Week</div>
        <div class="admin-stat-card__value">₹{{ number_format($weekRevenue) }}</div>
        <div class="admin-stat-card__meta">{{ $weekOrders }} order(s) this week</div>
    </div>
    <div class="admin-stat-card admin-stat-card--green">
        <div class="admin-stat-card__icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <div class="admin-stat-card__label">New Inquiries</div>
        <div class="admin-stat-card__value">{{ $newInquiries }}</div>
        <div class="admin-stat-card__meta">Awaiting response</div>
    </div>
    <div class="admin-stat-card admin-stat-card--purple">
        <div class="admin-stat-card__icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        </div>
        <div class="admin-stat-card__label">Products</div>
        <div class="admin-stat-card__value">{{ $totalProducts }}</div>
        <div class="admin-stat-card__meta">{{ $topSellers }} top sellers</div>
    </div>
</div>

{{-- ═══ REVENUE CHART ═══ --}}
<div class="admin-card admin-card--blue" style="margin-bottom:20px">
    <div class="admin-card__header">
        <span class="admin-card__title">Revenue — Last 30 Days</span>
        <span style="font-size:12px;font-weight:600;color:#2563EB">₹{{ number_format(array_sum($revenueValues)) }} total</span>
    </div>
    <div class="admin-card__body">
        <canvas id="revenueChart" height="160"></canvas>
    </div>
</div>

{{-- ═══ MONTHLY REVENUE ═══ --}}
<div class="admin-card admin-card--purple" style="margin-bottom:20px">
    <div class="admin-card__header">
        <span class="admin-card__title">Monthly Revenue — Last 12 Months</span>
    </div>
    <div class="admin-card__body">
        <canvas id="monthlyChart" height="160"></canvas>
    </div>
</div>

{{-- ═══ TOP PRODUCTS + INQUIRIES ═══ --}}
<div class="admin-grid-2" style="margin-bottom:20px">

    {{-- Top Products by Revenue --}}
    <div class="admin-card admin-card--gold">
        <div class="admin-card__header">
            <span class="admin-card__title">Top Products by Revenue</span>
        </div>
        <div class="admin-card__body">
            @forelse($topByRevenue as $item)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border-light)">
                <div>
                    <span style="font-size:13px;color:var(--text);font-weight:500">{{ $item->product_name }}</span>
                    <span style="font-size:11px;color:var(--text-muted);margin-left:6px">{{ $item->qty }} sold</span>
                </div>
                <span style="font-size:13px;font-weight:600;color:var(--gold)">₹{{ number_format($item->revenue) }}</span>
            </div>
            @empty
            <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:40px 0">
                No paid orders yet — revenue data will appear here.
            </p>
            @endforelse
        </div>
    </div>

    {{-- Inquiries Chart --}}
    <div class="admin-card admin-card--green">
        <div class="admin-card__header">
            <span class="admin-card__title">Inquiries — Last 30 Days</span>
        </div>
        <div class="admin-card__body">
            <canvas id="inquiryChart" height="180"></canvas>
        </div>
    </div>
</div>

{{-- ═══ RECENT INQUIRIES ═══ --}}
<div class="admin-card">
    <div class="admin-card__header">
        <span class="admin-card__title">Recent Inquiries</span>
        <a href="{{ route('admin.inquiries.index') }}" class="btn-admin btn-admin--outline btn-admin--sm">View All</a>
    </div>
    @if($recentInquiries->count())
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentInquiries as $inq)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px">{{ $inq->created_at->format('d M, H:i') }}</td>
                    <td style="font-weight:500;color:var(--text)">{{ $inq->customer_name ?: '—' }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $inq->item_count }} item(s)</td>
                    <td style="font-weight:600;color:var(--text)">{{ $inq->total_estimate ? '₹'.number_format($inq->total_estimate) : '—' }}</td>
                    <td>
                        @if($inq->status === 'new')     <span class="badge badge--gold">New</span>
                        @elseif($inq->status === 'contacted') <span class="badge badge--green">Contacted</span>
                        @else <span class="badge badge--gray">Completed</span>
                        @endif
                    </td>
                    <td><a href="{{ route('admin.inquiries.show', $inq) }}" class="btn-admin btn-admin--outline btn-admin--sm">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="admin-card__body">
        <p style="color:var(--text-muted);font-size:13px;text-align:center;padding:40px 0">No inquiries yet.</p>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
const isDark = body.classList.contains('admin-dark');
const chartFont = { family: "'Inter', sans-serif", size: 11 };
const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
const tickColor = isDark ? '#6B6A65' : '#8B8F98';

// Revenue Chart (30 days)
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Revenue (₹)',
            data: @json($revenueValues),
            backgroundColor: isDark ? 'rgba(184,134,11,0.3)' : 'rgba(184,134,11,0.15)',
            borderColor: '#B8860B',
            borderWidth: 1.5,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: tickColor, font: chartFont, maxRotation: 45 }, grid: { display: false } },
            y: { beginAtZero: true, ticks: { color: tickColor, font: chartFont, callback: v => '₹' + v.toLocaleString() }, grid: { color: gridColor } }
        }
    }
});

// Monthly Revenue Chart (12 months)
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: @json($monthlyLabels),
        datasets: [{
            label: 'Monthly Revenue (₹)',
            data: @json($monthlyValues),
            borderColor: '#B8860B',
            backgroundColor: isDark ? 'rgba(184,134,11,0.08)' : 'rgba(184,134,11,0.06)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#B8860B',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            borderWidth: 2.5,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: tickColor, font: chartFont, maxRotation: 45 }, grid: { display: false } },
            y: { beginAtZero: true, ticks: { color: tickColor, font: chartFont, callback: v => '₹' + v.toLocaleString() }, grid: { color: gridColor } }
        }
    }
});

// Inquiries Chart (30 days)
new Chart(document.getElementById('inquiryChart'), {
    type: 'bar',
    data: {
        labels: @json($inquiryLabels),
        datasets: [{
            label: 'Inquiries',
            data: @json($inquiryValues),
            backgroundColor: isDark ? 'rgba(16,185,129,0.3)' : 'rgba(16,185,129,0.15)',
            borderColor: '#10B981',
            borderWidth: 1.5,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: tickColor, font: chartFont, maxRotation: 45 }, grid: { display: false } },
            y: { beginAtZero: true, ticks: { color: tickColor, font: chartFont, stepSize: 1 }, grid: { color: gridColor } }
        }
    }
});
</script>
@endsection
