<h4{!! Html::classes(['text-danger' => $hasErrors]) !!}>
    {{ $label }}
@if ($required)
    <span class="badge badge-info">Required</span>
@endif
</h4>
{!! $input !!}
@foreach ($errors as $error)
    <p class="text-danger">{{ $error }}</p>
@endforeach

<br>