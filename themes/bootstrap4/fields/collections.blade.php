<div id="field_{{ $id }}" class="form-group">
    <label for="{{ $id }}" {!! html_classes(['text-danger' => $hasErrors]) !!}>
        {{ $label }}@if ($required) <span class="badge badge-info">Required</span>@endif
</label>
{!! $input !!}
@if ($helpText)
    <small id="help_block_{{ $id }}" class="form-text text-muted">{{ $helpText }}</small>
@endif
@foreach ($errors as $error)
<div class="text-danger">{{ $error }}</div>
@endforeach
</div>