@props(['settings', 'key', 'label', 'type' => 'text', 'placeholder' => ''])

@php $current = $settings->get($key)?->value ?? ''; @endphp

<div class="admin-form-group">
    <label>{{ $label }}</label>

    @if($type === 'textarea')
        <textarea name="{{ $key }}" class="admin-textarea" style="min-height:100px">{{ old($key, $current) }}</textarea>

    @elseif($type === 'file')
        @if($current)
            @if(in_array(pathinfo($current, PATHINFO_EXTENSION), ['jpg','jpeg','png','gif','webp']))
                <img src="{{ Storage::url($current) }}" style="display:block;width:100px;height:100px;object-fit:cover;border-radius:4px;margin-bottom:8px;border:1px solid var(--border)">
            @else
                <p style="font-size:11px;color:var(--text-muted);margin-bottom:8px">Current: {{ basename($current) }}</p>
            @endif
        @endif
        <input type="file" name="{{ $key }}" class="admin-input" accept="image/*">

    @else
        <input type="text" name="{{ $key }}" class="admin-input"
               value="{{ old($key, $current) }}"
               placeholder="{{ $placeholder }}">
    @endif
</div>
