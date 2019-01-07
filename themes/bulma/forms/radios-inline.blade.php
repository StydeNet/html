@php
    $classes = 'form-check-input';
    if ($hasErrors) {
        $classes .= ' is-invalid';
    }
@endphp
<div class="control">
@foreach($radios as $radio)

        {!! Form::radio(
            $radio['name'],
            $radio['value'],
            $radio['selected'],
            ['class' => $classes, 'id' => $radio['id']]) !!}
        <label class="radio" for="{{ $radio['id'] }}">
            {{ $radio['label'] }}
        </label>

@endforeach
</div>