<div class="sidebar" data-background-color="black">

    <div class="logo">
        <a class="simple-text logo-mini" href="{{ url('home') }}">
            <i class="material-icons">home</i>
        </a>
        <a class="simple-text logo-normal" href="{{ url('home') }}">
            {{ config('app.name') }}
        </a>
    </div>

    <div class="sidebar-wrapper">
        <ul class="nav" id="sidemenu">

            <!-- NOTIFICATIONS -->

            @if (isset($data->notifications) && $data->notifications->count())
            <li class="nav-item d-lg-none">
                <a class="nav-link" href="{{ url('home') }}">
                    <i class="material-icons">notifications</i>
                    <span class="badge badge-danger float-right">
                        {{ $data->notifications->count() }}
                    </span>
                    <p>Notifications </p>
                </a>
            </li>
            @endif

            <!-- USER MENU ITEMS -->

            @foreach($userMenu as $item)
                <li class="nav-item d-lg-none">
                    @if(isset($item['params']))
                    <a class="nav-link" href="{{ route($item['route'], $item['params']) }}">
                    @else
                    <a class="nav-link" href="{{ route($item['route']) }}">
                    @endif
                        <i class="material-icons">{{ $item['icon'] }}</i>
                        <p> @lang($item['title']) </p>
                    </a>
                </li>
            @endforeach

            <!-- SIDE MENU -->

            @foreach($sideMenu as $location => $menu)
                @if(!empty($menu['children']))
                <li class="nav-item ">
                    <a class="nav-link" data-toggle="collapse" href="#sidemenu-{{$location}}">
                        <i class="material-icons">{{ $menu['icon'] }}</i>
                        <p> @lang($menu['title'])
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="sidemenu-{{$location}}" data-parent="#sidemenu">
                        <ul class="nav">
                            @foreach($menu['children'] as $title => $item)
                                <li class="nav-item ">
                                    @if(isset($item['params']))
                                    <a class="nav-link" href="{{ route($item['route'], $item['params']) }}">
                                    @else
                                    <a class="nav-link" href="{{ route($item['route']) }}">
                                    @endif
                                        <span class="sidebar-mini"> . </span>
                                        <span class="sidebar-normal"> @lang($item['title']) </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                @endif
            @endforeach

            <!-- LOGOUT -->    

            <li class="nav-item d-lg-none">
                <a class="nav-link" href="{{ route('logout') }}" onclick="
                        event.preventDefault();
                        document.getElementById('sidebar-logout-form').submit();">
                    <i class="material-icons">power_settings_new</i>
                    <p> @lang('trax-account::common.logout') </p>
                </a>
                <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>

        </ul>
    </div>
</div>
