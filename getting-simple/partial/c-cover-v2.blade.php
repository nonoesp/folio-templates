
<section class="[ c-cover {!! $class ?? 'c-cover--header' !!} ]"{{--
--}} @if(isset($image)) style="background-image:url('{!! $image !!}')" @endif{{--
--}} @if(isset($background_color)) style="background-color:{{$background_color}}" @endif>

    @if(isset($title) || isset($subtitle))
	<div class="c-cover__title">
		@isset($title)
        <span class="[ c-cover__title-a ]">{!! $title !!}</span>
		<br>
        @endisset
        @isset($subtitle)
		<span class="[ c-cover__title-b  {!! $classes_title_b ?? '' !!} ]">{!! $subtitle ?? '' !!}</span>
        @endisset
	</div>
    @endif

	<div class="c-cover__description">
		{!! $description ?? '' !!}
	</div>

	@if(isset($slideshow))
		<div class="[ c-cover__slide c-cover__slide-back c-cover__slide-back--js ]">
		</div>

		<div class="[ c-cover__slide c-cover__slide-front c-cover__slide-front--js ]">
		</div>
	@endif

	@if(isset($video))

    <video class="c-cover__video" src="{!! $video !!}" preload="auto" autoplay loop muted>
			<source src="{!! $video !!}" type="video/mp4"/>
			Your browser does not support HTML5 video.
    </video>

	@endif

    @if(isset($youtube))

<style>
.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
    height: 0;
}
.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>

<div class="video-container">
    <iframe width="560" height="315"
    src="https://www.youtube.com/embed/{{$youtube}}?controls=1&autoplay=1&showinfo=0&controls=0"
    frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
    allowfullscreen></iframe>
    </div>

    @endif

	<div class="[ c-cover__slide c-cover__veil c-cover__veil--js ]" @if(isset($veil_opacity))style="opacity:{!! $veil_opacity !!}"@endif>
	</div>

</section>