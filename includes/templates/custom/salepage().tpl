<section id="main">
    <div itemscope itemtype="http://schema.org/WebPage">
    <ul class="bread_crumbs" itemprop="breadcrumb">
        <li><a href="<? echo $HTTP; ?>">Главная</a></li>
        <li>Распродажа</li>
    </ul>
    </div>
    <div class="row">
        <div class="twelve columns">
            <h1 class="heading">Распродажа</h1>

			<? if ($sale_found): ?>
            <ul class="list_products">
				<? foreach($sale_arr as $item1): ?>
                <li>
                    <a href="<? echo $HTTP; ?><? echo $item1['c_parent_path_uri']; ?>/<? echo $item1['c_uri']; ?>/<? echo $item1['uri']; ?>.html">
						<? if ($item1['is_sale'] > 0): ?>
                        <span class="sale"></span>
						<? elseif($item1['is_new'] > 0): ?>
                        <span class="new"></span>
						<? endif; ?>
                        <span class="image">
							<img src="<? echo $HTTP; ?>pub/products/<? echo $item1['id']; ?>/180x190/<? echo $item1['image_filename']; ?>" alt="<? echo $item1['title'] ?>">
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
                            </span>
                        <?php if ( $ip_country != 'Russian Federation' ): ?>
                            <span class="old-currency">
                                 <?php echo get_price_in_old_bel_rub( $item1['discount'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                            </span>
                        <?php endif; ?>
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
                        <?php if ( $ip_country != 'Russian Federation' ): ?>
                            <span class="old-currency">
                                 <?php echo get_price_in_old_bel_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                            </span>
                        <?php endif; ?>
                        <? endif; ?>
                    </a>
                </li>
				<? endforeach; ?>
            </ul>
			<? endif; ?>

			<? CControl::process('Pager', 'Sale'); ?>
        </div>
    </div>