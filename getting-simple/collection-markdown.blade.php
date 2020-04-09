<!-- <link rel="stylesheet" type="text/css" href="{{ config('folio.css') }}"> -->
<?php
    if(isset($item)) {
        $collection = $item->collection();
    }
?>

@isset($collection)
    @foreach($collection as $i)
    
# {{ $i->title }}

{!! $i->text !!}
    @endforeach
@endisset

