@foreach($checkboxes as [$checkbox, $label])
<div class="form-check">
    {{ $checkbox->class('form-check-input')->render() }}
    {{ $label->class('form-check-label')->render() }}
</div>
@endforeach
