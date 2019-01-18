
<nav>
	<ol class="breadcrumb">
		@foreach ($nav->breadcrumb as $url => $label)
			@if ($loop->last)
				<li class="breadcrumb-item active">{{ $label }}</li>
			@elseif (is_string($url))
				<li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
			@else
				<li class="breadcrumb-item">{{ $label }}</li>
			@endif
		@endforeach
	</ol>
</nav>
