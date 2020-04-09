@extends('folio::template._base')

<?php
  // $cover_hidden = true;

  // Date
  $date = new Date($item->published_at);
  $date = ucWords($date->format('F').' '.$date->format('j, Y'));

  // Defaults
  $font_size = "1em";

  // Template custom properties
  $hide_date = $item->boolProperty('hide-date');
  $hide_title = $item->boolProperty('hide-title');
  $hide_archive = $item->boolProperty('hide-archive');
  $hide_disqus = $item->boolProperty('hide-disqus');
  $visible_title = $item->stringProperty('visible-title');
  $footer_simple = $item->boolProperty('footer-simple');
  $footer_minimal = $item->boolProperty('footer-minimal');
  $header_minimal = $item->boolProperty('header-minimal');
  $hide_footer = $item->boolProperty('hide-footer', $footer_minimal);
  $wrap_size = $item->stringProperty('wrap-size', '600');
  $tight = $item->boolProperty('tight');
  $font_size = $item->stringProperty('font-size', $font_size);
  $font_size_palm = $item->stringProperty('font-size-palm', $font_size);
  $google_fonts = $item->propertyArray('google-font');
  $indent = $item->boolProperty('indent', true);
  $audio = $item->stringProperty('audio', $item->stringProperty('podcast-file'));
  $video_youtube = $item->stringProperty('video-youtube');
  $mode = $item->stringProperty('mode'); // Theme mode (e.g., t-folio-dark)
  $subscribe_button_text = $item->stringProperty('subscribe-button-text');

  $itunesEpisodeLink = $item->stringProperty('itunes');
  $spotifyEpisodeLink = $item->stringProperty('spotify');
  $youtubeEpisodeLink = $item->stringProperty('youtube');
  $overcastEpisodeLink = $item->stringProperty('overcast');
  $stitcherEpisodeLink = $item->stringProperty('stitcher');

  $spotifyEmbed = null;
  $episodeSummary = null;

  // Episode item
  if ($episode_item_id = $item->stringProperty('item-id')) {
    $episode = Item::withTrashed()->find($episode_item_id);
    if ($episode) {
      $podcast = new Podcast($episode);

      $itunesEpisodeLink = $episode->stringProperty('itunes', $itunesEpisodeLink);
      $spotifyEpisodeLink = $episode->stringProperty('spotify', $spotifyEpisodeLink);
      $youtubeEpisodeLink = $episode->stringProperty('youtube', $youtubeEpisodeLink);
      $overcastEpisodeLink = $episode->stringProperty('overcast', $overcastEpisodeLink);
      $stitcherEpisodeLink = $episode->stringProperty('stitcher', $stitcherEpisodeLink);

      $audio = $podcast->file();
      $episodeSummary = $podcast->plainSummary();
      $episodePost = $episode->URL();

    }
  }

  if($item) {
    $episodeSummary = $item->stringProperty('podcast-summary', $episodeSummary);
  }

  if (isset($mode)) {
    $html_theme_class = 't-folio-'.$mode;
  }

  // header

  if(isset($item)) {
    
    // Cover image and header classes
    if($item->image) {
      $header_data = [
        'color' => 'white',
      ];
    } else if (isset($video_youtube)) {
      $header_data = [
        'color' => 'white'
      ];
      $header_classes = ['absolute', 'white'];
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
</style>

@isset($video_youtube)

  @if($header_minimal)

  <style>
  .c-header-getting-simple-minimal {
    position: absolute;
    display:block;
    z-index: 1;
    width: 100%;
  }

  .c-header-getting-simple-minimal a {
    text-decoration: none;
  }

  .c-header-getting-simple-minimal__title {
    color: white;
    font-size: 1.4rem;
    text-align: center;
    font-weight: 600;
  }
  </style>

      <div class="[ c-header-getting-simple-minimal u-pad-t-3x ]">
      <a href="/">
      <p class="c-header-getting-simple-minimal__title">
        Getting Simple
      </p>
      </a>
      </div>
    @endif

    {!! view('partial.c-cover-v2', [
      'background' => '#222222',
      'class' => 'is-filled u-background-black',
      'youtube' => $video_youtube,
    ]) !!}
@endisset

<div class="[ o-band o-band--dark
              @if(!$item->image) @if($header_minimal) u-pad-t-0x @else u-pad-t-2x @endif @else u-pad-t-5x @endif
              @if($hide_date==true) u-pad-b-3x portable--u-pad-b-0x @endif ]" style="font-size:0.625rem">
  @if($wrap_size) <div class="[ o-wrap o-wrap--bleed o-wrap--size-{{ $wrap_size }} ]"> @endif
  <div class="[  u-resize--plain   ]">

  <div class="[ c-item-v2 c-item-v2--{{$mode}} ] [
  @if($indent) c-item-v2--indent @endif
  @isset($episode) c-item-v2--transcript @endisset
  @if($wrap_size) c-item-v2--free-width @endif c-item-v2--standard -c-item-v2--debug f-inter ]">
    
	@if($header_minimal && !isset($video_youtube))
    <div class="[ c-item-v2__section gf-ibm-plex-sans-regular ]">
		<a href="/">
		<p class="c-header-getting-simple__title u-text-align--center" style="font-size:1.4rem">
			Getting Simple
		</p>
		</a>
	</div>
	@endif

    <div class="[ c-item-v2__section f-inter -gf-ibm-plex-sans ]">

      @if($hide_title == false)
      <h1 class="[ c-item-v2__title c-item-v2__title--fixed-size ]" style="text-align:left;">
			  {!! $visible_title ?? $item->title !!}
      </h1>
      @endif

      {{-- PODCAST PLAYER --}}

      @isset($podcast)
        {!! $podcast->html5Player([
          'text' => 'Listen to this episode',
          'align' => 'left',
        ]) !!}
      @endisset

      {{-- PODCAST NETWORKS --}}

      {!! view('partial.c-podcast-networks', [
        'itunes' => $itunesEpisodeLink,
        'spotify' => $spotifyEpisodeLink,
        'youtube' => $youtubeEpisodeLink,
        'overcast' => $overcastEpisodeLink,
      ]) !!}      

      {{-- EPISODE SUMMARY --}}

      @isset($episodeSummary)
      <p>
          Please enjoy this transcript. {{ $episodeSummary }}
          Transcripts may contain typos.
          You can find the episode notes <a href="{{ $episodePost }}">here</a>.
      </p>
      <hr>
      @endisset

      {{-- TEXT --}}

      {!! $item->htmlText(['stripTags' => ['rss']]) !!}

      {{-- Meta --}}

      @if($hide_date == false)
        <p class="c-item-v2__date">
          <span class="c-item-v2__meta" style="font-size: 0.8rem">
            {{ $date }}
          </span>
        </p>
      @endif

      {{-- Tags --}}
      
        {{-- @if (count($item->tagNames()) > 0)
          <p class="c-item-v2__tags">{!! Folio::tagListWithItemAndClass($item, 'c-item-v2__tag u-case-upper') !!}</p>
        @endif --}}

      @isset($podcast)
        <hr>

        <div class="u-font-size--a" style="color:#666">
        <p>
        <strong>LEGAL NOTE</strong>
        <br><br>
        Nono Martínez Alonso owns the copyright in and to all content in and transcripts of The Getting Simple Podcast.
        <br><br>
        <strong>You can share</strong> the below transcript (up to 500 words) in media articles, on your personal website, in a non-commercial article or blog post, and/or on a personal social media account for non-commercial purposes, provided that you include attribution to "The Getting Simple Podcast" and link back to the gettingsimple.com/podcast URL.
        <br><br>
        <strong>You can't copy</strong> any portion of the podcast content for any commercial purpose or use, including without limitation inclusion in any books, e-books, book summaries or synopses, or on a commercial website or social media site that offers or promotes your or another's products or services.
        </p>
        </div>
      @endisset

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
      <a href="/podcast" class="[ c-header-getting-simple__link ] @if($isPodcast) [ {{ $header_active_link_classes }} ] @endif ">
        {{ trans('folio::base.podcast') }}
      </a>
      <a href="/" class="[ c-header-getting-simple__link ] @if($isEssays) [ {{ $header_active_link_classes }} ] @endif ">
        {{ trans('base.essays') }}
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
  
     <div class="[ o-band o-band--dark u-pad-b-4x @if($hide_archive == false) u-pad-t-3x @endif ]">

        <div class="[ o-wrap o-wrap--size-450 ]">
        
          <?php
            $footer_data = [];
            if(isset($subscribe_button_text)) {
              $footer_data = ['subscribe_button_text' => $subscribe_button_text];
            }
          ?>

          {!! view('folio::partial.c-footer__subscribe', $footer_data) !!}
        
        </div>

    </div>
  
  @elseif($footer_minimal)

	&nbsp;

  @else

    {!! view('partial.c-footer-getting-simple') !!}
  
  @endif

  @endif

@stop