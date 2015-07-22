<div id="field_{{ $id }}"{!! Html::classes(['checkbox', 'error' => $hasErrors]) !!}>
    <label>
        {{ $label }}
        {!! $input !!}
    </label>
    @if ($required)
     <span class="label label-info">Required</span>
    @endif
    <div class="controls">
        @foreach ($errors as $error)
            <p class="help-block">{{ $error }}</p>
        @endforeach
    </div>
</div>