<ul class="{{ $class }}">
@foreach ($items as $item)
    @if (empty ($item->items))
        <li{!! html_classes(['nav-item', $item->class, 'active' => $item->active]) !!}>
            <a href="{{ $item->url() }}" class="nav-link">
                {{ $item->text }}
            </a>
        </li>
    @else
        <li{!! html_classes(['nav-item', 'dropdown', $item->class, 'active' => $item->active]) !!}>
            <a href="{{ $item->url() }}" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                {{ $item->text }}
            </a>
            <div class="dropdown-menu">
                @foreach ($item->items as $subitem)
                    <a href="{{ $subitem->url() }}"{!! html_classes(['dropdown-item', 'active' => $subitem->active]) !!}>{{ $subitem->text }}</a>
                @endforeach
            </div>
        </li>
    @endif
@endforeach
</ul>
