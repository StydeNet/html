<ul class="{{ $class }}">
@foreach ($items as $item)
@if (empty($item->items))
    <li {!! html_classes(['navbar-item', $item->class]) !!}>
        <a href="{{ $item->url }}"{{ html_classes(['navbar-item', 'active' => $item->active]) }}>
                {{ $item->text }}
            </a>
    </li>
    @else
    <li {!! html_classes(['navbar-item has-dropdown is-hoverable',$item->class]) !!}>
            <a href="{{ $item->url }}" class="navbar-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                {{ $item->text }}
            </a>
            <div class="navbar-dropdown">
                @foreach ($item->items as $subitem)
                    <a href="{{ $subitem->url }}"{!! html_classes(['navbar-item', 'active' => $subitem->active]) !!}>{{ $subitem->text }}</a>
                @endforeach
            </div>
        </li>
    @endif
@endforeach
</ul>
