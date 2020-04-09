<?php
	$listenText = trans('base.newsletter.listen-text');
	$followText = trans('base.newsletter.follow-text');
	$references = trans('base.newsletter.references');
	if(!isset($text)) $text = trans('base.newsletter.text');
	if(!isset($headline)) $headline = trans('base.slogan');
	if(!isset($hide_subscribe)) $hide_subscribe = false;
?>

<div class="[ c-newsletter js--scroll-over ]
			[ gf-system-ui-ibm-plex-sans ]">

	<div class="[ c-newsletter__slogan ]
				[ u-text-align--palm-center ]">

		<div
		class="[ u-font-size--350 palm--u-font-size--190 ]"
		style="letter-spacing:-0.02em">
		
			{!! Item::convertToHtml($headline) !!}

		</div>

	</div>

	<div class="[ c-newsletter__small-slogan ]
	[ u-opacity--high u-font-size--170 palm--u-font-size--120 ]">

		{!! $text !!}

	</div>

	<br/>

	@if(!$hide_subscribe)

		{!! view('folio::partial.c-footer__subscribe', [
			'source' => 'gs-newsletter',
			'medium' => 'web',
			'campaign' => 'sign-up',
			'hide_terms' => true,
			]) !!}

	@endif

</div>

@isset($show_player)
	{{ Podcast::latest()->player(['text' => '[[title]]']) }}
@endisset

@isset($show_networks)
	<div class="u-text-align--palm-center">
		{!! view('partial.c-podcast-networks', [
			'itunes' => true,
			'spotify' => true,
			'youtube' => true,
			'overcast' => true,
		]) !!}
	</div>
@endif

@if(!isset($hide_arrow) AND isset($hide_subscribe))
<br/>
<div class="[ u-visible-palm u-text-align--center ] [ js--arrow-down u-cursor-pointer ]">
	<div style="width:40px;margin:auto;">{!! config('svg.arrow-down') !!}</div>
</div>
@endif