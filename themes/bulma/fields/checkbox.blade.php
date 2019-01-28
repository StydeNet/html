<div id="field_{{ $id }}" class="field">
    <div class="control">
        {!! $input !!}
        <label class="checkbox" for="{{ $id }}"{!! Html::classes(['text-danger' => $hasErrors]) !!}>
            {{ $label }}
        @if ($required)
            <span class="tag is-success">Required</span>
        @endif
</label>
        <p class="help is-danger">
        @foreach ($errors as $error)
            {{ $error }}
        @endforeach
    </p>
    </div>
</div>
