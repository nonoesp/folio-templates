@extends('template._base-collection')

<?php

	$header_classes = ['borderless'];
	$header_data = [
		'is_media_hidden' => true,
		'image' => null,
	];
	$cover_hidden = true;

	$site_title = $item->title.' | '.config('folio.title');	
	$og_title = $site_title;
	$og_image = $item->thumbnail();

	if(isset($item)) {
		$collection = $item->collection();
	}

	// Sort by Item's custom property
	if(isset($collection) and count($collection)) {
		if($sort = $item->stringProperty('collection-sort-property', '')) {
			// There is collection-sort-property in this collection
			$collection = $collection->sortBy(function($item) use ($sort) {
  				return $item->stringProperty($sort);
			});
		} else {
			// There is no collection-sort-property
		}
	}

?>

@section('content')

	<div class="[ o-wrap o-wrap--size-700 ]">

		<ul class="[ c-gridview ]">

			@isset($collection)

				@foreach($collection as $i)

					<?php
					$meta_key = $item->stringProperty('collection-meta', 'date');
					$link_key = $item->stringProperty('collection-link-key', 'link');
					$date = Date::parse($i->published_at)->format('F j, Y');
					$link = null;
					
					if($i->boolProperty($link_key, true)) {
						if($link = $i->stringProperty('shop-link')) {

						} else {
							$link = '/'.$i->path();
						}
					}

					if($price = $i->stringProperty($meta_key)) {
						$meta = $price;
					} else {
						$meta = $date;
					}
					
					if($user = Auth::user()) {
						if($user->is_admin) {
							$admin = true;
						}
					}
  								
					?>

					<li>
						<a @isset($link)href="{{ $link }}"@endisset>
							<img src="{{ $i->thumbnail() }}">
							{{ $i->title }}
							<small>{{ $meta }}</small>
						</a>
						@isset($admin)
							<small class="u-opacity--low">
								<a href="{{ $i->editPath() }}">edit</a>
								@if($i->trashed())
								· hidden
								@endif
							</small>
						@endisset
					</li>
				
				@endforeach

			@endisset

		</ul>

	</div>

@stop