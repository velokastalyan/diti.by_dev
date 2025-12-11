<div class="wrapper_pagination">
    <ul class="pagination">
        <? if(!$pager_hide): ?>
        <? if($la_show): ?>
        <li class="prev"><a href="<? echo $HTTP.substr($Global_uri, 1); ?><? if($get == false): ?>page-<? echo $la_link_page; ?>/<? else: ?>&page=<? echo $la_link_page; ?><? endif; ?>" class="nav-left"></a></li>
        <? endif; ?>

        <? if (!$get): ?>
            <? if ($current_page > 1):  ?>
                <li class="arrow_left"><a href="<? echo $HTTP.substr($Global_uri, 1); ?>page-<? echo $current_page - 1; ?>/">&laquo; назад</a></li>
            <? endif; ?>
        <? else:  ?>
            <? if ($current_page > 1):  ?><? if (substr_count($Global_uri, '?page') > 0) $prev_page = str_replace('?page='.$current_page, '?page='.($current_page-1), $HTTP.substr($Global_uri, 1));  elseif (substr_count($Global_uri, '&page') > 0) $prev_page = str_replace('&page='.$current_page, '&page='.($current_page-1), $HTTP.substr($Global_uri, 1));  elseif (substr_count($Global_uri, '?') == 0) $prev_page= $HTTP.substr($Global_uri, 1).'?page='.($current_page-1); elseif (substr_count($Global_uri, '&') == 0) $prev_page= $HTTP.substr($Global_uri, 1).'&page='.($current_page-1); ?>
                <li class="arrow_left"><a href="<? echo $prev_page; ?>">&laquo; назад</a></li>
            <? endif; ?>
        <? endif; ?>
        <? if (count($pages) !== 1): ?>
            <? foreach ($pages as $page): ?>
                <? if($page !== PAGER_SEPARATOR_WINGS): ?>
                    <? if($page == $current_page): ?>
                    <li class="current"><span><? echo $current_page; ?></span></li>
            <? else: ?>
                <? if ($get)
                    {
						if (substr_count($Global_uri, '?page') > 0)
							 $str_page = str_replace('?page='.$current_page, '?page='.$page, $HTTP.substr($Global_uri, 1));
						elseif (substr_count($Global_uri, '&page') > 0)
							$str_page = str_replace('&page='.$current_page, '&page='.$page, $HTTP.substr($Global_uri, 1));

                        elseif (substr_count($Global_uri, '?') == 0)
                            $str_page=$HTTP.substr($Global_uri, 1).'?page='.$page;
                        else
                        {
                            if (substr_count($Global_uri, '&page') == 0)
                               $str_page=$HTTP.substr($Global_uri, 1).'&page='.$page;

                        }
                     }
                    ?>
                    <li><a href="<? if($get == false): ?><? echo $HTTP.substr($Global_uri, 1); ?>page-<? echo $page; ?>/<? else: ?><? echo $str_page; ?><? endif; ?>"><? echo $page; ?></a></li>
                <? endif; ?>
            <? else: ?>
                <li><? echo $page; ?></li>
                <? endif; ?>
            <? endforeach; ?>
        <? endif; ?>

        <? if (!$get): ?>
            <? if (count($pages) > $current_page): ?>
                <li class="arrow_right"><a href="<? echo $HTTP.substr($Global_uri, 1); ?>page-<? echo $current_page + 1; ?>/">вперед &raquo;</a></li>
            <? endif; ?>

        <? else: ?>
            <? if (count($pages) > $current_page): ?>
                <? if (substr_count($Global_uri, '?page') > 0) $next_page = str_replace('?page='.$current_page, '?page='.($current_page+1), $HTTP.substr($Global_uri, 1));  elseif (substr_count($Global_uri, '&page') > 0) $next_page = str_replace('&page='.$current_page, '&page='.($current_page+1), $HTTP.substr($Global_uri, 1));  elseif (substr_count($Global_uri, '?') == 0) $next_page= $HTTP.substr($Global_uri, 1).'?page='.($current_page+1); else $next_page= $HTTP.substr($Global_uri, 1).'&page='.($current_page+1); ?>
                    <li class="arrow_right"><a href="<? echo $next_page; ?>">вперед &raquo;</a></li>
                <? endif; ?>
            <? endif; ?>

        <? endif; ?>
    </ul>

</div>