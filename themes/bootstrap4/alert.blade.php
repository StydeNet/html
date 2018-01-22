@foreach ($messages as $msg)
    <div class="alert alert-{{ $msg['type'] }} alert-dismissible fade show" role="alert">
        <strong>{{ $msg['message'] }}</strong>
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
        <p>
            @foreach ($msg['buttons'] as $btn)
                <a class="btn btn-{{ $btn['class'] }}" href="{{ $btn['url'] }}">{{ $btn['text'] }}</a>
            @endforeach
        </p>
    @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endforeach
