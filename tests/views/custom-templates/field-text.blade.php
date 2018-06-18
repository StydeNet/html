<p>Custom field template</p>
<div id="field_{{ $id }}" class="form-group">
    <label for="{{ $id }}"{!! html_classes(['text-danger' => $hasErrors]) !!}>
        {{ $label }}
@if ($required)
        <span class="badge badge-info">Required</span>
@endif
    </label>
    {{ $input->id($id)->classes(['form-control', 'is-invalid' => $hasErrors]) }}
@foreach ($errors as $error)
    <div class="invalid-feedback">{{ $error }}</div>
@endforeach
</div>
