@php
    $classes = 'form-check-input';
    if ($hasErrors) {
        $classes .= ' is-invalid';
    }
@endphp
<div class="control">
@foreach($checkboxes as $checkbox)
        {!! Form::checkbox(
            $checkbox['name'],
            $checkbox['value'],
            $checkbox['checked'],
            ['class' => $classes, 'id' => $checkbox['id']]
        ) !!}
        <label class="checkbox" for="{{ $checkbox['id'] }}">
            {{ $checkbox['label'] }}
        </label>
@endforeach
</div>