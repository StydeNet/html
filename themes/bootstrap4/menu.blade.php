<ul class="{{ $class }}">
@foreach ($items as $item)
    @if (empty($item['submenu']))
        <li id="menu_{{ $item['id'] }}" {!! Html::classes('nav-item', $item['class']) !!}>
            <a href="{{ $item['url'] }}" class="nav-link">
                {{ $item['title'] }}
            </a>
        </li>
    @else
        <li id="menu_{{ $item['id'] }}" {!! Html::classes('nav-item', $item['class']) !!}>
            <a href="{{ $item['url'] }}" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                {{ $item['title'] }}
            </a>
            <div class="dropdown-menu">
                @foreach ($item['submenu'] as $subitem)
                    <a href="{{ $subitem['url'] }}" class="dropdown-item">{{ $subitem['title'] }}</a>
                @endforeach
            </div>
        </li>
    @endif
@endforeach
</ul>
