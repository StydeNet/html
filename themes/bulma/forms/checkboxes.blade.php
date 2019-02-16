<div class="field">
    @foreach($checkboxes as [$checkbox, $label])
    <div class="control">
        {{ $checkbox->render() }}
        {{ $label->class('checkbox')->render() }}
    </div>
    @endforeach
</div>