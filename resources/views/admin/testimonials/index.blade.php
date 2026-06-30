@extends('layouts.admin')
@section('title', 'Testimonials')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Testimonials</h1>
    <a href="{{ route('admin.testimonials.create') }}" class="btn-admin btn-admin--gold">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Testimonial
    </a>
</div>

@if($testimonials->isEmpty())
    <div class="admin-card">
        <div class="admin-card__body" style="text-align:center;padding:60px 40px">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
            <p style="font-size:16px;color:var(--text);margin:16px 0 8px">No testimonials yet</p>
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:20px">Add customer testimonials to build trust and social proof.</p>
            <a href="{{ route('admin.testimonials.create') }}" class="btn-admin btn-admin--gold">Add First Testimonial</a>
        </div>
    </div>
@else
    <div class="admin-card" style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Customer</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testimonials as $testimonial)
                <tr>
                    <td style="width:48px">
                        @if($testimonial->image)
                            <img src="{{ Storage::url($testimonial->image) }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover">
                        @else
                            <div style="width:40px;height:40px;border-radius:50%;background:var(--gold-dim);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;color:var(--gold)">{{ strtoupper(substr($testimonial->customer_name, 0, 1)) }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:500;color:var(--text)">{{ $testimonial->customer_name }}</div>
                        @if($testimonial->customer_title)
                            <div style="font-size:11px;color:var(--text-muted)">{{ $testimonial->customer_title }}</div>
                        @endif
                    </td>
                    <td style="max-width:300px">
                        <div style="font-size:12px;color:var(--text-dim);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ Str::limit($testimonial->content, 80) }}</div>
                    </td>
                    <td>
                        <span style="color:var(--gold);font-size:12px">{{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $testimonial->is_active ? 'badge--green' : 'badge--gray' }}">{{ $testimonial->is_active ? 'Active' : 'Hidden' }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn-admin btn-admin--outline btn-admin--sm">Edit</a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-admin btn-admin--outline btn-admin--sm" style="color:#ef4444">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px">
        {{ $testimonials->links() }}
    </div>
@endif
@endsection
