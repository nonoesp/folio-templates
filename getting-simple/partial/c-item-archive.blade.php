<?php

  if(!isset($collection)) {
    return;
  }

  $date = new Date($collection->get(0)['published_at']);
  $year = $date->format('Y');
?>

  @foreach($collection as $i)

    <?php
    $date = new Date($i->published_at);
    $date_year = $date->format('Y');
    $date = ucWords($date->format('M Y'));

    if ($loop->first) {
      $year = $date_year;
    ?>
      <div class="[ grid ] [ c-archive ]" ><!--
      --><aside class="[ grid__item  one-quarter  palm--one-whole ]" >
            <h2 class="[ c-archive__list--border-top ] [ c-archive__year ]">{{ $year }}</h2>
          </aside><!--
       --><div class="[ grid__item  three-quarters  palm--one-whole ]"><!--
          --><div class="[ grid ] [ c-archive__list--border-top ]"><!--
             --><div class="[ grid__item ]" >
                  <ul class="[ c-archive__list ]" >
    <?php
    } else if($year != $date_year) {
      $year = $date_year;
      ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="[ grid ] [ c-archive ]" ><!--
--><aside class="[ grid__item  one-quarter  palm--one-whole ]" >
      <h2 class="[ c-archive__list--border-top ] [ c-archive__year ]">{{ $year }}</h2>
    </aside><!--
 --><div class="[ grid__item  three-quarters  palm--one-whole ]"><!--
    --><div class="[ grid ] [ c-archive__list--border-top ]"><!--
       --><div class="[ grid__item ]" >
            <ul class="[ c-archive__list ]" >

    <?php   
    }
    ?>
        
    <li>
      <a href="{{ '/'.$i->path() }}">
        <b class="c-archive__list__title">{!! $i->title !!}</b>
        <em class="c-archive__list__date">{!! $date !!}</em>
      </a>
    </li>

  @endforeach 

        </ul>
      </div>
    </div>
  </div>
</div>