<?php $ip_country = 'Belarus' ?>
<?php if ($static_page == 'otlozhennoe'): ?>
<section id="main">
    <div itemscope itemtype="http://schema.org/WebPage">
    <ul class="bread_crumbs" itemprop="breadcrumb">
        <li><a href="<?php echo $HTTP; ?>">Главная</a></li>
        <li><?php echo $page_arr[1]['title']; ?></li>
    </ul>
    </div>
    <div class="row">
        <div class="twelve columns">
            <span class="heading">Отложенные товары</span>
            <div class="white_block">
                <div class="products">
                    <?php if ($otlozh_found == true): ?>
                    <?php foreach ($otlozh_arr as $key=>$val): ?>
                    <div class="row_item" id="r_<?php echo $val['id'] ?>">
                        <table>
                            <tbody>
                            <tr class="item_product">
                                <td class="img">
                                    <img src="<?php echo $HTTP; ?>pub/products/<?php echo $val['id']."/79x79/".$val['image_filename']; ?>" alt="">
                                </td>
                                <td class="description">
                                    <?php if (!empty($val['c_parent_path_uri'])): ?>
                                        <h2><a href="<?php echo $HTTP.$val['c_parent_path_uri'].'/'.$val['c_parent_uri'].'/'.$val['category_uri'].'/'.$val['uri'].'.html'; ?>"><?php echo $val['title']; ?></a></h2>
                                    <?php else: ?>
                                        <h2><a href="<?php echo $HTTP.$val['c_parent_uri'].'/'.$val['category_uri'].'/'.$val['uri'].'.html'; ?>"><?php echo $val['title']; ?></a></h2>
                                    <?php endif; ?>
                                    <span class="code">Код товара: <?php echo $val['id']; ?></span>
                                    <?php if (!empty($val['c_parent_path_uri'])): ?>
                                        <p class="links"><a href="<?php echo $HTTP.$val['c_parent_path_uri']; ?>"><?php echo $val['c_parent_path_title'] ?></a><a href="<?php echo $HTTP.$val['c_parent_path_uri'].'/'.$val['c_parent_uri']; ?>"><?php echo $val['c_parent_title'] ?></a><a href="<?php echo $HTTP.$val['c_parent_path_uri'].'/'.$val['c_parent_uri'].'/'.$val['category_uri'] ?>"><?php echo $val['category_title'] ?></a></p>
                                    <?php else: ?>
                                    <p class="links"><a href="<?php echo $HTTP.$val['c_parent_uri']; ?>"><?php echo $val['c_parent_title'] ?></a><a href="<?php echo $HTTP.$val['c_parent_uri'].'/'.$val['category_uri'] ?>"><?php echo $val['category_title'] ?></a></p>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($val['price']==0): ?>
                                        <span class="price">Скоро в продаже</span>
                                    <?php elseif ($ip_country == 'Belarus'): ?>
                                        <span class="price"><?php echo get_price_in_bel_rub( $val['price'], $val['dollar_currency'], $sd_dollar ); ?> BYN</span>
                                    <?php elseif ($ip_country == 'Russian Federation'): ?>
                                        <span class="price"><?php echo get_price_in_rus_rub( $val['price'], $val['dollar_currency'], $sd_dollar, $val['rouble_currency'], $sd_rur ); ?> руб.</span>
                                    <?php else: ?>
                                        <span class="price"><?php echo '$'.$val['price']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                   <!-- <a href="<?php echo $HTTP.'otlozhennoe.html?delete='.$val['id']; ?>" class="btn_close"></a>-->
                                   <a href="#" id="<?php echo $val['id'] ?>" class="btn_close"></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!-- row_item -->
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="no_product">Отложенных товаров нет!</div>
                    <?php endif; ?>
                </div>

            </div><!-- block white -->
        </div>
    </div>
    <?php elseif ($static_page == 'subscribe'): ?>
<section id="main">
        <ul class="bread_crumbs">
            <li><a href="<?php echo $HTTP; ?>">Главная</a></li>
            <li><?php echo $page_arr[1]['title']; ?></li>
        </ul>

        <div class="row">
            <div class="twelve columns">
                <h1 class="heading"><?php echo $page_arr[1]['title']; ?></h1>
                <div class="white_block">


                    <div class="review_form">
                        <?php if ($subscriber_good) : ?>
                        <p class="message">Спасибо! Ваш E-mail  успешно добавлен в базу рассылки</p>
                        <?php elseif ($_errors): ?>
                            <p class="message">Ваш E-mail уже добавлен в базу рассылки</p>
                        <?php else: ?>
                            <?php CForm::begin('distribution', 'POST', '#', 'distribution'); ?>
                            <div class="line_form">
                                <div class="row">
                                    <div class="two columns">
                                    <label>Email</label>
                                    </div>
                                    <div class="ten columns">
                                    <input type="text" name="email" class="input_style<?php if ($error_email): ?> error_input<?php endif; ?>" value="<?php echo $user_email; ?>">
                                    <?php if ($error_email): ?><div class="error">неверный формат</div><?php endif; ?>
                                    </div>
                                </div>

                                <div class="row">
                                     <div class="two columns">
                                         <label>Сумма <?php echo $number_capthca1; ?>+<?php echo $number_capthca2 ?>=</label>
                                 </div>
                                     <div class="ten columns">
                                           <input type="text" name="captcha" class="input_style <?php if ($error_captcha): ?>error_input<?php endif; ?> capcha">
                                         <?php  if ($error_captcha): ?><div class="error">неверная сумма</div><?php endif; ?>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="two columns"></div>
                                    <div class="ten columns">
                                        <!-- <a onclick="javascript:document.getElementById('distribution').submit();" class="button medium send">Подписаться</a>-->
                                                  <a href="javascript: $('#distribution').submit();" class="button medium send">Подписаться</a>
                                     </div>
                                 </div>
                             </div>
                        <?php CForm::end(); ?>
                        <?php endif; ?>
                            <p class="message">Если Вы решили отписаться от подписки, отправьте e-mail администратору.</p>
                 </div>
             </div>
         </div>
        </div>
 <?php elseif ($static_page == 'thank'): ?>
     <section id="main">
         <ul class="bread_crumbs">
             <li>
                 <a href="<?php echo $HTTP; ?>">Главная</a>
             </li>
             <li>
                 Консультация
             </li>
         </ul>
         <div class="row">
             <div class="twelve columns">
                 <h1 class="heading">Получить консультацию</h1>

                 <div class="white_block">
                     <div class="row">
                         <div class="twelve columns">

                             <div class="thank_you">
                                 <span class="title">Спасибо!</span>
                                 <p class="title medium">Ваш запрос успешно отправлен.</p>
                                 <p>Наш менеджер свяжется с вами в ближайшее время.</p>
                             </div>

                         </div>
                     </div>
                 </div>
             </div>
         </div>
 <?php elseif ($static_page == 'success'): ?>
     <section id="main">
         <ul class="bread_crumbs">
             <li>
                 <a href="<?php echo $HTTP; ?>">Главная</a>
             </li>
             <li>
                 Успешная оплата
             </li>
         </ul>
         <div class="row">
             <div class="twelve columns">
                 <div class="white_block">
                     <div class="row">
                         <div class="twelve columns">

                             <div class="thank_you">
                                 <span class="title">Спасибо!</span>
                                 <p class="title medium">Вы успешно оплатили заказ.</p>
                             </div>

                         </div>
                     </div>
                 </div>
             </div>
         </div>
<?php elseif ($static_page == 'fail'): ?>
     <section id="main">
         <ul class="bread_crumbs">
             <li>
                 <a href="<?php echo $HTTP; ?>">Главная</a>
             </li>
             <li>
                 Неудачная оплата
             </li>
         </ul>
         <div class="row">
             <div class="twelve columns">
                 <div class="white_block">
                     <div class="row">
                         <div class="twelve columns">

                             <div class="thank_you">
                                 <p class="title medium">Неуспешная оплата заказа. Попробуйте еще раз.</p>
                             </div>

                         </div>
                     </div>
                 </div>
             </div>
         </div>
<?php elseif($static_page == 'shopping-cart'): ?>
	 <section id="main">
		 <ul class="bread_crumbs">
			 <li><a href="<?php echo $HTTP; ?>">Главная</a></li>
			 <li><?php echo $page_arr[1]['title']; ?></li>
		 </ul>
		 <div class="row">
			 <div class="twelve columns">
				 <span class="heading">Ваша корзина</span>
				 <div class="white_block" id="shopping-cart">
					 <div class="products <?php echo (($status_ok && !$cart_arr) ? 'status_ok' : null); ?>">
						 <?php if ($cart_arr): $total = 0; ?>
							 <?php CForm::begin('order', 'post'); ?>
								 <?php foreach ($cart_arr as $key => $val): ?>
									 <div class="row_item" id="r_<?php echo $val['id'] ?>">
										 <table>
											 <tbody>
											 <tr class="item_product">
												 <td class="img">
													 <img src="<?php echo $HTTP; ?>pub/products/<?php echo $val['id']."/79x79/".$val['image_filename']; ?>" alt="">
												 </td>
												 <td class="description">
													 <?php if (!empty($val['c_parent_path_uri'])): ?>
														 <h2><a href="<?php echo $HTTP.$val['c_parent_path_uri'].'/'.$val['c_parent_uri'].'/'.$val['category_uri'].'/'.$val['uri'].'.html'; ?>"><?php echo $val['title']; ?></a></h2>
													 <?php else: ?>
														 <h2><a href="<?php echo $HTTP.$val['c_parent_uri'].'/'.$val['category_uri'].'/'.$val['uri'].'.html'; ?>"><?php echo $val['title']; ?></a></h2>
													 <?php endif; ?>
													 <span class="code">Код товара: <?php echo $val['id']; ?></span>
													 <?php if (!empty($val['c_parent_path_uri'])): ?>
														 <p class="links"><a href="<?php echo $HTTP.$val['c_parent_path_uri']; ?>"><?php echo $val['c_parent_path_title'] ?></a><a href="<?php echo $HTTP.$val['c_parent_path_uri'].'/'.$val['c_parent_uri']; ?>"><?php echo $val['c_parent_title'] ?></a><a href="<?php echo $HTTP.$val['c_parent_path_uri'].'/'.$val['c_parent_uri'].'/'.$val['category_uri'] ?>"><?php echo $val['category_title'] ?></a></p>
													 <?php else: ?>
														 <p class="links"><a href="<?php echo $HTTP.$val['c_parent_uri']; ?>"><?php echo $val['c_parent_title'] ?></a><a href="<?php echo $HTTP.$val['c_parent_uri'].'/'.$val['category_uri'] ?>"><?php echo $val['category_title'] ?></a></p>
													 <?php endif; ?>
												 </td>
												 <td>
													<div class="number"><input type="text" name="productCount[<?php echo $val['id']; ?>]" id="count<?php echo $val['id'] ?>" value="<?php echo $_SESSION['cart'][$val['id']]; ?>"> шт. </div>
												 </td>
												 <td>
                                                     <?php if ($val['discount']>0): ?>
                                                         <?php if ($ip_country == 'Belarus'): ?>
                                                            <?php $price = get_price_in_bel_rub( $val['discount'] * $_SESSION['cart'][$val['id']], $val['dollar_currency'], $sd_dollar ); ?>
                                                            <span class="price"><?php echo $price ?> BYN</span>
                                                     <span class="old-currency">
                                                     <?php echo get_price_in_old_bel_rub( $val['discount'] * $_SESSION['cart'][$val['id']], $val['dollar_currency'], $sd_dollar ); ?> BYN
                                                     </span>
                                                         <?php elseif ($ip_country == 'Russian Federation'): ?>
                                                             <?php $price = get_price_in_rus_rub( $val['discount'] * $_SESSION['cart'][$val['id']], $val['dollar_currency'], $sd_dollar, $val['rouble_currency'], $sd_rur ); ?>
                                                            <span class="price"><?php echo $price; ?> руб.</span>
                                                         <?php else: ?>
                                                            <?php $price = $val['discount'] * $_SESSION['cart'][$val['id']]; ?>
                                                            <span class="price"><?php echo '$'.$price; ?></span>
                                                         <?php endif; ?>
                                                            <?php //$price = $val['discount'] * $_SESSION['cart'][$val['id']]; ?>
                                                     <?php else: ?>
                                                         <?php if ($ip_country == 'Belarus'): ?>
                                                             <?php $price = get_price_in_bel_rub( $val['price'] * $_SESSION['cart'][$val['id']], $val['dollar_currency'], $sd_dollar ); ?>
                                                             <span class="price"><?php echo $price ?> BYN</span>
                                                     <span class="old-currency">
                                                     <?php echo get_price_in_old_bel_rub( $val['price'] * $_SESSION['cart'][$val['id']], $val['dollar_currency'], $sd_dollar ); ?> BYN
                                                     </span>
                                                         <?php elseif ($ip_country == 'Russian Federation'): ?>
                                                             <?php $price = get_price_in_rus_rub( $val['price'] * $_SESSION['cart'][$val['id']], $val['dollar_currency'], $sd_dollar, $val['rouble_currency'], $sd_rur ); ?>
                                                             <span class="price"><?php echo $price; ?> руб.</span>
                                                         <?php else: ?>
                                                             <?php $price = $val['price'] * $_SESSION['cart'][$val['id']]; ?>
                                                             <span class="price"><?php echo '$'.$price; ?></span>
                                                         <?php endif; ?>
                                                         <?php //$price = $val['price'] * $_SESSION['cart'][$val['id']]; ?>
                                                     <?php endif; ?>
												 </td>
												 <td>
													 <a href="<?php echo $HTTP . 'shopping-cart.html?remove='.$val['id']; ?>" rel="<?php echo $val['id']; ?>" class="remove"></a>
												 </td>
											 </tr>
											 </tbody>
										 </table>
									 </div><!-- row_item -->
									<?php $total += floatval(str_replace(' ','',$price)); ?>
								 <?php endforeach; ?>
								 <div class="actions_cart">
									 <div class="total">Всего:
										<?php if ($ip_country == 'Belarus'): ?>
												 <?php echo number_format( $total , 2, '.', ' '); ?> BYN
                                         <span class="old-currency">
                                             <?php echo number_format( $total * 10000 , 0, '.', ' '); ?> BYN
                                         </span>
										<?php elseif ($ip_country == 'Russian Federation'): ?>
												 <?php echo number_format( $total , 0, ',', ' '); ?> руб.
										<?php else: ?>
												 <?php echo '$'. $total; ?>
										<?php endif; ?>
									 </div>
									 <div class="buttons order_buttons">
										 <a href="/" onclick="javascript: $('#order').submit(); return false;" class="button invert refresh">Пересчитать</a>
										 <a href="/" onclick="javascript: $('.shipping-form').slideDown(800, function() { $.scrollTo('#review_form',800) }); return false;" class="button">Оформить</a>
									 </div>
								 </div>
							 <?php CForm::end(); ?>

							 <div class="twelve columns shipping-form" style="<?php echo ((empty($errors)) ? 'display: none;' : 'display: block;'); ?>">
								 <span class="heading">&nbsp;</span>
								 <div class="white_block">

									 <div class="review_form" id="review_form" style="display: block;">
										 <span class="heading_two">Ваши контактные данные</span>

									 <?php CForm::begin('shipping_info', 'post'); ?>
                                                                                <div class="row">
                                                                                        <div class="two columns">
                                                                                                <label for="name">Ваше имя</label>
                                                                                        </div>
                                                                                        <div class="ten columns">
                                                                                                <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="input_style<?php echo ((in_array('name', $errors)) ? ' errors' : null); ?>">
                                                                                        </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                        <div class="two columns">
                                                                                                <label for="address">Ваш адрес</label>
                                                                                        </div>
                                                                                        <div class="ten columns">
                                                                                                <input type="text" id="address" name="address" value="<?php echo $address; ?>" class="input_style<?php echo ((in_array('address', $errors)) ? ' errors' : null); ?>">
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="two columns">
                                                                                                <label for="phone">Ваш телефон</label>
                                                                                        </div>
                                                                                        <div class="ten columns">
                                                                                                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="input_style<?php echo ((in_array('phone', $errors)) ? ' errors' : null); ?>">
                                                                                        </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                        <div class="two columns">
                                                                                                <label for="comment">Комментарий</label>
                                                                                        </div>
                                                                                        <div class="ten columns">
                                                                                                <textarea id="comment" name="comment" cols="150" rows="7" class="textarea_style"><?php echo $comment; ?></textarea>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                        <div class="two columns">
                                                                                                <label for="comment">Способ оплаты</label>
                                                                                        </div>
                                                                                        <div class="ten columns payment<?php echo ((in_array('payment', $errors)) ? ' error_block' : null); ?>">
                                                                                            <label for="cash">
                                                                                                <input type="radio" id="cash" name="payment" value="cash"> наличными<Br>
                                                                                            </label>
                                                                                            <label for="no_cash">
                                                                                                <input type="radio" id="no_cash" name="payment" value="no_cash"> безналичный расчет<Br>
                                                                                            </label>
                                                                                            <label for="card">
                                                                                                <input type="radio" id="card" name="payment" value="card"> пластиковой картой курьеру<Br>
                                                                                            </label>
                                                                                            <label for="halva">
                                                                                                <input type="radio" id="halva" name="payment" value="halva"> пластиковой картой "Халва" курьеру<Br>
                                                                                            </label>
                                                                                            <label for="visa">
                                                                                                <input type="radio" id="visa" name="payment" value="visa"> онлайн оплата картой Visa, MasterCard, Белкарт<Br>
                                                                                            </label>
                                                                                            <label for="credit">
                                                                                                <input type="radio" id="credit" name="payment" value="credit"> в кредит<Br>
                                                                                            </label>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                        <div class="two columns"></div>
                                                                                        <div class="ten columns">
                                                                                                <a href="javascript: $('#shipping_info').submit();" class="button medium send">Отправить</a>
                                                                                        </div>
                                                                                </div>
										</div>
									 <?php CForm::end(); ?>
								 </div>
							 </div>

						 <?php else: ?>
							 <?php if ($status_ok): ?>
								 <h2 class="status_ok">Спасибо, ваш заказ принят. Мы скоро с вами свяжемся.</h2>
                             <?php elseif ( $_GET['payment'] == 'visa' ):

                                 if ($this->tv['ip_country'] == 'Belarus') {
                                     $orderAmount = $total_price * $sd_dollar;
                                     $currency = 'BYN';
                                 } elseif ($ip_country == 'Russian Federation') {
                                     $orderAmount = round(($total_price * $sd_dollar)/intval($sd_rur));
                                     $currency = 'RUB';
                                 } else {
                                     $orderAmount = $total_price;
                                     $currency = 'USD';
                                 }
                                 ?>
                                 <h2 class="status_ok">Сейчас Вы будете перенаправлены на страницу оплаты Assist</h2>
                                 <FORM ACTION="https://pay132.paysec.by/pay/order.cfm" METHOD="POST" id="assist_form">
                                     <INPUT TYPE="HIDDEN" NAME="Merchant_ID" VALUE="476642">
                                     <INPUT TYPE="HIDDEN" NAME="OrderNumber" VALUE="<?php echo $order_id ?>">
                                     <INPUT TYPE="HIDDEN" NAME="OrderAmount" VALUE="<?php echo $orderAmount ?>">
                                     <INPUT TYPE="HIDDEN" NAME="OrderCurrency" VALUE="<?php echo $currency ?>">
                                     <INPUT TYPE="HIDDEN" NAME="FirstName" VALUE="<?php echo $name ?>">
                                     <INPUT TYPE="HIDDEN" NAME="OrderComment" VALUE="<?php echo $comment ?>">
                                     <INPUT TYPE="HIDDEN" NAME="MobilePhone" VALUE="<?php echo $phone ?>">
                                     <INPUT TYPE="HIDDEN" NAME="URL_RETURN_OK" VALUE="http://diti.by/success.html">
                                     <INPUT TYPE="HIDDEN" NAME="URL_RETURN_NO" VALUE="http://diti.by/fail.html">
                                 </FORM>
							 <?php else: ?>
								 <div class="no_product"><p>Корзина пуста.</p></div>
							 <?php endif; ?>
						 <?php endif; ?>
					 </div>

				 </div><!-- block white -->
			 </div>
		 </div>
 <?php else: ?>
         <?php if ($page_found): ?>
         <section id="main">
             <ul class="bread_crumbs">
                 <li><a href="<?php echo $HTTP; ?>">Главная</a></li>
                 <li><?php echo $page_arr[1]['title']; ?></li>
             </ul>

             <div class="row">
                 <div class="twelve columns">
                     <h1 class="heading"><?php echo $page_arr[1]['title']; ?></h1>
                     <div class="white_block">

                         <?php echo $page_arr[1]['description']; ?>

                     </div>
                 </div>
             </div>

             <?php endif; ?>
 <?php endif; ?>
