
<li class="nav-item dropdown">
	<a class="nav-link" href="#" id="usermenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="material-icons">person</i>
	</a>
	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="usermenu">

		<!-- USER MENU ITEMS -->

		@foreach($userMenu as $item)
			@if(isset($item['params']))
			<a class="dropdown-item" href="{{ route($item['route'], $item['params']) }}">
			@else
			<a class="dropdown-item" href="{{ route($item['route']) }}">
			@endif
				@lang($item['title'])
			</a>
		@endforeach

		<!-- LOGOUT -->
		
		<div class="dropdown-divider"></div>
		<a class="dropdown-item" href="{{ route('logout') }}" onclick="
				event.preventDefault();
				document.getElementById('topbar-logout-form').submit();">
			@lang('trax-account::common.logout')
		</a>
		<form id="topbar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			{{ csrf_field() }}
		</form>

	</div>
</li>
