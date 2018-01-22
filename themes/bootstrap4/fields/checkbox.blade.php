<div id="field_{{ $id }}" class="form-check">
    {!! $input !!}
    <label class="form-check-label" for="{{ $id }}">
        {{ $label }}
@if ($required)
        <span class="badge badge-info">Required</span>
@endif
    </label>
@foreach ($errors as $error)
    <div class="invalid-feedback">{{ $error }}</div>
@endforeach
</div>
