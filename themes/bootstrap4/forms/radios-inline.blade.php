@php
    $class = 'form-check-input';
    if ($hasErrors) {
        $class .= ' is-invalid';
    }
@endphp
@foreach($radios as $radio)
    <div class="form-check form-check-inline">
        {!! Form::radio(
            $radio['name'],
            $radio['value'],
            $radio['selected'],
            ['class' => $classes, 'id' => $radio['id']]) !!}
        <label class="form-check-label" for="{{ $radio['id'] }}">
            {{ $radio['label'] }}
        </label>
    </div>
@endforeach
