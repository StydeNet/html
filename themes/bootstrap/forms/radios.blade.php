@foreach($labels as $label)
    <div class="radio">
        {{ $label->open() }}
            {{ $label->radio->render() }}
            {{ $label->text }}
        {{ $label->close() }}
    </div>
@endforeach