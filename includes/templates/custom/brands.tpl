<!-- DEBUG brands.tpl loaded -->
<?php
$brandCount = (isset($brand_arr) && is_array($brand_arr)) ? count($brand_arr) : 0;
echo "<!-- DEBUG brand_arr_count: {$brandCount} -->";
if ($brandCount === 0) {
    echo "<!-- DEBUG brand_arr is empty -->";
}
?>
<?php if ($brandCount > 0): ?>
<span class="title">Бренды</span>
<ul>
    <?php if (isset($brand) && $brand != -1): ?>
        <?php foreach ($brand_arr as $item1): ?>
            <?php if ($brand == $item1['id']): ?>
                <li>
                    <?php
                    if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri);
                    elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);
                    elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri);
                    else $sort1 = $Global_uri;

                    if (substr_count($sort1, '?brand='.$item1['id'].'&') > 0) $sort1 = str_replace('?brand='.$item1['id'].'&', '?', $sort1);
                    else { $sort1 = str_replace('?brand='.$item1['id'], '', $sort1); $sort1 = str_replace('&brand='.$item1['id'], '', $sort1); }
                    ?>
                    <label for="<? echo $item1['uri']; ?>">
                        <input type="checkbox" disabled="true" id="<? echo $item1['uri']; ?>" style="display: none;"><span class="custom checkbox checked"></span><? echo $item1['title']; ?><img src="<? echo $HTTP; ?>images/ico_close.png" onclick="window.location.href='<? echo $HTTP.substr($sort1,1); ?>';" width="14" height="14"/>
                    </label>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>

    <?php elseif ($brandCount > 1): ?>
        <?php foreach ($brand_arr as $item1): ?>
            <?php
            if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri);
            elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);
            elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri);
            else $sort1 = $Global_uri;

            if (substr_count($sort1, '?brand='.$item1['id'].'&') > 0) $sort1 = str_replace('?brand='.$item1['id'].'&', '?', $sort1);
            else { $sort1 = str_replace('?brand='.$item1['id'], '', $sort1); $sort1 = str_replace('&brand='.$item1['id'], '', $sort1); }
            ?>
            <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'brand='.$item1['id']; ?>'">
                <label for="<? echo $item1['uri']; ?>">
                    <input type="checkbox" id="<? echo $item1['uri']; ?>" style="display: none;"><span class="custom checkbox <? if (isset($brand) && $brand == $item1['id']) echo 'checked'; ?>"></span><a class="filter_item" href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'brand='.$item1['id']; ?>"><? echo $item1['title']; ?></a>
                </label>
            </li>
        <?php endforeach; ?>

    <?php elseif ($brandCount == 1): ?>
        <?php
        if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri);
        elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);
        elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri);
        else $sort1 = $Global_uri;

        if (substr_count($sort1, '?brand='.$brand_arr[0]['id'].'&') > 0) $sort1 = str_replace('?brand='.$brand_arr[0]['id'].'&', '?', $sort1);
        else { $sort1 = str_replace('?brand='.$brand_arr[0]['id'], '', $sort1); $sort1 = str_replace('&brand='.$brand_arr[0]['id'], '', $sort1); }
        ?>
        <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'brand='.$brand_arr[0]['id']; ?>'">
            <label for="<? echo $brand_arr[0]['uri']; ?>">
                <input type="checkbox" id="<? echo $brand_arr[0]['uri']; ?>" style="display: none;"><span class="custom checkbox <? if (isset($brand) && $brand == $brand_arr[0]['id']) echo 'checked'; ?>"></span><a class="filter_item" href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'brand='.$brand_arr[0]['id']; ?>"><? echo $brand_arr[0]['title']; ?></a>
            </label>
        </li>
    <?php endif; ?>
</ul>
<?php endif; ?>
