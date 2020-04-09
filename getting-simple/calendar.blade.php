<?php

    $vCalendar = new \Eluceo\iCal\Component\Calendar('www.nono.ma');

    if(isset($item)) {
        $collection = $item->collection();

        foreach($collection as $i) {

            $is_hidden = $i->deleted_at ? "X" : " ";
            $is_blog = $i->is_blog ? "X" : " ";
            $is_rss = $i->rss ? "X" : " ";

            $vEvent = new \Eluceo\iCal\Component\Event();
            $vEvent
                ->setDtStart(new \DateTime($i->published_at))
                ->setDtEnd(new \DateTime($i->published_at))
                ->setNoTime(true)
                ->setSummary($i->title)
                ->setDescription('
['.$is_hidden.'] Hidden
['.$is_blog.'] Blog
['.$is_rss.'] RSS'
.strip_tags($i->htmlText()))
                ->setUrl(\Request::root().'/'.$i->path())
            ;
            $vCalendar->addComponent($vEvent);
        }
    }

    header('Content-Type: text/calendar; charset=utf-8');
    // header('Content-Disposition: attachment; filename="cal.ics"');

    echo $vCalendar->render();
?>