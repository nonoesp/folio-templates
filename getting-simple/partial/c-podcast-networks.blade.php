<?php
    if(isset($itunes) && $itunes === true) $itunes = config('networks.itunes');
    if(isset($spotify) && $spotify === true) $spotify = config('networks.spotify');
    if(isset($youtube) && $youtube === true) $youtube = config('networks.youtube');
    if(isset($overcast) && $overcast === true) $overcast = config('networks.overcast');
    if(isset($google) && $google === true) $google = config('networks.google');
?>

@isset($itunes)

    <a href="{{ $itunes }}" target="_blank" style="display:inline-block;overflow:hidden;background:url(https://gettingsimple.com/img/podcast/apple-podcasts.png) no-repeat;width:130px;height:34px;background-size:contain;-webkit-box-shadow:none;box-shadow:none;margin-right:4px"></a>{{--

--}}@endisset<!--

-->@isset($spotify){{--

--}}<a href="{{ $spotify }}" target="_blank" style="display:inline-block;overflow:hidden;background:url(https://gettingsimple.com/img/podcast/spotify.png) no-repeat;width:84px;height:34px;background-size:contain;-webkit-box-shadow:none;box-shadow:none;margin-right:4px"></a>{{--

--}}@endisset<!--

-->@isset($youtube){{--

--}}<a href="{{ $youtube }}" target="_blank" style="display:inline-block;overflow:hidden;background:url(https://gettingsimple.com/img/podcast/youtube-big.png) no-repeat;width:107px;height:34px;background-size:contain;-webkit-box-shadow:none;box-shadow:none"></a>{{--

--}}@endisset<!--

-->@isset($overcast){{--

--}}<a href="{{ $overcast }}" target="_blank" class="u-hidden-palm" style="display:inline-block;overflow:hidden;background:url(https://gettingsimple.com/img/podcast/overcast.png) no-repeat;width:96px;height:34px;background-size:contain;-webkit-box-shadow:none;box-shadow:none"></a>{{--

--}}@endisset