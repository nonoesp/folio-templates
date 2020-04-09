@extends('folio::template._base')

<?php
  $cover_hidden = true;
//   $header_view = 'folio::partial.c-header-simple';
//   $header_classes = ['borderless', 'tight'];
  $header_data = [
    'image' => null,
  ];
  $footer_data = [];

  // custom properties
  $font_size = "1em";
  $hide_date = isset($hide_date) ? $hide_date : false;
	$hide_tags = isset($hide_tags) ? $hide_tags : false;
  $is_single = isset($is_single) ? $is_single : false;

  if(isset($item)) {
    
    $hide_date = $item->boolProperty('hide-date');
    $hide_tags = $item->boolProperty('hide-tags');
    $footer_data['hide_subscribe'] = $item->boolProperty('hide-subscribe');
    $footer_data['hide_credits'] = $item->boolProperty('hide-credits');
    $font_size = $item->stringProperty('font-size', $font_size);
    $scripts = $item->propertyArray('js');

    // Automation Sketches
    $emoji = $item->stringProperty('emoji');
    $shareText = $item->stringProperty('share-text');
    app()->setLocale('es');
    $emoji_es = $item->stringProperty('emoji');
    $shareText_es = $item->stringProperty('share-text');
    $shareText = $item->stringProperty('podcast-newsletter', null);
    app()->setLocale('en');
    $hashtags = $item->stringProperty('hashtags');
    $textSummary = \Thinker::limitMarkdownText($item->htmlText(['stripTags' => ['rss', 'norss', 'nopodcast']]), 159, ['sup']);
    $summary = str_replace('  ', ' ', $item->stringProperty('podcast-summary', $textSummary));
    $title = $item->translate('title', 'en');
    $title_es = $item->translate('title', 'es');
    $thumbnail = $item->thumbnail();
    $image = $item->image;
    $URL = $item->URL();
    $cleanURL = explode('://', $URL)[1];
    if ($thumbnail == config('folio.image-src')) {
        $thumbnail = null;
    }
    $hashtagText = null;
    if ($hashtags) {
        $hashtagText = '<span class="o-social-copy__hashtag">'.$hashtags.'</span>';
    }

    $itunes = $item->stringProperty('itunes');
    $spotify = $item->stringProperty('spotify');
    $youtube = $item->stringProperty('youtube');
    $overcast = $item->stringProperty('overcast');
    $google = $item->stringProperty('google');

    $networks = [
        'itunes' => $itunes,
        'spotify' => $spotify,
        'youtube' => $youtube,
        'overcast' => $overcast,
        'google' => $google,
    ];
    $networkPreviewLength = 32;
        
    // Automation podcast episode
    $episode_number = $item->stringProperty('podcast-episode');
    $twitter_title = $item->stringProperty('twitter-title', $title);    

    $listenText = 'Listen at '.$cleanURL.', or visit the profile link of @gettingsimple.';

    // Image resizing
    // $imgWidth = 650;
    // $imgName = $item->id.'-'.$imgWidth.'.jpg';
    // $img = \Image::make($thumbnail);
    // $img->widen($imgWidth);
    // $img->save('tmp/'.$imgName);
  }
?>

<?php
            // Square image link
            $imageName = explode('/', $image);
            $imageName = $imageName[count($imageName)-1];
            $squareImageLink = '/img/square/'.$imageName;

            // Facebook link
            $facebookLink = 'https://www.facebook.com/sharer/sharer.php?u='.$URL;
            
            // LinkedIn link
            $linkedInLink = 'https://www.linkedin.com/sharing/share-offsite/?url='.$URL;
            
            // Twitter link
            $twitterLink = 'https://twitter.com/intent/tweet?text='
            .'New episode is out!%0a%0a🎙'.urlencode($twitter_title);
            if (isset($episode_number)) {
                $twitterLink .= ' (#'.$episode_number.')';
            }
            if (isset($hashtags)) {
                $twitterLink .= '%0a%0a'.$hashtags;
            }
            $twitterLink .= '%0a%0a'.$URL;
            $twitterLink = str_replace([' ', '#'], [urlencode(' '), urlencode('#')], $twitterLink);

            // Twitter clip
            $twitterClip = 'New episode is out!

🎙 '.$twitter_title;
            if (isset($hashtags)) {
                $twitterClip .= '

'.$hashtags;
            }
            $twitterClip .= '

'.$URL;

            function facebookClip($title, $title_es, $emoji, $emoji_es, $shareText, $summary, $hashtags, $URL, $networks, $instagram = false) {
                $br = '
';
                $cleanURL = explode('://', $URL)[1];
        
                // Facebook clip
        $facebookClip = '🎙 '.$title;

        if (isset($shareText)) {
            $facebookClip .= '

'.str_replace("<br>", "
", $shareText);
        } else if (isset($summary)) {
            $facebookClip .= $br.$br.$summary;

        }

        if(!$instagram) {
            $facebookClip .= $br.$br.$URL;
        }        
        
        if(array_key_exists('itunes', $networks)) {
            $itunes = $networks['itunes'];
        }
        if(array_key_exists('spotify', $networks)) {
            $spotify = $networks['spotify'];
        }
        if(array_key_exists('youtube', $networks)) {
            $youtube = $networks['youtube'];
        }
        if(array_key_exists('overcast', $networks)) {
            $overcast = $networks['overcast'];
        }
        if(array_key_exists('google', $networks)) {
            $google = $networks['google'];
        }

        // Space before networks
        if(
            isset($itunes) ||
            isset($spotify) ||
            isset($youtube) ||
            isset($google) ||
            isset($overcast)
        ) {
            $facebookClip .= '
';
        }        

        if(!$instagram) {
            if(isset($itunes)) {
                $facebookClip .= $br.'- Apple Podcasts '.$itunes;
            }
            if(isset($spotify)) {
                $facebookClip .= $br.'- Spotify '.$spotify;
            }
            if(isset($youtube)) {
                $facebookClip .= $br.'- Youtube '.$youtube;
            }
            if(isset($overcast)) {
                $facebookClip .= $br.'- Overcast '.$overcast;
            }
            if(isset($google)) {
                $facebookClip .= $br.'- Google Podcasts '.$google;
            }     

            // Space after networks
//             if(
//                 isset($itunes) ||
//                 isset($spotify) ||
//                 isset($youtube) ||
//                 isset($google) ||
//                 isset($overcast)
//             ) {
//                 $facebookClip .= '
// ';
//             }
        }
        
        if($instagram) {
            $facebookClip .= '

Listen at '.$cleanURL.', or visit the profile link of @gettingsimple.

Enjoy!';
        }

        if (isset($hashtags)) {
            if($instagram) {
            $facebookClip .= '

'.$hashtags;
            } else {

            $facebookClip .= '

'.$hashtags;
            }
        }

// if(!$instagram) {

//         $facebookClip .= '

// '.$URL;
// }

return $facebookClip;
            }
            $facebookClip = facebookClip($title, $title_es, $emoji, $emoji_es, $shareText, $summary, $hashtags, $URL, $networks);
            $instagramClip = facebookClip($title, $title_es, $emoji, $emoji_es, $shareText, $summary, $hashtags, $URL, [], true);
    
?>

@section('floating.menu')
  	{!! view('folio::partial.c-floating-menu', ['buttons' => [
		  '<i class="fa fa-eye"></i>' => '/'.$item->path(),
		  '<i class="fa fa-pencil"></i>' => $item->editPath()
		  ]]) !!}
@stop

@push('scripts')
    {{-- Clipboard --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script>
        const clipHandler = function(e) {
            $(e.trigger).addClass('o-clipable--highlight');
            setTimeout(function() {
                $(e.trigger).removeClass('o-clipable--highlight');
            }, 100);
        };

        // Instantiate ClipboardJS elements
        const twitterClip = new ClipboardJS('.js--twitter-clip');
        const facebookClip = new ClipboardJS('.js--facebook-clip');
        const instagramClip = new ClipboardJS('.js--instagram-clip');

        // Bind success callbacks
        twitterClip.on('success', clipHandler);
        facebookClip.on('success', clipHandler);     
        instagramClip.on('success', clipHandler);    
    </script>
    <script>
        
    </script>

@endpush

@section('content')

    <style>
    .o-social-copy {
        margin-bottom: 40px;
    }

    .o-social-copy__facebook-image {
        background-size: cover;
        background-position: center;
        border-bottom: solid 1px rgba(0,0,0,0.07);
    }

    .o-social-copy__instagram-image {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-bottom: solid 1px rgba(0,0,0,0.07);
    }    

    .o-social-copy__facebook-image:after {
        /* 273.61 / 527 px = 51.92 % */
        padding-top: 51.92%;
        display: block;
        content: '';
    }

    .o-social-copy__instagram-image:after {
        /* 273.61 / 527 px = 51.92 % */
        padding-top: 100%;
        display: block;
        content: '';
    }    

    .o-social-copy__text {
        padding: 20px;
    }

    .o-clipable .o-social-copy__text span:not(.u-not-copy) {
        transition: background-color 1s;
    }

    .o-clipable.o-clipable--highlight .o-social-copy__text span:not(.u-not-copy) {
        transition: background-color 0s;
        background-color: #ffe6a2;
    }

    .o-social-copy img {
        max-width:100%;
    }

    .o-social-copy a:hover {
        text-decoration: underline;
    }

    .o-social-copy__hashtag {
        color: rgba(0,0,0,0.5);
    }

    .o-social-copy__text-domain {
        font-size: 70%;
        color: rgba(0,0,0,0.5);
        text-transform: uppercase;
    }

    .o-social-copy__text-domain a {
        color: rgba(0,0,0,0.5);
        text-decoration: none;
    }

    </style>

    <div class="o-wrap o-wrap--size-550 u-pad-t-12x u-pad-b-5x" style="font-family: 'Inter', system-ui, sans-serif">
        
        <p class="u-font-size--e"><strong>{{ $item->title }}</strong></p>

        <p class="u-font-size--e"><strong>
            <a href="{{ $twitterLink }}" target="_blank">Twitter</a>        
        </strong></p>

        <div class="u-font-size--e u-border o-social-copy js--twitter-clip o-clipable" data-clipboard-text="{{ $twitterClip }}">
        
            <div class="o-social-copy__text">
            <span>
                New episode is out!
                <br>
                <br>
                🎙 {!! $twitter_title !!}
                {{--@isset($episode_number) (#{{$episode_number}}) @endisset--}}
                @isset($emoji) {{ $emoji }} @endisset</span>
                <br>
                <br>
                <span>
                @if($hashtagText)
                    {!! $hashtagText !!}
                    <br>
                    <br>
                @endif
                </span>
                <span>
                <a href="{!! $item->URL() !!}">{!! $item->URL() !!}</a>
                </span>
            </div>
        
        </div>

        <p class="u-font-size--e"><strong>
            <a href="{{ $facebookLink }}" target="_blank">Facebook</a>
            &
            <a href="{{ $linkedInLink }}" target="_blank">LinkedIn</a>
        </strong></p>

        <div class="u-font-size--e u-border o-social-copy js--facebook-clip o-clipable" data-clipboard-text="{{ $facebookClip }}">
        
            @isset($thumbnail)
            <div class="o-social-copy__facebook-image" style="background-image: url('{{ $thumbnail }}')"></div>
            {{-- <div class="o-social-copy__facebook-image" style="background-image: url('/tmp/{{ $imgName }}')"></div> --}}
            @endisset

            <div class="o-social-copy__text">
                <span class="o-social-copy__text-domain u-not-copy">{{ $item->domain() }}</span>
                <br>
                <br>

                <span>🎙 {!! $title !!}</span>
                {{--@isset($episode_number) (#{{$episode_number}}) @endisset--}}
                @isset($emoji) {{ $emoji }} @endisset</span>
                <br>
                <br>
                <span>
                @isset($shareText)
                    {!! $shareText !!}
                @else
                    {!! $summary !!}
                @endisset
                </span>
                <br>
                <br>

                <span>
                <a href="{!! $URL !!}">{!! $URL !!}</a>
                </span>
                <br>
                <br>

                @isset($itunes)
                    <span>- Apple Podcasts
                    {{ substr($itunes, 0, $networkPreviewLength).'..' }}</span>
                    <br>
                @endisset
                @isset($spotify)
                    <span>- Spotify
                    {{ substr($spotify, 0, $networkPreviewLength).'..' }}</span>
                    <br>
                @endisset
                @isset($youtube)
                    <span>- Youtube
                    {{ substr($youtube, 0, $networkPreviewLength).'..' }}</span>
                    <br>
                @endisset
                @isset($overcast)
                    <span>- Overcast
                    {{ substr($overcast, 0, $networkPreviewLength).'..' }}</span>
                    <br>
                @endisset
                @isset($google)
                    <span>- Google Podcasts
                    {{ substr($google, 0, $networkPreviewLength).'..' }}</span>
                    <br>
                @endisset                

                @if(
                    @isset($itunes) ||
                    @isset($spotify) ||
                    @isset($youtube) ||
                    @isset($google) ||
                    @isset($overcast)
                )
                    <br>
                @endif
                @if($hashtagText)
                    {!! $hashtagText !!}
                @endif
            </div>
        
        </div>

        <p class="u-font-size--e"><strong>
            <a href="{{ $squareImageLink }}" target="_blank">Insta</a><a href="{{ $squareImageLink }}?crop=1" target="_blank">gram</a>
        </strong></p>

        <div class="u-font-size--e u-border o-social-copy js--instagram-clip o-clipable" data-clipboard-text="{{ $instagramClip }}">
        
            @isset($thumbnail)
            <a href="{{ $squareImageLink }}" target="_blank">
                <div class="o-social-copy__instagram-image" style="background-image: url('{{ $thumbnail }}')"></div>
            </a>
            @endisset

            <div class="o-social-copy__text">
                <span class="o-social-copy__text-domain u-not-copy">{{ $item->domain() }}
                · {{strlen($instagramClip)}} / 2200 characters
                · <a href="{{ $squareImageLink }}?crop=1" target="_blank">Cropped</a>
                </span>
                <br>
                <br>

                <span>🎙 {!! $title !!}</span>
                {{--@isset($episode_number) (#{{$episode_number}}) @endisset--}}
                @isset($emoji) {{ $emoji }} @endisset</span>
                <br>
                <br>
                <span>
                @isset($shareText)
                    {!! $shareText !!}
                @else
                    {!! $summary !!}
                @endisset
                <br>
                <br>
                {{ $listenText }}
                <br>
                <br>
                Enjoy!
                </span>
                <br>
                <br>
                @if($hashtagText)
                    {!! $hashtagText !!}
                @endif
            </div>
        
        </div>        

        {!! view('folio::partial.c-media')->with(['media' => [
            'twitter' => $twitterLink,
            'facebook' => $facebookLink,
            'linkedin' => $linkedInLink,
            'instagram' => $squareImageLink,
        ]]) !!}
        <br>


        {{--

        <p class="u-font-size--e"><strong>Debug</strong></p>

        <div class="u-font-size--e u-border o-social-copy">
        
        <div class="o-social-copy__text">

            <strong>path</strong> · <a href="/{!! $item->path() !!}">{!! $item->path() !!}</a>
            <br>
            <strong>path(true)</strong> · <a href="{!! $item->path(true) !!}">{!! $item->path(true) !!}</a>
            <br>
            <strong>URL</strong> · <a href="{!! $URL !!}">{!! $cleanURL !!}</a>
            <br>        
            <strong>slug</strong> · {!! $item->slug !!}

        </div>

        --}}

    </div>

    <!-- c-item-feed -->

    <div class="o-wrap o-wrap--item-feed">

        {{-- {!! view('partial.c-item-feed', ['item' => $item]) !!} --}}

    </div>

    <br>
    <br>
    <br>

    <div style="font-size:0.52em">
    <div style="font-size:{{ $font_size }}">
  
    {{--  c-item-v2-editorial  --}}
    {{--
    {!! view('partial.c-item-v2-editorial', ['item' => $item, 'hide_date' => $hide_date, 'is_single' => true]) !!}
    --}}
  
    </div>
  </div>    

  <div class="o-wrap u-mar-b-8x">
        {{ view('partial.o-card-podcast', [
            'podcast' => new Podcast($item),
            'feature' => true
        ]) }}
  </div>

  <div class="o-wrap o-wrap--size-500 u-mar-b-8x">
        {{ view('partial.o-card-podcast', [
            'podcast' => new Podcast($item),
        ]) }}
  </div> 

@stop