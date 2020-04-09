@extends('folio::template._base')

<?php
  // $cover_hidden = true;

  // header

  if(isset($item)) {
    
    // Cover image and header classes
    if($item->image) {
      $header_data = [
        'color' => 'white',
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
        $header_data['title'] = trans('base.the-getting-simple-podcast-strong');
    }

  }

  $cover_data = [
    'description' => trans('base.slogan')  
  ];

  // Date
  $date = new Date($item->published_at);
  $date = ucWords($date->format('F').' '.$date->format('j, Y'));

  // Defaults
  $font_size = "1em";

  // Template custom properties
  $hide_date = $item->boolProperty('hide-date', true);
  $hide_archive = $item->boolProperty('hide-archive', true);
  $hide_disqus = $item->boolProperty('hide-disqus');
  $visible_title = $item->stringProperty('visible-title');
  $footer_simple = $item->boolProperty('footer-simple');
  $footer_minimal = $item->boolProperty('footer-minimal', true);
  $header_minimal = $item->boolProperty('header-minimal', true);
  $hide_footer = $item->boolProperty('hide-footer', $footer_minimal);
  $wrap_size = $item->stringProperty('wrap-size', 700);
  $tight = $item->boolProperty('tight');
  $font_size = $item->stringProperty('font-size', $font_size);
  $font_size_palm = $item->stringProperty('font-size-palm', $font_size);
  $google_fonts = $item->propertyArray('google-font');

  // Episode item
  if ($episode_item_id = $item->stringProperty('item-id')) {
      $episode = Item::withTrashed()->find($episode_item_id);
  }

  // collateral
  $header_hidden = $header_minimal;
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
    {{-- Clipboard --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        console.log('Works');
        const podcastClip = new ClipboardJS('.js--podcast-clip');

        podcastClip.on('success', function(e) {
            console.log('test');
            $(e.trigger).addClass('o-clipable--highlight');
            setTimeout(function() {
                $(e.trigger).removeClass('o-clipable--highlight');
            }, 100);
        }); 
    </script>

    {{-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.10.0/styles/default.min.css">
         <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.10.0/highlight.min.js"></script>
         <script>hljs.initHighlightingOnLoad();</script> --}}
    <script>
        $(document).ready(function() {
        $("img").unveil(1000);
        });
    </script>    
@stop

@section('content')

<style>
	.u-resize--plain {
		font-size:{{ $font_size }};
	}

	@media(max-width: 530px) {
		.u-resize--plain {
			font-size: {{ $font_size_palm }};
		}
	}

    .c-item-v2--standard pre {
        max-width: 100%;
    }

    .c-item-v2 pre code {
        white-space: pre-wrap;
    }
</style>

<style>
    .o-clipable code:not(.u-not-copy) {
        transition: background-color 1s;
    }

    .o-clipable.o-clipable--highlight code:not(.u-not-copy) {
        transition: background-color 0s;
        background-color: #ffe6a2;
    }
    </style>

<div class="[ o-band
              @if(!$item->image) @if($header_minimal) u-pad-t-0x @else u-pad-t-2x @endif @else u-pad-t-5x @endif
              @if($hide_date==true) u-pad-b-3x portable--u-pad-b-0x @endif ]" style="font-size:0.625rem">
  @if($wrap_size) <div class="[ o-wrap o-wrap--bleed o-wrap--size-{{ $wrap_size }} ]"> @endif
  <div class="[  u-resize--plain   ]">

  <div class="[ c-item-v2 ] [ c-item-v2--indent c-item-v2--standard -c-item-v2--debug f-inter ]">
    
	@if($header_minimal)
    <div class="[ c-item-v2__section gf-ibm-plex-sans-regular ]">
		<a href="/">
		<p class="c-header-getting-simple__title u-text-align--center" style="font-size:1.4rem">
			Getting Simple
		</p>
		</a>
	</div>
	@endif

    <div class="[ c-item-v2__section f-inter -gf-ibm-plex-sans ]">

        {{-- <h1 class="[ c-item-v2__title ]" style="text-align:left;">
			{!! $visible_title ?? $item->title !!}</h1> --}}

            {!! $item->htmlText(['stripTags' => ['rss']]) !!}

            @isset($episode)
                <a href="{{ $episode->editPath() }}" style="box-shadow:none!important">{{ $episode->title }}</a>
                <br/>
                {!! view('partial.c-episode-post', ['episode' => $episode]) !!}
            @else
                {!! view('partial.c-episode-post') !!}
            @endisset

      {{-- Meta --}}

      @if($hide_date == false)
        <p class="c-item-v2__date">
          <span class="c-item-v2__meta">
            {{ $date }}
          </span>
        </p>
      @endif

      {{-- Tags --}}
      
        {{-- @if (count($item->tagNames()) > 0)
          <p class="c-item-v2__tags">{!! Folio::tagListWithItemAndClass($item, 'c-item-v2__tag u-case-upper') !!}</p>
        @endif --}}

	<div></div>

    </div>

	</div>

    @if($wrap_size) </div> @endif

    @if($footer_minimal)

    <?php
      $header_active_link_classes = 'c-header-getting-simple__link--active';
      $path = \Request::path();
      $isPodcast = substr($path, 0, 7) === 'podcast';
      $isNewsletter = substr($path, 0, 10) === 'newsletter';
      $isEssays = !$isPodcast && !$isNewsletter;
    ?>
    <div class="o-wrap o-wrap--size-600 -u-text-align--center f-inter u-pad-t-5x u-pad-b-1x u-no-underline" style="font-size:1rem;">
      <a href="/" class="[ c-header-getting-simple__link ] @if($isEssays) [ {{ $header_active_link_classes }} ] @endif ">
        {{ trans('base.essays') }}
      </a>
      <a href="/podcast" class="[ c-header-getting-simple__link ] @if($isPodcast) [ {{ $header_active_link_classes }} ] @endif ">
        {{ trans('folio::base.podcast') }}
      </a>
      <a href="/follow" class="[ c-header-getting-simple__link ] @if($isNewsletter) [ {{ $header_active_link_classes }} ] @endif ">
        {{ trans('folio::base.newsletter') }}
      </a>
    </div>

    @endif

    </div>

  </div>

@stop

@section('footer')

  @if(!$hide_footer)

  @if($footer_simple)
  
     <div class="[ o-band u-pad-b-4x @if($hide_archive == false) u-pad-t-3x @endif ]">

        <div class="[ o-wrap o-wrap--size-450 ]">
        
          {!! view('folio::partial.c-footer__subscribe') !!}
        
        </div>

    </div>
  
  @elseif($footer_minimal)

	&nbsp;

  @else

    {!! view('partial.c-footer-getting-simple') !!}
  
  @endif

  @endif

@stop