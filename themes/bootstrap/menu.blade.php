<ul class="{{ $class }}">
@foreach ($items as $item)
    <li @if ($item['class']) class="{{ $item['class'] }}" @endif id="menu_{{ $item['id'] }}">
    @if ($item['submenu'])
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    @else
        <a href="{{ $item['url'] }}">
    @endif
            {{ $item['title'] }}
            @if ($item['submenu'])
            <b class="caret"></b>
            @endif
        </a>
        @if ($item['submenu'])
        <ul class="dropdown-menu">
            @foreach ($item['submenu'] as $subitem)
            <li><a href="{{ $subitem['url'] }}">{{ $subitem['title'] }}</a></li>
            @endforeach
        </ul>
        @endif
    </li>
@endforeach
</ul>