@extends('template._base-collection')

<?php
	$header_classes = [];
    $hide_date = false;
 	if(isset($item)) {
    	$site_title = $item->title.' · '.config('folio.title');
		$collection = $item->collection();
        $hide_date = $item->boolProperty('hide-date', $hide_date);
  	}
?>

@section('content')

		<div class="[ o-wrap o-wrap--size-standard ]">

		  	@isset($items_expected))
				@foreach($items_expected as $i)
					{!! view('folio::partial.c-item-li')->with(['item' => $i, 'expected' => true]) !!}
				@endforeach
			@endisset

			@foreach($collection as $i)
				{!! view('folio::partial.c-item-li')->with(['item' => $i]) !!}
			@endforeach

			@if(isset($ids))
				{!! view('folio::partial.c-load-more')->with(['ids' => $ids]) !!}
			@endif
		</div>

@stop