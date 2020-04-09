@extends('folio::template._base')

<?php
  // $cover_hidden = true;

  // imgix
  $imgix_active = config('folio.imgix');
  $baseImgixOptions = [
    'w' => 3840,
    'h' => null,
    'q' => 95,
    's' => 30,
    'fit' => 'max',   
    'fill' => 'solid', 
  ];
  $imgixOptions = $baseImgixOptions;        

  if(isset($item)) {

    // imgix
    if ($item->image && $imgix_active) {
      // Override default $imgixOptions with $itemOptions
      $imgixOptions = $item->imgixOptions($baseImgixOptions, []);        
      // And temporarily override $item->image
      $item->image = $item->image($imgixOptions);
    }    

    // Cover image and header classes
    if($item->image) {
      $header_data = [
        'color' => 'white'
      ];
    } else {
      $header_data = [];
      $header_classes = ['relative'];
    }

    // Header title
    $tags = [];
    foreach($item->tagged as $tag) {
      array_push($tags, $tag->tag_slug);
    }
    if($headerTitle = $item->stringProperty('header-title')) {
        $header_data['title'] = $headerTitle;
    } else if(in_array('podcast', $tags)) {
        // $header_data['title'] = trans('base.the-getting-simple-podcast-strong');
    }
  }

  $cover_data = [
    'description' => trans('base.slogan'),
    'class' => 'is-faded--30 is-fullscreen',
    'show_arrow' => true,
    'isLazy' => true,
  ];

  // Date
  $date = new Date($item->published_at);
  $date = ucWords($date->format('F').' '.$date->format('j, Y'));

  // Template custom properties
  $hide_date = $item->boolProperty('hide-date');
  $hide_archive = $item->boolProperty('hide-archive');
  $hide_disqus = $item->boolProperty('hide-disqus');
  $visible_title = $item->stringProperty('visible-title');
  $footer_simple = $item->boolProperty('footer-simple');
  $font_size = $item->stringProperty('font-size', '1em');
  $wrap_size = $item->stringProperty('wrap-size');
  $tight = $item->boolProperty('tight');
  $google_fonts = $item->propertyArray('google-font');

  $podcast = new Podcast($item);
  $spotifyEmbed = null;
  $spotify = $item->stringProperty('spotify');
  if ($spotify && count(explode("/episode/", $spotify)) > 1) {
    $spotifyEmbed = str_replace("/episode/", "/embed-podcast/episode/", $spotify);
  }

  // Apply font settings
	$sessionFontFamily = Session::get('font-family', 'Cardo');
	$sessionFontFamilyClass = Session::get('font-family-class', 'gf-cardo');
  $sessionFontFamilyWeights = Session::get('font-family-weights');
	if($sessionFontFamily && $sessionFontFamily != 'default') {
    if(!$google_fonts) {
      $google_fonts = [];
    }
		array_push($google_fonts, (object) [
      'value' => $sessionFontFamily,
      'weights' => $sessionFontFamilyWeights,
    ]);
	}
?>

@section('metadata')
	@parent
	@isset($google_fonts)
		@foreach($google_fonts as $font)
			<?php $weights = ''; ?>
			@if(isset($font->weights)) {
				<?php $weights = ':'.$font->weights; ?>
			@endif
			<link href="https://fonts.googleapis.com/css?family={{ $font->value }}{{ $weights }}" rel="stylesheet">
		@endforeach
	@endisset
@stop

@section('floating.menu')
  	{!! view('folio::partial.c-floating-menu', ['buttons' => [
		  '<i class="fa fa-share"></i>' => $item->sharePath(),
		  '<i class="fa fa-pencil"></i>' => $item->editPath()
		  ]]) !!}
@stop

@push('scripts')
  <script>
    $(document).ready(function() {
      $("img").unveil(1000);
    });
  </script>

  @if(!$hide_disqus)
  <script>
    var disqus_config = function () {
    this.page.url = '{{ $item->disqusPermalink() }}';
    this.page.identifier = '{{ $item->id }}';
    };
    (function() {
    var d = document, s = d.createElement('script');
    s.src = 'https://getting-simple.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
    })();
  </script>
  <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments.</a></noscript>
  @endif

@endpush


@section('content')

<div class="[ o-band
              @if(!$item->image) @if($tight) u-pad-t-3x @else u-pad-t-8x @endif @else u-pad-t-5x @endif
              @if($hide_date==true) u-pad-b-3x portable--u-pad-b-0x @endif ]" style="font-size:0.625rem">
  <div style="font-size:{{ $font_size }}">
  @if($wrap_size) <div class="[ o-wrap o-wrap--bleed o-wrap--size-{{ $wrap_size }} ]"> @endif
  <div class="[ c-item-v2 ] [ c-item-v2--indent c-item-v2--standard -c-item-v2--debug ]">
      
    @if(!$item->image and $visible_title != '-')
      <div class="[ c-item-v2__header ]">
        <h1 class="[ c-item-v2__title ]">{!! $visible_title ?? $item->title !!}</h1>
      </div>
    @endif

    <div class="[ c-item-v2__section c-item-v2__body ]">

    {{-- $podcast->player() --}}

    {{ $podcast->html5Player() }}

    {{-- $podcast->stitcherPlayer() --}}

    <div class="[ {{ $sessionFontFamilyClass }} ]">
      {!! $item->htmlText([
        'stripTags' => ['rss', 'podcast'],
        'parseExternalLinks' => true,
      ]) !!}
    </div>

      {{-- Meta --}}

      @if($hide_date == false)
        <p class="c-item-v2__date">
          <span class="c-item-v2__meta">
            {{ $date }}
          </span>
        </p>
      @endif

      {{-- Tags --}}
      
        @if (count($item->tagNames()) > 0)
          <p class="c-item-v2__tags">{!! Folio::tagListWithItemAndClass($item, 'c-item-v2__tag u-case-upper') !!}</p>
        @endif

    </div>

    @if($wrap_size) </div> @endif

    </div>
    </div>

    {{--  Disqus  --}}

    @if(!$hide_disqus)
    <div class="[  o-wrap  o-wrap--size-600  ]  [ u-pad-b-8x ]">
      <div class="[  c-disqus-before-wrapper  ]">
        <div class="[  c-disqus-before  ]"></div>
      </div>
    </div>    

    <div class="[  o-wrap  o-wrap--size-600  ]  [ -u-pad-b-8x ]">
      <div id="disqus_thread"></div>
    </div>
    
    <div class="[  o-wrap  o-wrap--size-600  ]  [ u-pad-b-8x ]">
      <div class="[  c-disqus-after-wrapper  ]">
        <div class="[  c-disqus-after  ]"></div>
      </div>
    </div>    
    @endif

  </div>

    {{--  Archive  --}}

    @if($hide_archive == false)

    <div class="[ o-band ] [ u-border-bottom u-pad-b-0x ]">

        <div class="[ o-wrap o-wrap--size-900 ]">

            {!! view('partial.c-item-archive',
            ['collection' => Item::published()->blog()->orderBy('published_at', 'DESC')->get() ]) !!}

        </div>

    </div>

    @endif

@stop

@section('footer')

  @if($footer_simple)
  
     <div class="[ o-band u-pad-b-4x @if($hide_archive == false) u-pad-t-3x @endif ]">

        <div class="[ o-wrap portable--o-wrap--size-300 o-wrap--size-350 ]">
        
          {!! view('folio::partial.c-footer__subscribe') !!}
        
        </div>

    </div>
  
  @else

    {!! view('partial.c-footer-getting-simple') !!}
  
  @endif

@stop