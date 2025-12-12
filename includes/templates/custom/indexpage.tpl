<div class="row">
	<div class="twelve columns">
<div class="main_slider">

    <!--start slider -->

	<? if ($slider_found):   ?>

		<div id="full-width-slider" class="royalSlider heroSlider rsMinW">

			<? foreach($slider_arr as $item1 ): ?>
                   <?  if ($item1['link']): ?>
                        <a href="<? echo htmlspecialchars($item1['link']); ?>">
                    <? endif; ?>
                                            <div class="rsContent">

                                                    <img class="rsImg" src="<? echo normalize_media_url($HTTP.'pub/banners/'.$item1['id'].'/'.$item1['image_filename']); ?>" alt="" />

                                            </div>
                         <?  if ($item1['link']): ?>
                            </a>
                         <? endif; ?>
			<? endforeach; ?>

		</div>

	<? endif; ?>

    <!-- finish slider -->

</div>

</div>
</div>

<section id="main">

<div class="row">

    <div class="twelve columns">

        <span class="heading">Мы рекомендуем</span>

			<? if ($recommend_found): ?>

            <ul class="list_products">

				<? foreach($recommend as $item1): ?>

							<li>

								<a href="<? echo $HTTP; ?><? echo $item1['parent_path_uri']; ?>/<? echo $item1['cat_uri']; ?>/<? echo $item1['uri']; ?>.html">

								  <? if ($item1['is_sale'] > 0): ?>

									<span class="sale"></span>

									<? elseif($item1['is_new'] > 0): ?>

                                    <span class="new"></span>

									<? endif; ?>

                                                                        <span class="image">

                                                                                <img src="<? echo normalize_media_url($HTTP.'pub/products/'.$item1['id'].'/180x190/'.$item1['image']); ?>" alt="<? echo htmlspecialchars($item1['title']); ?>">

                                                                        </span>

									<span class="name_product">

										<? echo $item1['title'].' '.$item1['brand']; ?>

									</span>

									<? if ($item1['discount'] > 0): ?>

									<del>
                                        <?php if ( $ip_country == 'Russian Federation' ):
                                            echo get_price_in_rus_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar, $item1['rouble_currency'], $sd_rur ); ?> руб.</br>
                                        <?php else:
                                            echo get_price_in_bel_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                                        <?php endif; ?>
									</del>

									<span class="price">
                                        <?php if ( $ip_country == 'Russian Federation' ):
                                            echo get_price_in_rus_rub( $item1['discount'], $item1['dollar_currency'], $sd_dollar, $item1['rouble_currency'], $sd_rur ); ?> руб.</br>
                                        <?php else:
                                            echo get_price_in_bel_rub( $item1['discount'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
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
                                            <?php if ($ip_country == 'Russian Federation'):
                                                echo get_price_in_rus_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar, $item1['rouble_currency'], $sd_rur ); ?> руб.</br>
                                            <?php else:
                                                echo get_price_in_bel_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                                            <?php endif; ?>
										<?php else: ?>
											<span class="soon">Скоро в продаже</span>
										<?php endif; ?>
									</span>
<?/*
                                    <?php if ( $ip_country != 'Russian Federation' ): ?>
                                        <span class="old-currency">
                                             <?php echo get_price_in_old_bel_rub( $item1['price'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                                        </span>
                                    <?php endif; ?>*/?>

									<? endif; ?>

								</a>

							</li>

				<? endforeach; ?>

            </ul>

			<? endif; ?>

        <div class="row">



    
        </div>

    </div>

</div>



