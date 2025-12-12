<header id="header">
    <div class="top_line">
        <div class="row">
            <div class="twelve columns link_top">
            	<div class="top-info">
            		Доставка по всей Беларуси <i class="by-icon"></i>, России <i class="ru-icon"></i> и Казахстану <i class="kz-icon"></i>
            	</div>
                <? CForm::begin('search', 'get', $HTTP.'search/', 'search'); ?>
                <input type="text" title="Поиск товаров" value="Поиск товаров"  id="search_text" name="search_text" onfocus="if (this.value == 'Поиск товаров') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Поиск товаров';}">
                <? //CTemplate::input('text', 'search_text', 'search_text', 'inp'); ?>
                <? CTemplate::submit('search_products', 'search_products', 1, 'btn'); ?>
                <? CForm::end(); ?>
				<a class="basket <?php if($basketCart>0) echo basket_active; ?>" href="<?php echo $HTTP; ?>shopping-cart.html" rel="nofollow">Корзина (<?php echo $basketCart; ?>)</a>
				<span class="separator">|</span>
                <a href="<? echo $HTTP; ?>otlozhennoe.html" rel="nofollow"><span id="cookie_count">Отложенные (<?php echo ((!empty($_COOKIE['otlozh'])) ? sizeof(explode(',', $_COOKIE['otlozh'])) : 0); ?>)</span></a><!-- <span class="separator">|</span> -->
				<!-- <a href="<? echo $HTTP; ?>subscribe.html" rel="nofollow">Подписаться на рассылку</a> -->
            </div>
        </div>
    </div>
	<div class="row">
		<div class="twelve columns">
			<div class="row">
				<div class="two columns">
                    <a href="<? echo $HTTP; ?>" id="logo"><img src="<? echo normalize_media_url($HTTP.'images/logo.png'); ?>" alt="Интернет-магазин спортивных товаров: продажа велосипедов, сноубордов, скейтов. Минск."></a>
				</div>
				<div class="ten columns">
					<div class="top_panel">
						<? if($sd_found): ?>
							<div class="top_panel_left">
								<span class="phone1" style="background: url(/images/<? echo $sd_phone1icon; ?>) left center no-repeat;"><? echo $sd_phone1; ?></span>
								<span class="phone2" style="background: url(/images/<? echo $sd_phone2icon; ?>) left center no-repeat;"><? echo $sd_phone2; ?></span>
								<span class="phone3" style="background: url(/images/<? echo $sd_phone3icon; ?>) left center no-repeat;"><? echo $sd_phone3; ?></span>
								<span class="phone4" style="background: url(/images/<? echo $sd_phone4icon; ?>) left center no-repeat;"><? echo $sd_phone4; ?></span>
								<span class="phone3" style="background: url(/images/<? echo $sd_phone5icon; ?>) left center no-repeat;"><? echo $sd_phone5; ?></span>
								<span class="phone4" style="background: url(/images/<? echo $sd_phone6icon; ?>) left center no-repeat;"><? echo $sd_phone6; ?></span>
							</div>
							<div class="top_panel_right">
								<span class="address"><? echo $sd_address; ?></span>
								</div>
							<? endif; ?>
						</div>
						<ul class="menu">
                                                    <?php foreach($top_menu_arr['child'] as $menu_item): ?>
                                                        <li>
                                                            <a href="<?php echo $menu_item['value'] ?>"><?php echo $menu_item['title'] ?></a>
                                                        </li>
                                                    <?php endforeach; ?>
						</ul>
					</div>
				</div>
				<? if($menu_found): ?>
				<nav class="main_menu">
					<ul>
						<? foreach($menu as $item1): ?>
						<li><a href="<? echo $HTTP; ?><? echo $item1['uri']; ?>"><? echo $item1['title']; ?></a>
							<? if(is_array($item1['children']) && !empty($item1['children'])): ?>			<ul class="sub">
									<? foreach($item1['children'] as $item2): ?>
									<li>
											<a href="<? echo $HTTP; ?><? echo $item1['uri'].'/'.$item2['uri']; ?>"><? echo $item2['title']; ?></a>
											<? if(is_array($item2['children']) && !empty($item2['children']) && ($item2['uri']!='snoubord-deki')): ?>
											<ul>
												<? foreach($item2['children'] as $item3): ?>
												<li><a href="<? echo $HTTP; ?><? echo $item1['uri'].'/'.$item2['uri'].'/'.$item3['uri']; ?>"><? echo $item3['title']; ?></a></li>
												<? endforeach; ?>
											</ul>
										<? endif; ?>
									</li>
								<? endforeach; ?>
							</ul>
						<? endif; ?>
					</li>
				<? endforeach; ?>
				<li><a href="<? echo $HTTP; ?>sale.html">Распродажа</a></li>
			</ul>
			<i id="mob-menu-buttom"></i>
		</nav>
	<? endif; ?>
</div>
</div>
</header>