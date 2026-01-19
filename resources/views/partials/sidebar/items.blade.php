@if ($item && $item->authorize(auth()->user()))
<li class="nav-item">
    <a class="nav-link {{ Request::is($item->getActivePath()) ? 'active' : '' }}"
       href="{{ $item->getHref() }}"
       @if($item->isDropdown())
       data-bs-toggle="collapse"
       aria-expanded="{{ Request::is($item->getExpandedPath()) ? 'true' : 'false' }}"
       @endif
    >
        @if ($item->getIcon())
            <i class="{{ $item->getIcon() }}"></i>
        @endif
        
        <span>{{ $item->getTitle() }}</span>
        
    </a>
    
    @if ($item->isDropdown())
        <ul class="collapse list-unstyled sub-menu {{ Request::is($item->getExpandedPath()) ? 'show' : '' }}"
            id="{{ str_replace('#', '', $item->getHref()) }}">
            @foreach ($item->children() as $child)
                @include('partials.sidebar.items', ['item' => $child])
            @endforeach
        </ul>
    @endif
</li>
@endif