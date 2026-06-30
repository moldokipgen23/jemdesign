@extends('layouts.admin')
@section('title', 'Attributes')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-header__title">Attributes</h1>
        <p class="admin-page-header__sub">Global product attributes — Color, Size, Material, etc.</p>
    </div>
</div>

<div class="admin-grid-2">
    {{-- Left: Add new attribute --}}
    <div class="admin-card">
        <div class="admin-card__header"><span class="admin-card__title">Add Attribute</span></div>
        <div class="admin-card__body">
            <form id="addAttrForm" onsubmit="return addAttribute(event)" style="display:flex;gap:10px;align-items:flex-end">
                <div style="flex:1">
                    <label style="display:block;font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px">Attribute Name</label>
                    <input type="text" id="attrName" class="admin-input" placeholder="e.g. Color, Size, Material" style="width:100%">
                </div>
                <button type="submit" class="btn-admin btn-admin--gold">+ Add</button>
            </form>
        </div>
    </div>

    {{-- Right: Existing attributes --}}
    <div>
        @forelse($attributes as $attr)
        <div class="admin-card" style="margin-bottom:16px" id="attr-{{ $attr->id }}">
            <div class="admin-card__header" style="display:flex;align-items:center;gap:10px">
                <span class="admin-card__title" style="flex:1">{{ $attr->name }}</span>
                <span style="font-size:11px;color:var(--text-muted)">{{ $attr->values->count() }} value(s)</span>
                <form action="{{ route('admin.attributes.destroy', $attr) }}" method="POST" onsubmit="return confirm('Delete this attribute and all its values?')">
                    @csrf @method('DELETE')
                    <button style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:16px" title="Delete">×</button>
                </form>
            </div>
            <div class="admin-card__body">
                {{-- Existing values --}}
                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px" id="values-{{ $attr->id }}">
                    @foreach($attr->values as $val)
                    <div style="display:flex;align-items:center;gap:6px;padding:5px 10px;border:1px solid var(--border);border-radius:4px;background:var(--bg-input)">
                        @if($val->hex_code)
                            <div style="width:14px;height:14px;border-radius:50%;background:{{ $val->hex_code }};border:1px solid var(--border);flex-shrink:0"></div>
                        @endif
                        <span style="font-size:12px;color:var(--text-dim)">{{ $val->name }}</span>
                        <form action="{{ route('admin.attributes.values.destroy', [$attr, $val]) }}" method="POST" onsubmit="return confirm('Delete this value?')">
                            @csrf @method('DELETE')
                            <button style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:12px;padding:0;line-height:1">×</button>
                        </form>
                    </div>
                    @endforeach
                </div>

                {{-- Add new value --}}
                <div style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap">
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px">Value Name</label>
                        <input type="text" class="admin-input val-name" data-attr="{{ $attr->id }}" placeholder="e.g. Dusty Blue" style="width:160px">
                    </div>
                    @if(in_array($attr->slug, ['color', 'colours']))
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px">Hex Code</label>
                        <input type="color" class="val-hex" data-attr="{{ $attr->id }}" value="#C9A04E" style="width:40px;height:34px;border:1px solid var(--border);border-radius:3px;background:transparent;cursor:pointer;padding:2px">
                    </div>
                    @endif
                    <button type="button" class="btn-admin btn-admin--gold btn-admin--sm" onclick="addValue({{ $attr->id }})">+ Add Value</button>
                </div>
            </div>
        </div>
        @empty
        <div class="admin-card">
            <div class="admin-card__body" style="text-align:center;padding:40px">
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:8px">No attributes yet.</p>
                <p style="color:var(--text-muted);font-size:12px">Create your first attribute (e.g. Color, Size) to get started.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const JSON_HEADERS = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' };

async function addAttribute(e) {
    e.preventDefault();
    const name = document.getElementById('attrName').value.trim();
    if (!name) return;
    const res = await fetch('{{ route("admin.attributes.store") }}', {
        method: 'POST', headers: JSON_HEADERS,
        body: JSON.stringify({ name })
    });
    const data = await res.json();
    if (data.success) location.reload();
}

async function addValue(attrId) {
    const card = document.getElementById('attr-' + attrId);
    const name = card.querySelector('.val-name').value.trim();
    if (!name) return;
    const hexInput = card.querySelector('.val-hex');
    const hex = hexInput ? hexInput.value : null;
    const res = await fetch(`/admin/attributes/${attrId}/values`, {
        method: 'POST', headers: JSON_HEADERS,
        body: JSON.stringify({ name, hex_code: hex })
    });
    const data = await res.json();
    if (data.success) location.reload();
}
</script>
@endsection
