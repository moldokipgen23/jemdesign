@extends('layouts.admin')
@section('title', 'Inquiry #' . $inquiry->id)

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Inquiry #{{ $inquiry->id }}</h1>
        <p class="admin-page-header__sub">{{ $inquiry->created_at->format('d M Y, H:i') }}</p>
    </div>
    <a href="{{ route('admin.inquiries.index') }}" class="btn-admin btn-admin--outline">← Back</a>
</div>

<div class="admin-grid-2">

    {{-- Cart breakdown --}}
    <div class="admin-card">
        <div class="admin-card__header">
            <span class="admin-card__title">Cart Contents</span>
            <span style="font-size:13px;font-weight:500;color:var(--gold)">
                {{ $inquiry->total_estimate ? '₹'.number_format($inquiry->total_estimate) : 'Total unknown' }}
            </span>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr><th>Product</th><th>Color</th><th>Size</th><th>Qty</th><th>Price</th></tr>
                </thead>
                <tbody>
                    @foreach($inquiry->cart_summary as $item)
                    <tr>
                        <td style="color:var(--text);font-weight:500">{{ $item['name'] ?? '—' }}</td>
                        <td style="font-size:12px;color:var(--text-muted)">{{ $item['color'] ?? '—' }}</td>
                        <td style="font-size:12px;color:var(--text-muted)">{{ $item['size'] ?? '—' }}</td>
                        <td>{{ $item['qty'] ?? 1 }}</td>
                        <td style="font-weight:500">{{ isset($item['price']) ? '₹'.number_format($item['price']) : '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Customer + Actions --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="admin-card">
            <div class="admin-card__header"><span class="admin-card__title">Customer</span></div>
            <div class="admin-card__body">
                <div style="margin-bottom:12px">
                    <div style="font-size:10px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px">Name</div>
                    <div style="font-size:14px;color:var(--text)">{{ $inquiry->customer_name ?: 'Not provided' }}</div>
                </div>
                <div style="margin-bottom:16px">
                    <div style="font-size:10px;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px">Phone</div>
                    <div style="font-size:14px;color:var(--text)">{{ $inquiry->customer_phone ?: 'Not provided' }}</div>
                </div>
                @if($whatsapp)
                @php
                    $items = collect($inquiry->cart_summary);
                    $msg = "Hi! Following up on inquiry #".$inquiry->id." from ".($inquiry->created_at->format('d M')).".\n\nItems:\n";
                    foreach($items as $item) {
                        $msg .= "• ".($item['name'] ?? '')." — ".($item['color'] ?? '').(isset($item['size']) ? ', '.$item['size'] : '')." x".($item['qty'] ?? 1)."\n";
                    }
                    $msg .= "\nTotal estimate: ".($inquiry->total_estimate ? '₹'.number_format($inquiry->total_estimate) : 'TBD');
                @endphp
                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($msg) }}" target="_blank"
                   class="btn-admin btn-admin--gold" style="width:100%;justify-content:center">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Open in WhatsApp
                </a>
                @endif
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header"><span class="admin-card__title">Update Status</span></div>
            <div class="admin-card__body">
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:16px">
                    Current: <strong style="color:var(--text)">{{ ucfirst($inquiry->status) }}</strong>
                </p>
                <div style="display:flex;flex-direction:column;gap:8px">
                    @foreach(['new' => 'Mark as New', 'contacted' => 'Mark as Contacted', 'completed' => 'Mark as Completed'] as $status => $label)
                    <form action="{{ route('admin.inquiries.markStatus', $inquiry) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $status }}">
                        <button type="submit"
                            class="btn-admin {{ $inquiry->status === $status ? 'btn-admin--gold' : 'btn-admin--outline' }}"
                            style="width:100%;justify-content:center">
                            {{ $label }}
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
