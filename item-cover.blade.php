@extends('folio::template._base')

<?php
  // $cover_hidden = true;
  $site_title = trans('folio::base.about-me').' | '.config('folio.title');
  $og_title = $site_title;
  // header
  $header_view = 'folio::partial.c-header-simple';
  //$header_classes = ['borderless'];
  $header_data = [
    // 'image' => '/img/profile.gif',
    // 'is_media_hidden' => null,
    'image' => null,
    'color' => 'white'
  ];

  $date = new Date($item->published_at);
  $date = ucWords($date->format('F').' '.$date->format('j, Y'));

  $font_size = "1em";
  if($p = $item->property('font-size')) {
    $font_size = $p->value;
  }
?>

@section('scripts')
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.10.0/styles/default.min.css">
  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.10.0/highlight.min.js"></script>
  <script>hljs.initHighlightingOnLoad();</script>
@stop

@section('floating.menu')
  {!! view('folio::partial.c-floating-menu', ['item' => $item]) !!}
@stop

@section('content')

  <div style="font-size:0.625em">
    <div style="font-size:{{ $font_size }}">

<div class="[ c-item-v2 ] [ c-item-v2--indent c-item-v2--standard -c-item-v2--debug ]">

  <div class="c-item-v2__section">

    @if(!$item->image)
      <h1 class="[ u-text-align--center ]">{{ $item->title }}</h1>
    @else
      <hr/>
    @endif

    {!! str_replace(["<p><img","/></p>"],["<img","/>"], $item->htmlText()) !!}

    {{-- Meta --}}

      <p>
        <span class="c-item-v2__meta">
          {{ $date }}
        </span>
      </p>

    {{-- Tags --}}
      @if (count($item->tagNames()) > 0)
        <p class="c-item-v2__tags">{!! Folio::tagListWithItemAndClass($item, 'c-item-v2__tag u-case-upper') !!}</p>
      @endif

      </div>
    </div>  
  </div>
</div>
@stop
