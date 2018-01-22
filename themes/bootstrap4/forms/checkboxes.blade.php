@php
    $classes = 'form-check-input';
    if ($hasErrors) {
        $classes .= ' is-invalid';
    }
@endphp
@foreach($checkboxes as $checkbox)
    <div class="form-check">
        {!! Form::checkbox(
            $checkbox['name'],
            $checkbox['value'],
            $checkbox['checked'],
            ['class' => $classes, 'id' => $checkbox['id']]
        ) !!}
        <label class="form-check-label" for="{{ $checkbox['id'] }}">
            {{ $checkbox['label'] }}
        </label>
    </div>
@endforeach
