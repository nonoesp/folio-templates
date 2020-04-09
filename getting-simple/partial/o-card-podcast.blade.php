<?php
if(!isset($feature)) $feature = false;
if(!isset($image)) {
    $image = $podcast->cardImage();
}
?>

<div class="[  o-card  ]
        @if($feature) [ o-card--feature ] @endif
        @if($podcast->item->boolProperty('card-fade')) [ o-card--fade ] @endif">

    <div class="[  o-card__image  ] [ lazy ]" data-src="{{ $image }}" style="background-image:url('{{ $image }}')">
    
    </div>

    @if(!$podcast->item->boolProperty('hide-titles'))
        <div class="[  o-card__text ]">
        
            <div class="[ o-card__date ]
            [ u-case-upper u-font-size--a u-opacity--half ]">
                {{ $podcast->date() }}
            </div>

            <div class="o-card__title f-inter">

                {{ $podcast->number()}} ·

                {{ $podcast->title() }} 

            </div>

            <div class="o-card__summary">

                {{ $podcast->plainSummary() }}

            </div>

            <div class="[ o-card__metadata ]
            [ u-font-size--b u-opacity--half ]
            [ u-mar-t-1x ]">
                {{ $podcast->duration() }}
            </div>

        </div>
    @endif

</div>		