<?php
	$header_active_link_classes = 'c-header-getting-simple__link--active';

    $header_class = 'c-header-getting-simple';
    // Class
    $class_specified = '';
    if(isset($classes) && $classes != '') {
        $class_specified = '[ ';
        foreach($classes as $class) {
        $class_specified .= $header_class.'--'.$class.' ';
        }
        $class_specified .= ']';
    }
  
    // Data
    if(isset($data['is_navigation_hidden'])) { $is_navigation_hidden = $data['is_navigation_hidden']; }
    if(isset($data['is_media_hidden'])) { $is_media_hidden = $data['is_media_hidden']; }
    if(isset($data['image'])) { $image = $data['image']; }
    if(isset($data['title'])) { $title = $data['title']; }
	if(isset($data['title_svg'])) { $title_svg = $data['title_svg']; }
    if(isset($data['description'])) { $description = $data['description']; }
	if(isset($data['color'])) { $color = $data['color']; }
    if(isset($data['navigation'])) { $navigation = $data['navigation']; } else {
    $navigation = [
		trans('folio::base.writing') => ['/'.config('folio.path-prefix'), config('folio.path-prefix')],
		trans('folio::base.about-me') => ['/about', 'about']
	];
  }
	if(isset($data['media_links'])) { $media_links = $data['media_links']; } else { $media_links = config('folio.media_links'); }
	$header_domain = '';
	if(config('folio.domain')) {
		$header_domain = 'http://'.config('folio.domain');
	}

	$hasPodcastTag = false;
	if (isset($item)) {
		if(in_array("Podcast", $item->tagNames())) {
			$hasPodcastTag = true;
		}
	}

	$path = \Request::path();
	$isHome = $path === '/';
	$isPodcast = substr($path, 0, 7) === 'podcast' || $hasPodcastTag;
	$isNewsletter = substr($path, 0, 10) === 'newsletter';
	$isSisyphus = substr($path, 0, 8) === 'sisyphus';
	// TODO - substr($path, 0, 7) === 'writing' || $hasWritingTag;
	$isEssays = !$isHome && !$isPodcast && !$isNewsletter && !$isSisyphus;
?>

<!-- c-header-getting-simple · styling based on frankchimero.com -->

<header class="[ {{ $header_class }} ] {{ $class_specified }}">
	
	<div class="[ o-wrap ]">

		<div class="[ grid ] [ u-select--none ]">

			<div class="[ grid__item  two-fifths palm--one-whole ] [ u-text-align--left u-text-align--palm-center ]">
				<!-- <a class="c-header-getting-simple__title" href="@if($isPodcast) /podcast @else{{ URL::to(Folio::path()) }}@endif"> -->
				<a class="c-header-getting-simple__title" href="{{ URL::to(Folio::path()) }}">
				    {!! $title ?? config('folio.title') !!}
				</a>
			</div>

			<!-- <div class=" grid__item  three-quarters  u-visible-palm  u-text-align--right u-opacity--high ">
				@if($isPodcast)
					<a href="/" class="[ c-header-getting-simple__link ]">
							<i class="fa fa-file-text-o fa--social"></i>
					</a>
				@else
					<a href="/podcast" class="[ c-header-getting-simple__link ]">
							<i class="fa fa-podcast fa--social"></i>
					</a>			
				@endif
			</div> -->

			<div class="[ grid__item  three-fifths palm--one-whole ]
						[ u-text-align--right u-text-align--palm-center ]">

				<a href="/" class="[ c-header-getting-simple__link ] [ u-hidden-lap ] 
				@if($isHome) [ {{ $header_active_link_classes }} ] @endif						
				">
						{{ trans('folio::base.home') }}
				</a>

				<a href="/podcast" class="[ c-header-getting-simple__link ] 
						@if($isPodcast) [ {{ $header_active_link_classes }} ] @endif						
						">
						{{ trans('folio::base.podcast') }}
				</a>

				<a href="/writing" class="[ c-header-getting-simple__link ] 
						@if($isEssays) [ {{ $header_active_link_classes }} ] @endif
						">
						{{ trans('base.essays') }}
				</a>


				<a href="/newsletter" class="[ c-header-getting-simple__link ] [ -u-visible-desk ]
						@if($isNewsletter) [ {{ $header_active_link_classes }} ] @endif">
						{{ trans('folio::base.newsletter') }}
				</a>

				{{--
				<a href="/sisyphus" class="[ c-header-getting-simple__link ] [ u-hidden-lap ]
						@if($isSisyphus) [ {{ $header_active_link_classes }} ] @endif">
						Sisyphus
				</a>
				--}}

				@if(false)

				<?php $twitter_image = Session::get('twitter_image'); ?>

				@if($twitter_handle = Session::get('twitter_handle'))

					@if(Auth::check() && Auth::user()->is_admin == 1)
						<a href="/admin" class="c-header-getting-simple__link">
					@endif	

						@if($twitter_image != '')
							<div class="[ c-header-getting-simple__link-text ] [ o-user-profile ] [ u-visible-desk ]">			
								<img src="{{ $twitter_image }}">
							</div>
						@else
							{{ $twitter_handle }}
						@endif
				
					@if(Auth::check() && Auth::user()->is_admin == 1)
						</a>
					@endif		
				@endif	

				@endif			

			</div>

		</div>

	</div>
</header>