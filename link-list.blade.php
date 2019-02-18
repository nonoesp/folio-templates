@extends('folio::layout.plain')

<?php

	use Spatie\Regex\Regex;

	$site_title = $item->title.' · '.config('folio.title');
	if($item->boolProperty('simple-title')) {
		$site_title = $item->title;
	}
	$og_title = $site_title;
	// remove <style> tags
    $cleanHTML = Regex::replace('/<style[^>]*>.*?<\/style>/is', '', $item->htmlText())->result();	
	$og_description = Thinker::limitMarkdownText($cleanHTML, 159, ['sup']);
	$og_type = 'article';
	$og_url = $item->permalink();
	$og_image = $item->thumbnail();

	$header_hidden = true;
	$folio_css = '';
	$google_fonts = $item->propertyArray('google-font');
?>

@section('metadata')
	@parent
	@isset($google_fonts)
		@foreach($google_fonts as $font)
			<link href="https://fonts.googleapis.com/css?family={{ $font->value }}" rel="stylesheet">
		@endforeach
	@endisset

	<style>
		.o-band {
		padding:0.005em 1em;
		}
		h1, ul, p {
		font-family:"IBM Plex Sans";
		}
		a, a:hover {
			color:#125fde;
		}
		.c-collection-list__date,
		.c-collection-list__date a {
			color: rgba(0,0,0,0.5);
			text-decoration:none;
		}
		.c-collection-list__meta {

		}
		ul {
			list-style-type: none;
			padding-left:20px;
		}
		ul>li {
			margin-bottom:0.3em;
		}		
		@media (max-width:530px) {
			ul>li {
				margin-bottom:0.8em;
			}
		}
		ul>li:before {
			content: "–";
			/* en dash here */
			position: absolute;
			margin-left: -1.1em;
		}
		
		ul>li section {
			display:inline-block;
		}

		:target a {
			background-color:ffe6a2;
			padding:2px 0;
			color:black;
			text-decoration:none;
		}

		:target a:before {
			//content: '★';
		}
	</style>	
@stop

<head>
<title>{{ $site_title }}</title>
</head>

<?php
	$links = $item->propertyArray('link');
?>

@section('content')

<div class="[ o-band ]">

	<p>
		<a href="/">
			{{ config('folio.title-short') }}
		</a>
	</p>

	<h1>
		{{ $item->title }}
	</h1>

	@isset($item->text)
		{{-- Text --}}
		{!! $item->htmlText() !!}
	@endisset

		@isset($links)

			<ul>

			{{--
			TODO: Allow to do:
			label: Visit the article [here]
			value: http://nytimes/article/3423
			and link here to the URL
			--}}
			
			@foreach($links->reverse() as $link)
					<?php
						$label = $link->value;
						$text = null;
						$labelDate = null;
						if($link->label) {
							$label = $link->label;
							if(count(explode("|", $link->label)) == 2) {
								$label = explode("|", $link->label)[0];
								$text = explode("|", $link->label)[1];
							} else if(count(explode("|", $link->label)) > 2) {
								$label = explode("|", $link->label)[0];
								$text = explode("|", $link->label)[1];
								$labelDate = explode("|", $link->label)[2];
							}
							if($text=="") {
								$text = null;
							}
						}
						$source = null;
						if($link->value != "" && $link->value[0] != "/") {
							$source = explode("/", $link->value)[2];
						}
						if($labelDate) {
							$labelDate = str_replace(".","-",$labelDate);
							$labelDate = str_replace(" ","&nbsp;",ucWords(Date::parse($labelDate)->format('M Y')));
						}
					?>
					<li>
						<section id="{{ $link->value }}">
							<a href="{{ $link->value }}" target="_blank">
								{{ $label }}</a>
						</section>
						@isset($text)
							· {{ $text }}
						@endisset
						<span class="[ c-collection-list__meta ]"  style="font-size:0.8em;">
						@isset($source)
							({{ $source }})
						@endisset	
						{{-- @isset($labelDate)
							<span class="c-collection-list__date" style="font-size:0.9em;">
								({!! $labelDate !!})
							</span>
						@endisset --}}
						<span class="c-collection-list__date" style="font-size:0.9em;">
							<a href="#{{ $link->value }}">{{ Date::parse($link->created_at)->ago() }}</a>
						</span>
						</span>								
					</li>
				
			@endforeach

			</ul>

		@endisset

</div>

@stop

<style media="screen">
html,body{
    font-size:1em;
}
  .o-wrap {
    max-width:640px;
      //margin:auto;
  }
  img {width:100%;}
      .c-floating-menu {
      position:fixed;
      top:0;
      right:0;
      width:150px;
      height:80px;
      padding:1.5em 1.5em;
      z-index:300;
      cursor:pointer;
    }
    .c-float-menu__item {
		font-family:"IBM Plex Sans", sans-serif;
      float:right;
      font-size:80%;
      color:rgba(0,0,0,0.50);
      font-weight:600;
      text-transform:uppercase;
      background-color:white;
      padding:0 1em;
      border-radius:25px;
      text-decoration:none;
    }
</style>

@if($user = Auth::user())
  @if($user->is_admin)
    <div class="c-floating-menu">
      <a href="/admin/item/edit/{{ $item->id }}" class="c-float-menu__item">edit</a>
    </div>
    @endif
@endif