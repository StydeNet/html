<ul class="{{ $class }}">
@foreach ($items as $item)
    @if (empty($item['submenu']))
        <li id="menu_{{ $item['id'] }}" {!! Html::classes('navbar-item', $item['class']) !!}>
            <a href="{{ $item['url'] }}" class="navbar-item">
                {{ $item['title'] }}
            </a>
        </li>
    @else
        <li id="menu_{{ $item['id'] }}" {!! Html::classes('navbar-item has-dropdown is-hoverable', $item['class']) !!}>
            <a href="{{ $item['url'] }}" class="navbar-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                {{ $item['title'] }}
            </a>
            <div class="navbar-dropdown">
                @foreach ($item['submenu'] as $subitem)
                    <a href="{{ $subitem['url'] }}" class="navbar-item">{{ $subitem['title'] }}</a>
                @endforeach
            </div>
        </li>
    @endif
@endforeach
</ul>
