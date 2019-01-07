<div id="field_{{ $id }}" class="field">
    <label class="label" for="{{ $id }}"{!! Html::classes(['text-danger' => $hasErrors]) !!}>
        {{ $label }}
@if ($required)
        <span class="tag is-success">Required</span>
@endif
    </label>
    <div class="field-body">
        <div class="field">
            <div class="control">
                {!! $input !!}
            </div>
            <p class="help is-danger">
            @foreach ($errors as $error)
                {{ $error }}
            @endforeach
            </p>
        </div>
    </div>
</div>
