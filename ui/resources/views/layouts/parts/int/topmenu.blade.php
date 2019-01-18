
@foreach($topMenu as $location => $menu)
	<li class="nav-item dropdown">
		<a class="nav-link" href="#" id="topmenu-{{$location}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="material-icons">{{ $menu['icon'] }}</i>
		</a>
		<div class="dropdown-menu dropdown-menu-right pt-2 pb-2" style="width:{{$menu['width']}};" aria-labelledby="topmenu-{{$location}}">
			<div class="row">
				@foreach($menu['children'] as $title => $item)
					<div class="col">
						<h6 class="dropdown-header">@lang($title)</h6>
						@foreach($item as $code => $item)
							@if(isset($item['params']))
							<a class="dropdown-item" href="{{ route($item['route'], $item['params']) }}">
							@else
							<a class="dropdown-item" href="{{ route($item['route']) }}">
							@endif
								@lang($item['title'])
							</a>
						@endforeach
					</div>
				@endforeach
			</div>
		</div>
	</li>
@endforeach
