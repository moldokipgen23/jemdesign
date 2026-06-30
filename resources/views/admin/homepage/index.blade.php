@extends('layouts.admin')
@section('title', 'Homepage Sections')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Homepage Sections</h1>
        <p class="admin-page-header__sub">Toggle and reorder sections — changes reflect on the live site instantly</p>
    </div>
</div>

<div class="admin-card" style="max-width:720px">
    <form action="{{ route('admin.homepage.update') }}" method="POST" id="sectionsForm">
        @csrf
        <div id="sectionsList">
            @foreach($sections as $i => $section)
            <div class="section-row" style="border-bottom:1px solid var(--border);padding:16px 24px">
                <div style="display:flex;align-items:center;gap:16px">
                    <span style="cursor:grab;color:var(--text-muted);font-size:18px;line-height:1" title="Drag to reorder">⠿</span>

                    <input type="hidden" name="sections[{{ $i }}][id]" value="{{ $section->id }}">
                    <input type="hidden" name="sections[{{ $i }}][sort_order]" value="{{ $section->sort_order }}" class="sort-input">

                    <div style="flex:1">
                        <span style="font-size:13px;font-weight:500;color:var(--text);text-transform:capitalize">
                            {{ str_replace('_', ' ', $section->section_key) }}
                        </span>
                        <span style="font-size:11px;color:var(--text-muted);margin-left:8px">{{ $section->section_key }}</span>
                    </div>

                    <label class="admin-toggle">
                        <input type="checkbox" name="sections[{{ $i }}][is_enabled]" value="1" {{ $section->is_enabled ? 'checked' : '' }}>
                        <span class="admin-toggle__track"></span>
                        <span class="admin-toggle__label">{{ $section->is_enabled ? 'Enabled' : 'Hidden' }}</span>
                    </label>
                </div>

                {{-- Image Upload per Section --}}
                <form action="{{ route('admin.homepage.updateImage', $section) }}" method="POST" enctype="multipart/form-data" style="margin-top:12px;margin-left:34px">
                    @csrf
                    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                        @if($section->image_path)
                            <img src="{{ Storage::url($section->image_path) }}" style="width:60px;height:40px;object-fit:cover;border-radius:4px;border:1px solid var(--border)">
                        @endif
                        <label style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:var(--bg-input);border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;font-size:12px;color:var(--text-dim);transition:all .15s" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            {{ $section->image_path ? 'Replace Image' : 'Upload Image' }}
                            <input type="file" name="image_path" accept="image/*" style="display:none" onchange="this.form.submit()">
                        </label>
                        <span style="font-size:11px;color:var(--text-muted)">{{ str_replace('_', ' ', $section->section_key) }} background. Recommended: 1920×800px.</span>
                    </div>
                </form>
            </div>
            @endforeach
        </div>

        <div style="padding:20px 24px">
            <button type="submit" class="btn-admin btn-admin--gold">Save Order & Visibility</button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.section-row').forEach((row, idx, rows) => {
    const handle = row.querySelector('span[title="Drag to reorder"]');
    handle.style.cursor = 'pointer';
    handle.addEventListener('click', () => {
        const prev = row.previousElementSibling;
        if (prev && prev.classList.contains('section-row')) {
            row.parentNode.insertBefore(row, prev);
            updateSortOrders();
        }
    });
});

function updateSortOrders() {
    document.querySelectorAll('.section-row').forEach((row, i) => {
        row.querySelector('.sort-input').value = i;
        row.querySelectorAll('[name^="sections["]').forEach(input => {
            input.name = input.name.replace(/sections\[\d+\]/, `sections[${i}]`);
        });
    });
}
</script>
@endsection
