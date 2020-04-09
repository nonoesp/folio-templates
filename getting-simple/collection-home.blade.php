@extends('folio::template._base')

<?php
    $cover_hidden = true;  
    $og_image = config('folio.image_src');
    $hide_subscribe = isset($hide_subscribe) ? $hide_subsribe : false;
    $subscribe_location = '2018-06';

    // imgix
    $imgix_w = 900;
    $imgix_q = 95;
    $imgix_s = 30;
    $imgix_active = config('folio.imgix');

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
        $subscribe_location = $item->stringProperty('subscribe-location');

        // imgix
        $imgix_w = $item->intProperty('imgix-w', $imgix_w);
        $imgix_q = $item->intProperty('imgix-q', $imgix_q);
        $imgix_s = $item->intProperty('imgix-s', $imgix_s);
        $imgix_active = $item->boolProperty('imgix', $imgix_active);
    } else {
        $featuredPosts = Item::makeCollection([
            'tags' => 'blog-featured',
        ]);
    }
?>

@section('content')

    <div class="[ o-band ] [ u-border-bottom u-pad-b-0x u-pad-t-5x palm--u-pad-t-1x ]">

    <div class="[ o-wrap ]  [ u-pad-b-2x ]" style="max-width: 37rem">
        
        {!! view('partial.c-newsletter', [
            // 'headline' => 'Writing.',
            // 'text' => trans('base.newsletter.text-alternative'),
            'text' => trans('base.newsletter.podcast-description'),
            'hide_subscribe' => true,
            'show_player' => true,
            'show_networks' => true,
        ]) !!}

    </div>

    <br/>

    {{-- LATEST EPISODES --}}

    <div class="[ o-wrap -o-wrap--bleed@palm ]  [  u-pad-b-6x  ]">       

			<div class="[  grid  grid--gutter-podcast  grid--gutter-h-podcast  ]">
            
                <div class="[ -u-border grid__item one-half u-bold u-no-underline  ]">

                    <div class="[ u-font-size--110 -palm--u-font-size--120 -tinypalm--u-font-size--120 ]"
                        style="font-weight:600;">

                        {{ trans('base.latest-episodes') }}

                    </div>

                </div>

                <div class="[ -u-border u-font-size--110 grid__item one-half u-text-align--right u-bold u-opacity--half u-no-underline  ]">

                        <a href="/podcast">{{ trans('base.view-all') }}&nbsp;&nbsp;→</a>

                </div>     

                <div class="[ grid__item one-whole ]" style="padding-top:20px !important">
                    <hr style="height: 1px; background-color:rgb(223, 223, 223); border: 0;"/>
                </div>                

				@foreach(Podcast::take(2) as $podcast)

                    <?php
                    $imgixOptions = ['w' => $imgix_w, 'q' => $imgix_q, 'sharp' => $imgix_s];
                    $image = $podcast->cardImage($imgixOptions);
                    ?>

					<a href="{{ $podcast->path() }}">

                        <div class="[ -u-border grid__item
                        one-half
                        {{-- @if($loop->first) one-whole @else one-half @endif --}}
                        palm--one-whole  ]">

                            {!! view('partial.o-card-podcast', [
                                'podcast' => $podcast,
                                'image' => $image,
                                'feature' => false,
                            ]) !!}	

						</div>	

					</a>		

                @endforeach

			</div>

    </div>

    {{-- SELECTED WRITING --}}

    <div class="[ o-wrap -o-wrap--bleed@palm ]  [  u-pad-b-6x  ]">       

			<div class="[  grid  grid--gutter-podcast  grid--gutter-h-podcast  ]">
            
                <div class="[ -u-border grid__item one-half u-bold u-no-underline  ]">

                    <div class="[ u-font-size--110 -palm--u-font-size--150 -tinypalm--u-font-size--205 ]"
                        style="font-weight:600;">

                        {{ trans('base.featured-posts') }}

                    </div>

                </div>

                <div class="[ -u-border u-font-size--110 grid__item one-half u-text-align--right u-bold u-opacity--half u-no-underline  ]">

                        <a href="/writing">{{ trans('base.view-all') }}&nbsp;&nbsp;→</a>

                </div>     

                <div class="[ grid__item one-whole ]" style="padding-top:20px !important">
                    <hr style="height: 1px; background-color:rgb(223, 223, 223); border: 0;"/>
                </div>

				@foreach($featuredPosts as $i)

                    <?php
                    $imgixOptions = ['w' => $imgix_w, 'q' => $imgix_q, 'sharp' => $imgix_s];
                    $image = $i->cardImage($imgixOptions);
                    ?>

					<a href="{{ $i->path() }}">

                        <div class="[ grid__item
                        one-half
                        {{-- @if($loop->first) one-whole @else one-half @endif --}}
                        palm--one-whole  ]">

                            {!! view('partial.o-card-post', [
                                'item' => $i,
                                'image' => $image,
                                'feature' => false,
                                'hide_date' => true,
                            ]) !!}	

						</div>	

					</a>		

                @endforeach

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