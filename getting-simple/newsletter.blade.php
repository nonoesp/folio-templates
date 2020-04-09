@extends('folio::template._base')

<?php
    $cover_hidden = true;  
    $og_image = 'https://gettingsimple.com/img/u/image_src-newsletter.png';
    $og_description = 'Creatives from eclectic areas on living a productive, creative, simple life. Habits. Techniques. Ideas.';

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

    // Item
    if ($item) {
        // Cover image and header classes
        if($item->image) {
            $header_data = [
                'color' => 'white'
            ];
            } else {
            $header_data = [];
            $header_classes = ['relative'];
        }
    }
?>

@section('content')

    <div class="[ o-band ] [ u-pad-b-0x u-pad-t-5x palm--u-pad-t-1x ]">

        <div class="[ o-wrap ] [ u-pad-b-2x ]" style="max-width: {{ $heroMaxWidth }}">
        
            {!! view('partial.c-newsletter', [
                'hide_arrow' => true,
                'headline' => $heroTitle,
                'text' => $heroText,
                'hide_subscribe' => $heroHideSubscribe,
            ]) !!}

        </div>

    </div>

@stop

@section('footer')
    <div class="f-inter u-font-size--a u-no-underline u-pad-t-5x u-text-align--center">
        <p class="c-footer-getting-simple__detail" style="max-width:100%">
            © 2013–{!! Date::now()->format('Y'); !!}
            <a href="http://nono.ma/about" target="_blank">Nono Martínez Alonso</a>
            ·
            <a href="/contact" target="_blank">{{ trans('base.contact') }}</a>
        </p>
    </div>
@stop

@isset($item)
        @section('floating.menu')
          {!! view('folio::partial.c-floating-menu', ['buttons' => ['<i class="fa fa-pencil"></i>' => $item->editPath()]]) !!}
        @stop
@endisset