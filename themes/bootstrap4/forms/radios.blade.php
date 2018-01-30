@foreach($radios as [$radio, $label])
<div class="form-check">
    {{ $radio->class('form-check-input')->render() }}
    {{ $label->class('form-check-label')->render() }}
</div>
@endforeach
