@php $sidebarUser = auth()->user(); @endphp
@if ($item && $sidebarUser && ((method_exists($item, 'authorize') && $item->authorize($sidebarUser)) || (is_callable($item->authorize ?? null) && ($item->authorize)($sidebarUser))))
<li class="nav-item">
    <a class="nav-link {{ Request::is(is_callable([$item, 'getActivePath']) ? $item->getActivePath() : (is_callable($item->getActivePath ?? null) ? ($item->getActivePath)() : ($item->getActivePath ?? ''))) ? 'active' : '' }}"
       href="{{ is_callable([$item, 'getHref']) ? $item->getHref() : (is_callable($item->getHref ?? null) ? ($item->getHref)() : ($item->getHref ?? '#')) }}"
       @if(is_callable([$item, 'isDropdown']) ? $item->isDropdown() : (is_callable($item->isDropdown ?? null) ? ($item->isDropdown)() : false))
    data-toggle="collapse"
       aria-expanded="{{ Request::is(is_callable([$item, 'getExpandedPath']) ? $item->getExpandedPath() : (is_callable($item->getExpandedPath ?? null) ? ($item->getExpandedPath)() : ($item->getExpandedPath ?? ''))) ? 'true' : 'false' }}"
       @endif
    >
        @php
            $icon = is_callable([$item, 'getIcon']) ? $item->getIcon() : (is_callable($item->getIcon ?? null) ? ($item->getIcon)() : ($item->getIcon ?? null));
            $title = is_callable([$item, 'getTitle']) ? $item->getTitle() : (is_callable($item->getTitle ?? null) ? ($item->getTitle)() : ($item->getTitle ?? ''));
        @endphp
        @if ($icon)
            <i class="{{ $icon }}"></i>
        @endif
        <span>{{ $title }}</span>
    </a>
    @if(is_callable([$item, 'isDropdown']) ? $item->isDropdown() : (is_callable($item->isDropdown ?? null) ? ($item->isDropdown)() : false))
        <ul class="collapse list-unstyled sub-menu {{ Request::is(is_callable([$item, 'getExpandedPath']) ? $item->getExpandedPath() : (is_callable($item->getExpandedPath ?? null) ? ($item->getExpandedPath)() : ($item->getExpandedPath ?? ''))) ? 'show' : '' }}"
            id="{{ str_replace('#', '', is_callable([$item, 'getHref']) ? $item->getHref() : (is_callable($item->getHref ?? null) ? ($item->getHref)() : ($item->getHref ?? '#'))) }}">
            @foreach ((is_callable([$item, 'children']) ? $item->children() : (is_callable($item->children ?? null) ? ($item->children)() : [])) as $child)
                @include('partials.sidebar.items', ['item' => $child])
            @endforeach
        </ul>
    @endif
</li>
@endif