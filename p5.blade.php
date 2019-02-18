@extends('folio::template._base')

<?php
  $cover_hidden = true;
  $header_view = 'folio::partial.c-header-simple';
  $header_classes = ['borderless', 'tight', 'center', 'absolute'];
  $header_data = [
    'image' => null,
		'is_navigation_hidden' => true
  ];
  if($item->boolProperty('hide-logo', false)) {
	  $header_data['title_svg'] = null;
	  $header_data['title'] = '';
  }
  
	if($p_header_classes = $item->property('header-classes')) {
		$p_header_classes = $p_header_classes->value;
		$p_header_classes = explode(",",str_replace(" ", "", $p_header_classes));
		foreach($p_header_classes as $class) {
			 array_push($header_classes, $class);
		}
	}

	$scripts = $item->propertyArray('js');
	$google_fonts = $item->propertyArray('google-font');
?>

@section('metadata')
	@parent
	@isset($google_fonts)
		@foreach($google_fonts as $font)
			<link href="https://fonts.googleapis.com/css?family={{ $font->value }}" rel="stylesheet">
		@endforeach
	@endisset
@stop

@section('scripts')
	<script language="javascript" type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.6.0/p5.min.js"></script>
	<script language="javascript" type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.6.0/addons/p5.dom.min.js"></script>
@isset($scripts)
@foreach($scripts as $script)
	<script language="javascript" type="text/javascript" src="{{ $script->value }}"></script>
@endforeach
@endisset
		<script>

		// prevent touches on mobile to scroll window
		var firstMove;

		window.addEventListener('touchstart', function(e) {
    	firstMove = true;
		});

		window.addEventListener('touchmove', function(e) {
    	if (firstMove) {
        e.preventDefault();
        firstMove = false;
	    }
		});

		// ensure full screen on resizing
		function windowResized() {
			resizeCanvas(windowWidth, windowHeight);
		}

		// p5 sketch from Folio Item
		{!! $item->text !!}
		</script>
@stop

@section('floating.menu')
  {!! view('folio::partial.c-floating-menu', ['buttons' => ['<i class="fa fa-pencil"></i>' => $item->editPath()]]) !!}
@stop

@section('content')

	<style>
		body {
			overflow-y:hidden;
			overflow-x:hidden;
		}
	</style>
    <div id="sketch"></div>

@stop

@section('footer')
@stop
