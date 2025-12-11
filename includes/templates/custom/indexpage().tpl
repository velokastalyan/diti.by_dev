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

							<img class="rsImg" src="<? echo $HTTP; ?>pub/banners/<? echo $item1['id']; ?>/<? echo $item1['image_filename']; ?>" alt="" />

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

										<img src="<? echo $HTTP; ?>pub/products/<? echo $item1['id']; ?>/180x190/<? echo $item1['image']; ?>" alt="<? echo htmlspecialchars($item1['title']); ?>">

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
									</span>
                                    <?php if ( $ip_country != 'Russian Federation' ): ?>
                                        <span class="old-currency">
                                             <?php echo get_price_in_old_bel_rub( $item1['discount'], $item1['dollar_currency'], $sd_dollar ); ?> BYN
                                        </span>
                                    <?php endif; ?>

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

        <div class="row">

            <div class="nine columns">

                <span class="heading">Видосы</span>

                <!-- Start newslider-vertical -->

                <div class="sliderkit newslider-vertical">

                    <div class="sliderkit-nav">

                        <div class="sliderkit-nav-clip">

                            <ul>

								<? if ($vd_found): ?><? $k = 0; ?>

									<? foreach($vd_title as $val ): ?>

										<li class="tab" id="<? echo $k; $k++; ?>"><a href="<? echo $HTTP; ?>#"><? echo $val; ?></a></li>

									<? endforeach; ?>



                            </ul>

                        </div>

                    </div>

                    <div class="sliderkit-panels"><? $k = 0; ?>

                        <? foreach($vd_link as $val ): ?>

							<div class="sliderkit-panel">

								<div class="sliderkit-news">

									<iframe id="v<? echo $k; $k++; ?>" src="http://www.youtube.com/embed/<? parse_str(str_replace("?", "&",$val)); echo $v; ?>?enablejsapi=1'" allowfullscreen ></iframe>

								</div>

							</div>

						<? endforeach; ?>



								<? endif;  ?>

                    </div>

                </div>

                <!-- // end of newslider-vertical -->

            </div>

            <div class="three columns">

                <span class="heading">Новости</span>

                <? if ($news_found): ?>

					<ul class="news">

						<? foreach($news_arr as $item1): ?>

									<li>

										<a href="<? echo $HTTP; ?>news/<? echo $item1['uri']; ?>.html">

											<span class="date"><? echo date('d / m / Y', strtotime($item1['public_date'])); ?></span>

													<span class="text">

														<? $str1= mb_substr(strip_tags($item1['description']), 0, 149, 'UTF-8')."...";

														echo $str1; ?>

													</span>

										</a>

									</li>

						<? endforeach; ?>

					</ul>

				<? endif; ?>

                <a href="<? echo $HTTP; ?>news.html" class="all_news">все новости &raquo;</a>

            </div>

        </div>

    </div>

</div>



