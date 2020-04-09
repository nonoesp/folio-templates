@php
if(!isset($feature)) $feature = false;
if(!isset($hide_date)) $hide_date = false;
if(!isset($image)) {
    $image = $item->cardImage();
}
@endphp

<div class="[  o-card  ]
        @if($feature) [ o-card--feature ] @endif
        @if($item->boolProperty('card-fade')) [ o-card--fade ] @endif">

    <div class="[  o-card__image  ] [ lazy ]" data-src="{{ $image }}" style="background-image:url('{{ $image }}')">
    
    </div>

    @if(!$item->boolProperty('hide-titles'))
        <div class="[  o-card__text ]">
        
            @if(!$hide_date)
            <div class="[ o-card__date ]
            [ u-case-upper u-font-size--a u-opacity--half ]">
                {{ $item->date() }}
            </div>
            @endif

            <div class="o-card__title f-inter">

                {{ $item->title }} 

            </div>

            <div class="o-card__summary">

                {!! $item->description() !!}

            </div>

            <div class="[ o-card__metadata ]
            [ u-font-size--b u-opacity--half ]
            [ u-mar-t-1x ]">
                {{ $item->readTime() }}
            </div>

        </div>
    @endif

</div>		