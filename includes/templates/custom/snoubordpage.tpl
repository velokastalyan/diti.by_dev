<div itemscope itemtype="http://schema.org/WebPage">
<ul class="bread_crumbs" xmlns="http://www.w3.org/1999/xhtml" itemprop="breadcrumb">
    <li>
        <a href="<? echo $HTTP; ?>">Главная</a>
    </li>
    <li>
        <? if($c_deep > 1): ?>
        <? $c_uri_3 = explode("/", $c3_uri); ?>
        <a href="<? echo $HTTP; ?><? echo $c_parent_path_uri; ?>/"><? echo $c_parent_path; ?></a></li>
    <? endif; ?>

    <? if (isset($c3_title)): ?>
    <li><a href="<? echo $HTTP; ?><? echo $c2_uri; ?>"><? echo $c_title; ?></a></li><li><? echo $c3_title; ?>
    <? else: ?>
    <li><? echo $c_title; ?>
        <? endif; ?>
    </li>
</ul>
</div>
<? if ($product_found): ?>
<div class="row">
    <h1 class="heading"><? if (!isset($c3_title)) echo $c_title; else echo $c3_title; ?></h1>
    <div class="three columns">
        <aside class="left_aside">
            <form action="#" class="custom">
                <? if($children_found): ?>
                <span class="title">Категория</span>
                <ul><?
					if (substr_count($Global_uri, '?page'.$r_page.'&') > 0)  $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri;
                    if (substr_count($sort1, '?category='.$category.'&') > 0) $sort1 = str_replace('?category='.$category.'&','?',$sort1); else { $sort1 = str_replace('?category='.$category,'',$sort1); $sort1 = str_replace('&category='.$category,'',$sort1); } ?>
                    <? if ($category != -1): ?>
                    <? foreach($children as $child): ?>
                    <? if ($category == $child['id']): ?>
                    <li>
                        <label for="<? echo $child['uri']; ?>">
                            <input type="checkbox" disabled="true" id="<? echo $child['uri']; ?>" style="display: none;"><span class="custom checkbox checked"></span><? echo $child['title']; ?><img src="<? echo $HTTP; ?>images/ico_close.png" onclick="window.location.href='<? echo $HTTP.substr($sort1,1); ?>';" width="14" height="14"/>
                        </label>
                    </li>
                    <? endif; ?>
                    <? endforeach; ?>
                    <? else: ?>
                    <? foreach($children as $child): ?>
                    <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'category='.$child["id"]; ?>'">
                    <label for="<? echo $child['child_uri']; ?>">
                            <input type="checkbox" id="<? echo $child['child_uri']; ?>" style="display: none;"><span class="custom checkbox <? if ($child['id'] === $cat) echo 'checked'; ?>"></span><a class="filter_item" href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'category='.$child['id']; ?>"><? echo $child['title']; ?></a>
                        </label>
                    </li>
                    <? endforeach; ?>
                    <? endif; ?>
                </ul>
                <? endif; ?>
                <? if ($brand_found): ?><? /*****************BRAND************/ ?>
                <span class="title">Брэнд</span>
                <ul><?
					if (substr_count($Global_uri, '?page'.$r_page.'&') > 0)  $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri;
                    if (substr_count($sort1, '?brand='.$brand.'&') > 0) $sort1 = str_replace('?brand='.$brand.'&','?',$sort1); else { $sort1 = str_replace('?brand='.$brand,'',$sort1); $sort1 = str_replace('&brand='.$brand,'',$sort1); } ?>
                    <? if ($brand != -1): ?>
                    <? foreach($brand_arr as $item1): ?>
                    <? if ($brand == $item1['id']): ?>
                    <li>
                        <label for="<? echo $item1['uri']; ?>">
                            <input type="checkbox" disabled="true" id="<? echo $item1['uri']; ?>" style="display: none;"><span class="custom checkbox checked"></span><? echo $item1['title']; ?><img src="<? echo $HTTP; ?>images/ico_close.png" onclick="window.location.href='<? echo $HTTP.substr($sort1,1); ?>';" width="14" height="14"/>
                        </label>
                    </li>
                    <? endif; ?>
                    <? endforeach; ?>

                    <? else: ?>
                    <? foreach($brand_arr as $item1): ?>
                    <? if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri; ?>
                    <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'brand='.$item1['id']; ?>'">
                        <label for="<? echo $item1['uri']; ?>">
                            <input type="checkbox" id="<? echo $item1['uri']; ?>" style="display: none;"><span class="custom checkbox <? if ($item1['id'] === $brand) echo 'checked'; ?>"></span><a class="filter_item" href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'brand='.$item1['id']; ?>"><? echo $item1['title']; ?></a>
                        </label>
                    </li>
                    <? endforeach; ?>
                    <? endif; ?>
                </ul>
                <? endif; ?><? /*****************END-BRAND************/ ?>

                <span class="title">Пол</span><? /*****************SEX************/ ?>
                <ul>
                    <? if ($sex != -1): ?>
                    <? foreach($sex_arr as $item1): ?>
                    <? if($sex == $item1['id']): ?>
                    <li><?
								if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri;
                        if (substr_count($sort1, '?sex='.$item1['name'].'&') >0 ) $sort1 = str_replace('?sex='.$item1['name'].'&','?',$sort1); else { $sort1 = str_replace('?sex='.$item1['name'],'',$sort1);$sort1 = str_replace('&sex='.$item1['name'],'',$sort1); } ?>
                        <label for="<? echo $item1['name']; ?>">
                            <input type="checkbox" disabled="true" id="<? echo $item1['name']; ?>" style="display: none;"><span class="custom checkbox checked"></span><? echo $item1['title']; ?><img src="<? echo $HTTP; ?>images/ico_close.png" onclick="window.location.href='<? echo $HTTP.substr($sort1,1); ?>';" width="14" height="14"/>
                        </label>
                    </li>
                    <? endif; ?>
                    <? endforeach; ?>

                    <? elseif (count($sex_arr) > 1): ?>


                    <? foreach($sex_arr as $item1): ?>
                    <? if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri; ?>
                    <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'sex='.$item1["name"]; ?>'">
                        <label for="<? echo $item1['name']; ?>">
                            <input type="checkbox" id="<? echo $item1['name']; ?>" style="display: none;"><span class="custom checkbox"></span><a class="filter_item" href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'sex='.$item1['name']; ?>"><? echo $item1['title']; ?></a>
                        </label>
                    </li>
                    <? endforeach; ?>

                    <? elseif (count($sex_arr) == 1): ?>
                    <? if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri; ?>
                    <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'sex='.$sex_arr[0]['name']; ?>'">
                        <label for="<? echo $sex_arr[0]['name']; ?>">
                            <input type="checkbox" id="<? echo $sex_arr[0]['name']; ?>" style="display: none;"><span class="custom checkbox"></span><a class="filter_item" href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'sex='.$sex_arr[0]['name']; ?>"><? echo $sex_arr[0]['title']; ?></a>
                        </label>
                    </li>
                    <? endif; ?>
                </ul><? /*****************END-SEX************/ ?>


                <span class="title">Цена</span><? /*****************PRICE************/ ?>
                <? if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri; ?>
                <div class="layout-slider" style="width: 100%;margin-bottom: 25px;margin-top: 15px;">
                    <span style="display: inline-block; width: 100%; padding: 0 5px;"><input id="Slider2" type="slider" name="price" value="<?echo $PriceToSlider[0];?>;<?echo $PriceToSlider[1];?>" data-value-main="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'price='; ?>" /></span>
                </div>
                <span class="title">Год выпуска</span><? /*****************year************/ ?>
                <ul>
                    <? if ($year != -1): ?>
                    <? foreach($year_arr as $item1): ?>
                    <? if($year == $item1['id']): ?>
                    <li><?
                                if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri;
                        if (substr_count($sort1, '?year='.$item1['name'].'&') >0 ) $sort1 = str_replace('?year='.$item1['name'].'&','?',$sort1); else { $sort1 = str_replace('?year='.$item1['name'],'',$sort1);$sort1 = str_replace('&year='.$item1['name'],'',$sort1); } ?>
                        <label for="<? echo $item1['name']; ?>">
                            <input type="checkbox" disabled="true" id="<? echo $item1['name']; ?>" style="display: none;"><span class="custom checkbox checked"></span><? echo $item1['title']; ?><img src="<? echo $HTTP; ?>images/ico_close.png" onclick="window.location.href='<? echo $HTTP.substr($sort1,1); ?>';" width="14" height="14"/>
                        </label>
                    </li>
                    <? endif; ?>
                    <? endforeach; ?>

                    <? elseif (count($year_arr) > 1): ?>

                    <? foreach($year_arr as $item1): ?>
                    <? if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri; ?>
                    <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'year='.$item1['name']; ?>'">
                        <label for="<? echo $item1['name']; ?>">
                            <input type="checkbox" id="<? echo $item1['name']; ?>" style="display: none;"><span class="custom checkbox"></span><a class="filter_item" href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'year='.$item1['name']; ?>"><? echo $item1['title']; ?></a>
                        </label>
                    </li>
                    <? endforeach; ?>

                    <? elseif (count($year_arr) == 1): ?>
                    <? if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri; ?>
                    <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'year='.$year_arr[0]['name']; ?>'">
                        <label for="<? echo $year_arr[0]['name']; ?>">
                            <input type="checkbox" id="<? echo $year_arr[0]['name']; ?>" style="display: none;"><span class="custom checkbox"></span><a class="filter_item" href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'year='.$year_arr[0]['name']; ?>"><? echo $year_arr[0]['title']; ?></a>
                        </label>
                    </li>
                    <? endif; ?>
                </ul><? /*****************END-year************/ ?>

            </form>
        </aside>
    </div>
    <div class="nine columns">
            <div class="description_category_top">
                <?php if ($child_description): ?>
                <div class="start_text"><? echo $child_description; ?></div>
                <div class="more_text"><? echo $child_description_more; ?></div>
                <?php if ($child_description_more): ?>
                    <span class="more">Подробнее</span>
                    <?php endif; ?>
                <?php else: ?>
                <div class="start_text"><? echo $c_description; ?></div>
                <div class="more_text"><? echo $c_description_more; ?></div>
                <?php if ($c_description_more): ?>
                    <span class="more">Подробнее</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <div class="top_actions">
            <form action="#" class="custom clearfix">
                <div class="item">
                    <? if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri; ?>
                    Показано <span><?php if ($this->page == 1) echo $this->page; else echo ($this->page-1)*$this->on_page+1 ?><? if ($cnt > 1) if ((count($product_arr) >= $this->on_page) && ($this->on_page!="all")) echo '-'.$this->page*$this->on_page; elseif (count($product_arr) != 1) echo '-'.count($product_arr); elseif($this->on_page=="all") echo '-'.$cnt; ?></span> из <span><? echo $cnt; ?></span>
                </div>
                <div class="item_page"><? if (substr_count($sort1, '?on_page') > 0) { if (substr_count($sort1, '&') > 0) $sort1 = str_replace('?on_page='.$on_page.'&', '?', $sort1); else $sort1 = str_replace('?on_page='.$on_page, '', $sort1); } elseif (substr_count($sort1, '&on_page') > 0) $sort1 = str_replace('&on_page='.$on_page, '', $sort1); ?>
                    Показывать по  <span class="items_line"><a href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'on_page=36'; ?>" <? if ($this->on_page == 36): ?>class="active"<? endif; ?>>36</a><a href="<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?'; echo 'on_page=all'; ?>" <? if ($this->on_page == all): ?>class="active"<? endif; ?>>Все</a></span> на странице
                </div>
               <!-- <select style="display:none;">
                    <option selected><a href="?sort_by=title">По названию</a></option>
                    <option selected><a href="?sort_by=price">По цене</a></option>
                </select>    -->
                <div class="custom dropdown small">
                    <a href="#" class="current">
                        Сортировать
                    </a>
                    <a href="#" class="selector"></a>
                    <ul><?
					if (substr_count($Global_uri, '?page'.$r_page.'&') > 0) $sort1 = str_replace('?page'.$r_page.'&', '?', $Global_uri); elseif (substr_count($Global_uri, '?page='.$r_page) > 0) $sort1 = str_replace('?page='.$r_page, '', $Global_uri);  elseif (substr_count($Global_uri, '&page='.$r_page) > 0) $sort1 = str_replace('&page='.$r_page, '', $Global_uri); else $sort1 = $Global_uri;
                        if (substr_count($sort1, '?sort_by') > 0) { if (substr_count($sort1, '&') > 0) $sort1 = str_replace('?sort_by='.$sort_by.'&', '?', $sort1); else $sort1 = str_replace('?sort_by='.$sort_by, '', $sort1); } elseif (substr_count($sort1, '&sort_by') > 0) $sort1 = str_replace('&sort_by='.$sort_by, '', $sort1); ?>
                        <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?';?>sort_by=title';">По названию</li>
                        <li onclick="window.location.href='<? echo $HTTP.substr($sort1,1); if (substr_count($sort1, '?') > 0) echo '&'; else echo '?';?>sort_by=price';">По цене</li>
                    </ul>
                </div>
            </form>
        </div>
        <ul class="list_products medium">
            <? foreach($product_arr as $item1): ?>
            <li>
                <a href="<? echo $HTTP.$item1['c_parent_path_uri'].'/'.$item1['c3_uri'].'/'; ?><? echo $item1['uri']; ?>.html">
                    <? if ($item1['is_sale']): ?>
                    <span class="sale"></span>
                    <? endif; if ($item1['is_new']): ?>
                    <span class="new"></span>
                    <? endif; ?>
                <span class="image">
					<img src="<? echo $HTTP; ?>pub/products/<? echo $item1['id']; ?>/180x190/<? echo $item1['image_filename']; ?>" alt="<? echo $item1['title']; ?>">
				</span>
				<span class="name_product">
					<? echo $item1['title']; ?>
				</span>

                    <? if ($item1['discount'] > 0): ?>
                        <del>
                            <?php if ($ip_country == 'Russian Federation'): ?>
                                <? echo get_price_in_rus_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar, $item1['rouble_currency'], $sd_rur ); ?> руб.</br>
                            <?php else: ?>
                                <? echo get_price_in_bel_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                            <?php endif; ?>
                        </del>
                        <span class="price">
							<?php if ($ip_country == 'Russian Federation'): ?>
								<? echo get_price_in_rus_rub( $item1['discount'], $item1['dollar_currency'], $sd_dollar, $item1['rouble_currency'], $sd_rur ); ?> руб.</br>
							<?php else: ?>
								<? echo get_price_in_bel_rub( $item1['discount'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
							<?php endif; ?>
						</span><?/*
                    <?php if ( $ip_country != 'Russian Federation' ): ?>
                                        <span class="old-currency">
                                             <?php echo get_price_in_old_bel_rub( $item1['discount'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                                        </span>
                    <?php endif; ?>*/?>
                    <? else: ?>
                        <span class="price">
					<?php if ($item1['price'] != 0): ?>
                        <?php if ($ip_country == 'Russian Federation'): ?>
                            <? echo get_price_in_rus_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar, $item1['rouble_currency'], $sd_rur ); ?> руб.</br>
                        <?php else: ?>
                            <? echo get_price_in_bel_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="soon">Скоро в продаже</span>
                    <?php endif; ?>
						</span>
				<?php endif; ?>
				<?/*
                    <?php if ( $ip_country != 'Russian Federation' ): ?>
                                        <span class="old-currency">
                                             <?php echo get_price_in_old_bel_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                                        </span>
                    <?php endif; ?>
                    <? endif; ?>*/?>
                </a>
            </li>
            <? endforeach; ?>
        </ul>

        <? endif; ?>
        <? //if ((!$product_found) || (!$children_found) || (count($product_arr) == 0)): ?>
        <? if ((!$child) && (($product_found = false) || (!isset($product_arr)))): echo $product_found; ?>
        <div class="row">
            Извините, нет записей, удовлетворяющих вашим условиям.
        </div>
        <? endif; ?>

                <? CControl::process('Pager', 'Products'); ?>
    </div>
</div>
