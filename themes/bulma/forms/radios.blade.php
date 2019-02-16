<div class="field">
<div class="control">
@foreach($radios as [$radio, $label])

    {{ $radio->render() }}
    {{ $label->class('radio')->render() }}

@endforeach
</div>
</div>