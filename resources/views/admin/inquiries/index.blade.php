@extends('layouts.admin')
@section('title', 'Inquiries')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Inquiries</h1>
        <p class="admin-page-header__sub">WhatsApp checkout requests</p>
    </div>
    @if($newCount > 0)
    <span class="badge badge--gold" style="font-size:13px;padding:8px 16px">{{ $newCount }} new</span>
    @endif
</div>

{{-- Status Tabs --}}
<div style="display:flex;gap:4px;margin-bottom:20px">
    @foreach(['all' => 'All', 'new' => 'New', 'contacted' => 'Contacted', 'completed' => 'Completed'] as $val => $label)
    <a href="{{ route('admin.inquiries.index', $val !== 'all' ? ['status' => $val] : []) }}"
       class="btn-admin {{ $activeStatus === $val ? 'btn-admin--gold' : 'btn-admin--outline' }} btn-admin--sm">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Items</th>
                    <th>Total Est.</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $inq)
                <tr>
                    <td style="font-size:12px;color:var(--text-muted);white-space:nowrap">{{ $inq->created_at->format('d M Y, H:i') }}</td>
                    <td style="color:var(--text)">{{ $inq->customer_name ?: '—' }}</td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $inq->customer_phone ?: '—' }}</td>
                    <td style="font-size:12px">{{ $inq->item_count }} item(s)</td>
                    <td style="font-weight:500;color:var(--text)">{{ $inq->total_estimate ? '₹'.number_format($inq->total_estimate) : '—' }}</td>
                    <td>
                        @if($inq->status === 'new') <span class="badge badge--gold">New</span>
                        @elseif($inq->status === 'contacted') <span class="badge badge--green">Contacted</span>
                        @else <span class="badge badge--gray">Completed</span>
                        @endif
                    </td>
                    <td><a href="{{ route('admin.inquiries.show', $inq) }}" class="btn-admin btn-admin--outline btn-admin--sm">View</a></td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:60px">No inquiries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inquiries->hasPages())
    <div style="padding:16px 24px;border-top:1px solid var(--border)">{{ $inquiries->links() }}</div>
    @endif
</div>
@endsection
