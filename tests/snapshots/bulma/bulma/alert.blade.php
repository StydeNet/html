@foreach ($messages as $msg)
    <article class="message is-{{ $msg['type'] }}">
        <div class="message-header">
            <p><strong>Alert!</strong></p>
            <button type="button" onclick="this.parentElement.parentElement.style.display = 'none';" class="delete" aria-label="delete"></button>
        </div>
        <div class="message-body">
            <p>  <strong>{{ $msg['message'] }}</strong></p>
            @if (!empty ($msg['details']))
                {{ $msg['details'] }}
            @endif
            {!! $msg['html'] !!}
            @if (!empty ($msg['items']))
                <ul>
                    @foreach ($msg['items'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @endif
            @if ( ! empty ($msg['buttons']))
                <div class="is-right-to-left">
                    @foreach ($msg['buttons'] as $btn)
                        <a class="button is-{{ $btn['class'] }}" href="{{ $btn['url'] }}">{{ $btn['text'] }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </article>
@endforeach



