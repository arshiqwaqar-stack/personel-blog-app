<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    <select name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' => 'form-select']) }} {!! $customattributes !!}>
        @foreach($options as $value => $text)
            <option value="{{ $value }}"  {{ $selected == $value ? 'selected' : '' }} >
                {{ $text }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>
