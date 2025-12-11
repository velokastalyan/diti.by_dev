<div itemscope itemtype="http://schema.org/WebPage">
<ul itemprop="breadcrumb" class="bread_crumbs" xmlns="http://www.w3.org/1999/xhtml">
    <li>
        <a href="<?php echo $HTTP; ?>">Главная</a>
    </li>
<li>
    <?php $uri_3 = explode(" / ", $product_arr[1]['c_parent_path']); ?>
    <a href="<?php echo $HTTP; ?><?php echo $c1_uri; ?>/"><?php echo $uri_3[0]; ?></a></li>
    <?php if (!empty( $uri_3[1])): ?>
        <li><a href="<?php echo $HTTP; ?><?php echo $c1_uri; ?>/<?php echo $c2_uri; ?>"><?php echo $uri_3[1]; ?></a></li>
    <?php endif; ?>
    <?php if($c2_uri!=='snoubord-deki'): ?>
    <li><a href="<?php echo $HTTP; ?><?php echo $c1_uri; ?>/<?php echo $c2_uri; ?>/<?php echo $c3_uri; ?>"><?php echo $product_arr[1]['c_title']; ?></a></li>
    <?php endif; ?>
    <li>
</li>
</ul>
</div>
    <?php if ($product_found): ?>
        <div class="row" itemscope itemtype="http://schema.org/Product">
        <div class="twelve columns">
        <meta itemprop="name" content="<?php echo $product_arr[1]['title']; ?>">
        <meta itemprop="brand" content="<?php echo $product_arr[1]['b_title']; ?>">
        <meta itemprop="logo" content="<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/180x190/".$product_arr[1]['i_image_filename']; ?>">
        <h1 class="heading"><?php echo $product_arr[1]['title']; ?></h1>
        <div class="white_block indent">

            <div class="row">
                <div class="twelve columns">
                    <div class="product_full_info">

                        <div class="product row">
                            <div class="five columns">
                                <!-- gallery -->
                                <div class="gallery_custom">
                                    <div class="large_img">
                                        <?php if ($product_arr[1]['is_sale'] > 0): ?>
                                        <span class="sale"></span>
                                        <?php elseif($product_arr[1]['is_new'] > 0): ?>
                                        <span class="new"></span>
                                        <?php endif; ?>
                                        <?php 

                                        $file_exists = file_exists(ROOT.'pub/products/'.$product_arr[1]['id']."/300x340/".$product_arr[1]['i_image_filename']);
                                        
                                        if (!$file_exists)
                                        $img_size = getimagesize($HTTP.'pub/products/'.$product_arr[1]['id']."/260x340/".$product_arr[1]['i_image_filename']);
                                        else 
                                        $img_size = getimagesize($HTTP.'pub/products/'.$product_arr[1]['id']."/300x340/".$product_arr[1]['i_image_filename']);
                                        $img_size_full = getimagesize($HTTP.'pub/products/'.$product_arr[1]['id']."/".$product_arr[1]['i_image_filename']);
                                        ?>
                                        <?php $xx = 383 - (($img_size[0] > 300) ? 300 : $img_size[0]) - 55; ?>
                                            
                                            <?php if (!$file_exists): ?>
                                                <img itemprop="image" class="cloudzoom" alt ="<?php echo htmlspecialchars($product_arr[1]['title']); ?>" style="max-height: 340px;" id ="zoom1" src="<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/260x340/".$product_arr[1]['i_image_filename']; ?>"
                                                 data-cloudzoom='
                                                 zoomImage:"<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/".$product_arr[1]['i_image_filename']; ?>",
                                                 zoomMatchSize:true,
                                                 tintColor:"#000",
                                                 tintOpacity:0.25,
                                                 captionPosition:"bottom",
                                                 maxMagnification:4
                                                 '>
                                            <?php else: ?>
                                                <img itemprop="image" class="cloudzoom" alt ="<?php echo htmlspecialchars($product_arr[1]['title']); ?>" style="max-height: 340px;" id ="zoom1" src="<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/300x340/".$product_arr[1]['i_image_filename']; ?>"
                                                 data-cloudzoom='
                                                 zoomImage:"<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/".$product_arr[1]['i_image_filename']; ?>",
                                                 zoomMatchSize:true,
                                                 tintColor:"#000",
                                                 tintOpacity:0.25,
                                                 captionPosition:"bottom",
                                                 maxMagnification:4
                                                 '>
                                            <?php endif; ?>

                                            
                                            <?php $count = 1;
                                            foreach($image_arr as $item): ?>
                                            <?php if ($item['image_filename'] != $product_arr[1]['i_image_filename']): ?>
                                            <a style="display: none;" href="<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/".$item['image_filename']; ?>"><img itemprop="image" class="gallery_img" src="<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/79x79/".$item['image_filename']; ?>" alt="<?php echo htmlspecialchars($product_arr[1]['title']); echo ' '.$count;  ?>" /></a>
                                            <?php $count++;
                                                endif; ?>
                                        <?php endforeach; ?>
                                        
                                    </div>

                                    <?php if ($image_found): ?>
                                    <div class="thumbnail">
                                    <ul id="thumbnail_ul">
                                    <?php $count = 1;
                                        foreach($image_arr as $item): ?>
                                        <li>
                                            <div class="small_img">
                                            <a href="<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/".$item['image_filename']; ?>" class="thumb-link">
                                                <img itemprop="image" class="cloudzoom-gallery" src="<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/79x79/".$item['image_filename']; ?>"
                                                     alt ="<?php echo htmlspecialchars($product_arr[1]['title']); echo ' '.$count;  ?>"
                                                     data-cloudzoom='{
                                                     "useZoom":"#zoom1",
                                                     "image":"<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/260x340/".$item['image_filename']; ?>",
                                                     "zoomImage":"<?php echo $HTTP; ?>pub/products/<?php echo $product_arr[1]['id']."/".$item['image_filename']; ?>"}'>
                                            </a>
                                            </div>
                                        </li>
                                    <?php  $count++;
                                        endforeach; ?>
                                    </ul>
                                    </div>
                                    <?php endif; ?>
                                    </div>
                                <!-- /gallery -->
                            </div>
                            <div class="seven columns">

                                <div class="top_product" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <?php if (strlen($product_arr[1]['b_image_filename'])): ?>
                                        <?php if ($product_arr[1]['b_link'] != ''): ?>
                                            <a href="<?php echo $product_arr[1]['b_link']; ?>" rel="nofollow" target="_blank">
                                        <?php endif; ?>
                                        <img src="<?php echo $HTTP.'pub/brands/'.$product_arr[1]['b_id'].'/'.$product_arr[1]['b_image_filename']; ?>" alt="<?php echo htmlspecialchars($product_arr[1]['b_title']); ?>" class="product_company">
                                        <?php if ($product_arr[1]['b_link'] != ''): ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <span class="code_product">Код товара: <?php echo $product_arr[1]['id']; ?></span>
                                    <?php if($product_arr[1]['status']==0): ?>
                                    <link itemprop="availability" href="http://schema.org/InStock"><span class="label yes"><?php CTemplate::loc_string('in_stock'); ?></span>
                                    <?php elseif($product_arr[1]['status']==1): ?>
                                    <link itemprop="availability" href="http://schema.org/OutOfStock"><span class="label no"><?php CTemplate::loc_string('not_available'); ?></span>
                                    <?php elseif($product_arr[1]['status']==2): ?>
                                    <link itemprop="availability" href="http://schema.org/PreOrder"><span class="label order">Доставка в 2-3 дня</span>
                                    <?php elseif($product_arr[1]['status']==3): ?>
                                    <link itemprop="availability" href="http://schema.org/PreOrder"><span class="label order">Доставка в 5-7 дней</span>
                                    <?php elseif($product_arr[1]['status']==4): ?>
                                    <link itemprop="availability" href="http://schema.org/PreOrder"><span class="label order">Доставка в 3-4 недели</span>
                                    <?php endif; ?>
                                    <hr class="separator">
                                    <?php if ($product_arr[1]['discount'] > 0): ?>
                                    <del>
                                        <?php if ($ip_country == 'Russian Federation'):
                                            echo get_price_in_rus_rub( $product_arr[1]['price'], $product_arr[1]['dollar_currency'], $sd_dollar, $product_arr[1]['rouble_currency'], $sd_rur ); ?> руб.
                                        <?php else:
                                            echo get_price_in_bel_rub( $product_arr[1]['price'], $product_arr[1]['dollar_currency'], $sd_dollar ); ?> BYN
                                        <?php endif; ?>
                                    </del><br/>
                                    <span class="price">
                                            <?php echo get_price_in_bel_rub( $product_arr[1]['discount'], $product_arr[1]['dollar_currency'], $sd_dollar ); ?> BYN<br>
                                        <?/* <?php if ( $ip_country != 'Russian Federation' ): ?>
                                       <span class="old-currency">
                                             <?php echo get_price_in_old_bel_rub( $product_arr[1]['discount'], $product_arr[1]['dollar_currency'], $sd_dollar ); ?> BYN
                                        </span> 
                                        <?php endif; ?>*/?>
                                            <span class="reviews" style="display:none;">

                                           <!-- <span itemprop="price"><?php echo number_format($product_arr[1]['discount'], 0, ',', ' '); ?></span> <span itemprop="priceCurrency">USD</span><br> -->

                                            <?php if ($ip_country == 'Russian Federation'): ?>
                                                <?php echo get_price_in_bel_rub( $product_arr[1]['discount'], $product_arr[1]['dollar_currency'], $sd_dollar ); ?> BYN

                                            <?php else: ?>
                                                <?php echo get_price_in_rus_rub( $product_arr[1]['discount'], $product_arr[1]['dollar_currency'], $sd_dollar, $product_arr[1]['rouble_currency'], $sd_rur ); ?> RUB
                                            <?php endif; ?>
                                            </span>
                                    </span>

                                    <?php else: ?>
                                    <span class="price">
                                        <?php if ($product_arr[1]['price'] != 0): ?>

                                        <?php if ($ip_country == 'Russian Federation'): ?>
                                            <?php echo get_price_in_rus_rub( $product_arr[1]['price'], $product_arr[1]['dollar_currency'], $sd_dollar, $product_arr[1]['rouble_currency'], $sd_rur ); ?> BYN<br>
                                            <?php else: ?>
                                            <?php echo get_price_in_bel_rub( $product_arr[1]['price'], $product_arr[1]['dollar_currency'], $sd_dollar ); ?> BYN<br>
                                            <?/*-<span class="old-currency">
                                             <?php echo get_price_in_old_bel_rub( $product_arr[1]['price'], $product_arr[1]['dollar_currency'], $sd_dollar ); ?> BYN
                                        </span>*/?>
                                        <?php endif; ?>
                                            <span class="reviews" style="display:none;">

                                            <!--<span itemprop="price"><?php echo number_format($product_arr[1]['price'], 0, ',', ' '); ?></span> <span itemprop="priceCurrency">USD</span><br>-->

                                            <?php if ($ip_country == 'Russian Federation'): ?>
                                                <?php echo get_price_in_bel_rub( $product_arr[1]['price'], $product_arr[1]['dollar_currency'], $sd_dollar ); ?> BYN

                                            <?php else: ?>
                                                <?php echo get_price_in_rus_rub( $product_arr[1]['price'], $product_arr[1]['dollar_currency'], $sd_dollar, $product_arr[1]['rouble_currency'], $sd_rur ); ?> RUB
                                            <?php endif; ?>
                                            
                                            </span>
                                        <?php else: ?>
                                            <span class="soon">Скоро в продаже</span>
                                        <?php endif; ?>
                                    </span>
                                    <?php endif; ?>
                                    <div class="block_reviews" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                        <div class="star_reviews">
                                            <?php  $stars_max = 5;
                                                $stars_enable = $product_arr[1]['rate'];
                                                $stars_disable = $stars_max - $stars_enable;
                                            ?>
                                            <meta itemprop="ratingValue" content="<?php echo 5-$stars_disable ?>">
                                            <?php for ($i = 0; $i < $stars_enable; $i++): ?>
                                            <img src="<?php echo $HTTP; ?>images/star_enable.png" alt="star">
                                            <?php endfor; ?>
                                            <?php if ($stars_disable > 0): ?>
                                            <?php for ($i = 0; $i < $stars_disable; $i++): ?>
                                                <img src="<?php echo $HTTP; ?>images/star_disable.png" alt="star">
                                            <?php endfor; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="reviews">
                                            <a href="javascript: void(0);" class="reviews_all"><?php if ($comment_found): echo '<span itemprop="reviewCount">'.count($comment_arr).'</span>'; ?> отзывов<?php else: echo "Отзывов нет<meta itemprop='reviewCount' content='0'>"; endif; ?></a> | <a href="javascript: void(0);" class="add_review">Поделись своим мнением</a>
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <span class="labels">
                                    <?php if (intval($product_arr[1]['is_delivery_minsk'])): ?>
                                        <a href="#" class="orange normalTip" title="<?php if (strlen($product_arr[1]['description_delivery_minsk'])) echo $product_arr[1]['description_delivery_minsk']; else echo 'Данный товар доставляется по Минску бесплатно'; ?>">Доставка по Минску</a>
                                    <?php endif; ?>
                                    <?php if (intval($product_arr[1]['is_delivery_moscow'])): ?>
                                        <a href="#" class="red normalTip" title="<?php if (strlen($product_arr[1]['description_delivery_moscow'])) echo $product_arr[1]['description_delivery_moscow']; else echo 'Пункт выдачи на рынке Садовод. Максимальная стоимость доставки $20.'; ?>">Доставка в Москву</a>
                                    <?php endif; ?>
                                    <?php if (intval($product_arr[1]['is_delivery_belarus'])): ?>
                                        <a href="#" class="green normalTip" title="<?php if (strlen($product_arr[1]['description_delivery_belarus'])) echo $product_arr[1]['description_delivery_belarus']; else echo 'Доставка по всей Беларуси курьерской службой в течении 2 дней. Приблизительная стоимость доставки 100 - 200 тыс. бел. рублей.'; ?>">Доставка по Беларуси</a>
                                    <?php endif; ?>
                                    <?php if(intval($product_arr[1]['service'])==1): ?>
                                        <a href="#" class="service normalTip" title="<?php if (strlen($product_arr[1]['description_service'])) echo $product_arr[1]['description_service']; else echo 'Сервиса нет' ?>">Доставка по Беларуси</a>
                                        <?php endif; ?>
                                    </span>
                                    
                                    <div class="product_actions">
                                        <?php if ( !empty( $uri_3[1] ) && $uri_3[1] === 'Велосипеды' || $c2_uri === 'velosipedyi' ): ?>
                                            <a class="modalbox" href="<?= $HTTP ?>images/velo-size.jpg">Подбор размера рамы</a><br>
                                        <?php endif ?>
                                        <?php if ($product_arr[1]['price'] > 0): ?>
                                            <input class="countProduct" type="text" value="1">
                                            <a href="#" class="button" id="add_to_cart" data-rel="<?php echo $product_arr[1]['id']; ?>">Положить в корзину <span class="basket_ico"></span></a>
                                            <a href="<?php echo $HTTP; ?>shopping-cart.html" class="button" id="go_to_cart" style="display: none">Перейти в корзину</a><br />
                                        <?php endif; ?>
                                        <a href="#ConsultationBox" class="modalbox">Купить в один клик</a>
                                        <span class="separator">|</span>
                                        <a href="#" id="add_cookie" data-rel="<?php echo $product_arr[1]['id']; ?>">Отложить на потом</a>
                                        <div id="message_window" style="display: none"></div>
                                    </div>
                                    
                                <?php if(!empty($product_arr[1]['size_arr'])): ?>
                                    <span class="heading_two"><?php CTemplate::loc_string('size_arr'); ?></span>
                                    <div class="product_size">
                                        <?php echo $product_arr[1]['size_arr']; ?>
                                    </div>
                                <?php endif; ?>

                                <span class="heading_two">Описание</span>
                                <div itemprop="description"><?php echo $product_arr[1]['description']; ?></div>
                                <div class="video-review">
                                <?php if ($product_arr[1]['video'] != ''): ?>
                                <?php $product_arr[1]['video'] = str_replace(array('<p>', '</p>'), '', $product_arr[1]['video']);?>
                                    <span class="heading_two">Видео</span>
                                <?php if(substr($product_arr[1]['video'], 0, 7)=='http://'): ?>
                                    <iframe height="315" src="http://www.youtube.com/embed/<?php parse_str(str_replace("?", "&",$product_arr[1]['video'])); echo $v; ?>?enablejsapi=1'" allowfullscreen ></iframe>
                                <?php else: ?>
                                    <?= $product_arr[1]['video'] ?>
                                <?php endif; ?>
                                <?php endif; ?>
                                </div>
                                <!-- AddThis Button BEGIN -->
                                <div class="addthis_toolbox addthis_default_style ">
                                    <a class="addthis_button_facebook"></a>
                                    <a class="addthis_button_twitter"></a>
                                    <a class="addthis_button_email"></a>
                                    <a class="addthis_button_compact"></a>
                                    <a class="addthis_counter addthis_bubble_style" style="width: 36px !important;"></a>
                                </div>
                                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-512da75618ed4707"></script>
                                <!-- AddThis Button END -->

                                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="line-dopinfo-prod">Изображения товара, включая цвет, могут отличаться от реального внешнего вида. Комплектация также может быть изменена производителем без предварительного уведомления. Описание не является публичной офертой. Убедительно просим Вас при выборе модели уточнять наличие желаемых функций и характеристик у наших консультантов.</div>
        </div><!-- block white -->

    <?php endif; ?>

    <?php if ($recommend_found): $i = 0; ?>
        <div class="row">
            <div class="twelve columns see_also">
                <span class="heading">Смотрите так же</span>
                    <ul class="list_products">
                        <?php foreach($recommend_arr as $item): ?>
                            <li>
                                <a href="<?php echo $HTTP.$item['category'].'/'.$item['uri']; ?>.html">
                                    <span class="image">
                                        <img src="<?php echo $HTTP; ?>pub/products/<?php echo $item['id'].'/180x190/'.$item['image_filename']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                    </span>
                                    <span class="name_product">
                                        <?php echo $item['brand']; ?> / <?php echo $item['title']; ?>
                                    </span>
                                    <?php if (($item['price'] > 0) || ($item['discount'] > 0)): ?>
                                        <?php if ($item['discount'] > 0): ?>
                                            <del>
                                                <?php if ($ip_country == 'Russian Federation'):
                                                    echo get_price_in_rus_rub( $item['price'], $item['dollar_currency'], $sd_dollar, $item['rouble_currency'], $sd_rur ); ?> руб.</br>
                                                <?php else:
                                                    echo get_price_in_bel_rub( $item['price'], $item['dollar_currency'], $sd_dollar ); ?> BYN
                                                <?php endif; ?>
                                            </del>
                                            <span class="price">
                                                <?php if ($ip_country == 'Russian Federation'):
                                                    echo get_price_in_rus_rub( $item['discount'], $item['dollar_currency'], $sd_dollar, $item['rouble_currency'], $sd_rur ); ?> BYN</br>
                                                <?php else:
                                                    echo get_price_in_bel_rub( $item['discount'], $item['dollar_currency'], $sd_dollar ); ?> BYN
                                                <?php endif; ?>
                                            </span>
                                    <?php if ( $ip_country != 'Russian Federation' ): ?>
                                       <?/* <span class="old-currency">
                                             <?php echo get_price_in_old_bel_rub( $item['discount'], $item['dollar_currency'], $sd_dollar ); ?> BYN
                                        </span>*/?>
                                    <?php endif; ?>
                                        <?php else: ?>
                                            <span class="price">
                                                <?php if ($ip_country == 'Russian Federation'):
                                                    echo get_price_in_rus_rub( $item['price'], $item['dollar_currency'], $sd_dollar, $item['rouble_currency'], $sd_rur ); ?> руб.</br>
                                                <?php else:
                                                    echo get_price_in_bel_rub( $item['price'], $item['dollar_currency'], $sd_dollar ); ?> BYN
                                                <?php endif; ?>
                                            </span>
                                    <?php if ( $ip_country != 'Russian Federation' ): ?>
                                        <?/*<span class="old-currency">
                                             <?php echo get_price_in_old_bel_rub( $item['price'], $item['dollar_currency'], $sd_dollar ); ?> BYN
                                        </span>*/?>
                                    <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php $i++; if ($i >= 4) break; ?>
                        <?php endforeach; ?>
                    </ul>
            </div>
        </div>
    <?php endif; ?>


        <div class="row">
            <div class="twelve columns reviews-list">
                <span class="heading">Отзывы</span>

                <div class="reviews_message" id="reviews_message">
                    <?php if ($comment_found): ?>
                        <span class="arrow"></span>
                        <?php foreach($comment_arr as $item): ?>
                            <div class="message_item" itemprop="review" itemscope itemtype="http://schema.org/Review">

                                <div class="star_reviews">
                                    <?php  $stars_max = 5; //Звезд может быть всего
                                        $stars_enable = $item['rate']; //Поставленно звезд
                                        $stars_disable = $stars_max - $stars_enable; //Звезд не активных

                                    for ($i = 0; $i < $stars_enable; $i++): ?>
                                        <img src="<?php echo $HTTP; ?>images/star_enable.png" alt="star">
                                        <?php endfor; ?>
                                    <?php if ($stars_disable > 0)

                                    for ($i = 0; $i < $stars_disable; $i++): ?>
                                        <img src="<?php echo $HTTP; ?>images/star_disable.png" alt="star">
                                        <?php endfor; ?>
                                </div>
                                <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                                    <meta itemprop="worstRating" content = "1">
                                    <meta itemprop="bestRating" content = "5">
                                    <meta itemprop="ratingValue" content = "<?php echo 5-$stars_disable ?>">
                                </div>
                                <span class="heading" itemprop="name"><?php echo $item['title']; ?></span>
                                <p itemprop="description"><?php echo $item['description']; ?></p>
                                <span class="author" itemprop="author"><?php echo $item['name']; ?></span>
                                <?php if ($item['answer']): ?>
                                <div class="answer"><?php echo $item['answer']; ?>
                                    <p class="author" itemprop="author">- Администратор</p></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div><!-- review -->

                <!-- -->
                <div class="white_block">
                    <div id="scroll-add-review">&nbsp;</div>
                    <p class="t-center" id="box-btn">
                        <span class="button medium add_review" id="add_review_button">Поделись своим мнением</span>
                    </p>

                    <div class="review_form" id="review_form">
                        <?php if($_errors && !empty($_errors)): ?>
                            <script type="text/javascript">showAddReview = true;</script>
                        <?php endif; ?>
                        <span class="heading_two">Написать отзыв</span>
                        <?php CForm::begin('add_comment', 'post', 'http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI], 'f-form'); ?>
                        <?php if ($comment_added): ?>
                            <script type="text/javascript">showAddReview = true;</script>
                            <div align="center" style="margin: 20px auto;">Спасибо за ваше сообщение, оно будет опубликовано на сайте после прохождения модерации!</div>
                        <?php endif; ?>
                            <div class="row">
                                <div class="two columns">
                                    <label for="name">Ваше имя</label>
                                </div>
                                <div class="ten columns">
                                    <?php CTemplate::input('text', 'name', 'name', 'input_style'); ?>
                                    <?php if (strlen($error_name)): ?><span class="error">Введите имя!</span><?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="two columns">
                                    <label for="title">Заголовок</label>
                                </div>
                                <div class="ten columns">
                                    <?php CTemplate::input('text', 'title', 'title', 'input_style'); ?>
                                    <?php if (strlen($error_title)): ?><span class="error">Введите заголовок!</span><?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <script type="text/javascript">var select = "rate";</script>
                                <div class="two columns">
                                    Оценка
                                </div>
                                <div class="rate ten columns">
                                    <?php //CInput::set_select_data('review_ratio', 'review_ratio'); ?>
                                    <?php CTemplate::select_rate('rate', 'rate'); ?>
                                    <?php if (strlen($error_rate)): ?><span class="error">Выберите рейтинг!</span><?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="two columns">
                                    <label for="description">Отзыв</label>
                                </div>
                                <div class="ten columns">
                                    <?php CTemplate::textarea('description', 'description', 'textarea_style'); ?>
                                    <?php if (strlen($error_text)): ?><span class="error">Введите текст!</span><?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="two columns">
                                    <label for="answer">Сложите</label>
                                </div>
                                <div class="ten columns">
                                    <?php CTemplate::input('text', 'answer', 'answer', 'input_style'); ?>
                                    <?php if (strlen($error_answer)): ?><span class="error">Неправильный ответ!</span><?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="two columns"></div>
                                <div class="ten columns">
                                    <a href="javascript: $('#add_comment').submit();" class="button medium send">Отправить</a>
                                </div>
                            </div>
                        <?php CForm::end(); ?>
                    </div><!-- review_form -->
                </div>
                <!-- -->


            </div>
        </div>

        </div>
    </div>


<div style="display: none">
    <div id="ConsultationBox" class="consultation">

        <p class="title">Оставьте свой номер телефона, менеджер свяжется с вами в ближайшее время.</p>

        <?php CForm::begin('consultation', 'POST', 'http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI], 'form', array('enctype' => 'multipart/form-data')); ?>
        <div class="row">
            <div class="four columns">
                <label for="f_phone">Телефон</label>
            </div>
            <div class="eight columns">
                <input id="f_phone" name="phone" type="text" value="<?php echo $r_phone; ?>" <?php if ($error_phone) echo 'class="error"'; ?>>
            </div>
        </div>
        <div class="row">
            <div class="eight columns offset-by-four">
                <!--<a class="button medium" onclick="javascript: submit();">Отправить</a>-->
                <input class="button medium" value="Отправить" type="submit">
            </div>
        </div>
        <?php CForm::end(); ?>

    </div>
</div>
