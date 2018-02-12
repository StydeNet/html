<div id="field_{{ $id }}" class="form-check">
    {{ $input->classes(['form-check-input', 'is-invalid' => $hasErrors]) }}
    <label{!! html_classes([form-check-label, 'text-danger' => $hasErrors]) !!} for="{{ $id }}">
        {{ $label }}
        @if ($required)
            <span class="badge badge-info">Required</span>
        @endif
    </label>
    @foreach ($errors as $error)
        <div class="invalid-feedback">{{ $error }}</div>
    @endforeach
</div>
