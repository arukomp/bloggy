<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            @foreach ($menu as $menuItem)
                @if (isset($menuItem['type']) && $menuItem['type'] == 'divider')
                <li class="nav-item">
                    <hr>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link @if(isset($menuItem['active']) && $menuItem['active']) active @endif" href="{{ $menuItem['link'] }}">
                        <span data-feather="{{ $menuItem['icon'] }}"></span>
                        {{ $menuItem['name'] }}
                        @if(isset($menuItem['active']) && $menuItem['active'])
                            <span class="sr-only">(current)</span>
                        @endif
                    </a>
                </li>
                @endif
            @endforeach
        </ul>
    </div>
</nav>