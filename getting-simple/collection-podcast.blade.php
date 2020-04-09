@extends('folio::template._base')

<?php
    $cover_hidden = true;  
    $og_image = config('folio.image_src');

    if(isset($item)) {

        // Image
        $og_image = $item->thumbnail();

        // Site title
        $site_title = $item->title.' · '.config('folio.title');
        if($item->boolProperty('clean-title')) {
            $site_title = $item->title;
        }

        $og_title = $site_title;
        if($item->stringProperty('site-title')) {
            $site_title = $item->stringProperty('site-title');
            $og_title = $site_title;
        }

        // Header title
        $tags = [];
        foreach($item->tagged as $tagName) {
            array_push($tags, $tagName->tag_slug);
        }

        if($headerTitle = $item->stringProperty('header-title')) {
            $header_data['title'] = $headerTitle;
        } else if(in_array('podcast', $tags)) {
            $header_data['title'] = trans('base.the-getting-simple-podcast-strong');
        }

        // Collection
        if($collectionTags = $item->property('collection')) {

            $sortByIsFeatured = function($collection_item) {
                return $collection_item->intProperty('is-featured', $collection_item->published_at);
            };

            $collection = Item::withAnyTag(explode(",", $collectionTags->value))->
                                published()->
                                get()->sortByDESC('published_at')->sortByDESC($sortByIsFeatured);
                                // get()->sortByDESC('published_at')->sortByDESC($sortByIsFeatured)->sortByDESC($sortByIsFeatured);
        }

        // imgix
        $imgix_w = $item->intProperty('imgix-w', 600);
        $imgix_q = $item->intProperty('imgix-q', 95);
        $imgix_s = $item->intProperty('imgix-s', 0);
        $imgix_active = $item->boolProperty('imgix', config('folio.imgix'));
    }

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
    }

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

@section('content')

    <div class="[ o-band ] [ u-pad-b-0x u-pad-t-5x ]">

        <div class="[ o-wrap ]  [ u-pad-b-2x ]" style="max-width: {{ $heroMaxWidth }}">
        
            {!! view('partial.c-newsletter', [
                'headline' => $heroTitle,
                'text' => $heroText, // 'Getting Simple is a podcast about how you can live a more meaningful, creative, and simple life.',
                'hide_subscribe' => $heroHideSubscribe,
                'show_player' => true,
                'show_networks' => true,
            ]) !!}

        </div>

        <br/>

        <div class="[ o-wrap -o-wrap--bleed@palm ]  [  u-pad-b-6x  ]">

			<div class="[  grid  grid--gutter-podcast  grid--gutter-h-podcast  ]">
			
				@foreach($collection as $i)

                    <?php
                    $podcast = new Podcast($i);

                    // Bigger width for featured episode
                    $imgixOptions = ['w' => $imgix_w, 'q' => $imgix_q, 'sharp' => $imgix_s];
                    if ($loop->first) {
                        $imgixOptions['w'] = 1600;
                    }

                    $image = $podcast->cardImage($imgixOptions);
                    ?>

					<a href="{{ $podcast->path() }}">

						<div class="[  grid__item  @if($loop->first) one-whole @else one-half @endif palm--one-whole  ]">

                            {!! view('partial.o-card-podcast', [
                                'podcast' => $podcast,
                                'image' => $image,
                                'feature' => $loop->first,
                            ]) !!}	

						</div>	

					</a>		

				@endforeach

			</div>

        </div>

    </div>
        
@stop

@section('footer')

    {!! view('partial.c-footer-getting-simple', [
        'source' => 'gs-podcasts',
        'medium' => 'web-footer',
        'campaign' => 'sign-up',
    ]) !!}

@stop

@isset($item)
        @section('floating.menu')
          {!! view('folio::partial.c-floating-menu', ['buttons' => ['<i class="fa fa-pencil"></i>' => $item->editPath()]]) !!}
        @stop
@endisset