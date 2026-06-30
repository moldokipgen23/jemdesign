@extends('layouts.admin')
@section('title', 'Marketing Sections')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-header__title">Marketing Sections</h1>
    <a href="{{ route('admin.marketing.create') }}" class="btn-admin btn-admin--gold">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Section
    </a>
</div>

@if($sections->isEmpty())
    <div class="admin-card">
        <div class="admin-card__body" style="text-align:center;padding:60px 40px">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            <p style="font-size:16px;color:var(--text);margin:16px 0 8px">No marketing sections yet</p>
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:20px">Create sections like "Trending Now", "New Arrivals", "Best Sellers" to showcase products on your homepage.</p>
            <a href="{{ route('admin.marketing.create') }}" class="btn-admin btn-admin--gold">Create First Section</a>
        </div>
    </div>
@else
    <div id="sectionsList" style="display:flex;flex-direction:column;gap:8px;max-width:800px">
        @foreach($sections as $section)
        <div class="admin-card" data-section-id="{{ $section->id }}" style="cursor:grab">
            <div class="admin-card__body" style="display:flex;align-items:center;gap:16px;padding:16px 20px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="flex-shrink:0;cursor:grab"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>

                <div style="flex:1;min-width:0">
                    <div style="font-size:14px;font-weight:600;color:var(--text)">{{ $section->title }}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px">
                        <span class="badge badge--outline">{{ $section->type }}</span>
                        <span class="badge badge--outline">{{ $section->display_style }}</span>
                        {{ $section->items_per_row }} columns
                        @if(in_array($section->type, ['category', 'collection']) && $section->filter_value)
                            — {{ $section->type === 'category' ? 'Category' : 'Collection' }}: {{ $section->type === 'category' ? \App\Models\Category::find($section->filter_value)?->name : \App\Models\Collection::find($section->filter_value)?->name }}
                        @endif
                        @if($section->type === 'manual')
                            — {{ $section->items_count }} item(s)
                        @endif
                    </div>
                </div>

                <label style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0">
                    <input type="checkbox" {{ $section->is_enabled ? 'checked' : '' }} onchange="toggleSection({{ $section->id }}, this.checked)" style="opacity:0;width:0;height:0">
                    <span style="position:absolute;cursor:pointer;inset:0;background:var(--border);border-radius:24px;transition:.3s"></span>
                    <span style="position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s;pointer-events:none"></span>
                </label>

                <div style="display:flex;gap:6px;flex-shrink:0">
                    <a href="{{ route('admin.marketing.edit', $section) }}" class="btn-admin btn-admin--outline btn-admin--sm">Edit</a>
                    <form action="{{ route('admin.marketing.destroy', $section) }}" method="POST" onsubmit="return confirm('Delete this section?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-admin btn-admin--outline btn-admin--sm" style="color:#ef4444">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
async function toggleSection(id, enabled) {
    await fetch(`/admin/marketing/${id}/toggle`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('sectionsList');
    if (!list) return;

    new Sortable(list, {
        handle: 'svg',
        animation: 150,
        ghostClass: 'drag-ghost',
        onEnd: async () => {
            const order = [...list.querySelectorAll('[data-section-id]')].map(el => el.dataset.sectionId);
            await fetch('{{ route("admin.marketing.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order })
            });
        }
    });
});
</script>
@endpush
