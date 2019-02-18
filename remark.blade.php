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
	$remark_ratio = $item->stringProperty('remark-ratio', '8:5'); // '3:4' '16:9'
	$remark_count_format = $item->stringProperty('remark-count-format', "''")
?>

@section('scripts')
	<script language="javascript" type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/remark/0.14.0/remark.min.js"></script>
@isset($scripts)
@foreach($scripts as $script)
	<script language="javascript" type="text/javascript" src="{{ $script->value }}"></script>
@endforeach
@endisset
	<script>
		var slideshow = remark.create({
			ratio: '{{ $remark_ratio }}',
			slideNumberFormat: function (current, total) {
    			return {!! $remark_count_format !!};
  			}
		});
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
		div {
			background-size: cover;
			background-position:center;
		}
	</style>

<textarea id="source">
{!! $item->text !!}
</textarea>

@stop

@section('footer')
@stop