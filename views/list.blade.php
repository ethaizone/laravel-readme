<h2>Available Documentation</h2>
<ul>
@foreach ($bundles as $bundle)
	<li><a href="{{ URL::to('/readme/'.$bundle.'/readme') }}">{{ Str::title($bundle) }}</a></li>
@endforeach
</ul>