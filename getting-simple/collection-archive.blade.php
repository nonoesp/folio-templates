@extends('folio::template._base')

<?php
    $cover_hidden = true;  
    $og_image = config('folio.image_src');
    $hide_subscribe = isset($hide_subscribe) ? $hide_subsribe : false;
    $subscribe_location = '2018-06';

    // c-newsletter
    $heroTitle = trans('base.slogan');
    $heroText =  trans('base.newsletter.text-alternative');
    $heroHideSubscribe = true;
    $heroMaxWidth = '37rem';

    if(isset($item)) {
        $heroTitle = $item->stringProperty('hero-title', $heroTitle);
        $heroText = $item->stringProperty('hero-text', $heroText);
        $heroHideSubscribe = $item->boolProperty('hero-hide-subscribe', $heroHideSubscribe);
        $heroMaxWidth = $item->stringProperty('hero-max-width', $heroMaxWidth);
        if ($item->text) {
            $heroText = $item->text;
        }        
    }

    if(isset($item)) {

        // Site title
        $site_title = $item->title.' · '.config('folio.title');
        if($item->boolProperty('clean-title')) {
            $site_title = $item->title;
        }
        $og_title = $site_title;  

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

        // Collection
        $collection = $item->collection();
        $hide_subscribe = $item->boolProperty('hide-subscribe', $hide_subscribe);
        $subscribe_location = $item->stringProperty('subscribe-location', $subscribe_location);
    }
?>

@section('content')

    <div class="[ o-band ] [ u-border-bottom u-pad-b-0x u-pad-t-5x palm--u-pad-t-1x ]">

    <div class="[ o-wrap ]  [ u-pad-b-2x ]" style="max-width: {{ $heroMaxWidth }}">
        
        {!! view('partial.c-newsletter', [
            'headline' => $heroTitle,
            'text' => $heroText,
            'hide_subscribe' => $heroHideSubscribe,
        ]) !!}

    </div>

    <br/>

    {{-- POSTS ARCHIVE --}}

        <div class="[ o-wrap ]">

            @if($subscribe_location == null)
            
                {!! view('partial.c-item-archive', ['collection' => $collection]) !!}
            
            @else

                @php
                    $collection_before_subscribe = $collection->where('published_at', '>=', $subscribe_location);
                    $collection_after_subscribe = $collection->where('published_at', '<', $subscribe_location);
                @endphp

                @if(count($collection_before_subscribe))
                {!! view('partial.c-item-archive',
                        ['collection' => $collection_before_subscribe]) !!}
                @endif

                <p class="[ u-border-bottom ]"></p>

                <div class="grid">
                    <div class="grid__item one-quarter u-hidden-palm"></div>
                    <div class="grid__item two-quarters palm--one-whole">
                        <br />
                        {!! view('folio::partial.c-footer__subscribe', [
                            'source' => 'gs-articles',
                            'medium' => 'web-archive-'.$subscribe_location,
                            'campaign' => 'sign-up',
                            'hide_terms' => true,
                            ]) !!}
                        <br />
                    </div>
                </div>

                @if(count($collection_after_subscribe))
                {!! view('partial.c-item-archive',
                        ['collection' => $collection_after_subscribe]) !!}
                @endif

            @endisset

        </div>

    </div>
        
@stop

@section('footer')

    {!! view('partial.c-footer-getting-simple', [
        'source' => 'gs-archive',
        'medium' => 'web-footer',
        'campaign' => 'sign-up',
    ]) !!}

@stop

@isset($item)
        @section('floating.menu')
          {!! view('folio::partial.c-floating-menu', ['buttons' => ['<i class="fa fa-pencil"></i>' => $item->editPath()]]) !!}
        @stop
@endisset