<div itemscope itemtype="http://schema.org/WebPage">
<ul class="bread_crumbs" xmlns="http://www.w3.org/1999/xhtml" itemprop="breadcrumb">
    <li>
        <a href="<? echo $HTTP; ?>">Главная</a>
    </li>
    <li>
		<? if($c_deep > 1): ?><a href="<? echo $HTTP; ?><? echo $c_parent_path_uri; ?>/"><? echo $c_parent_path; ?></a></li> <li><? endif; ?><? echo $c_title; ?>
    </li>
</ul>
</div>
<div class="row">
<? if($children_found): ?>
	<div class="twelve columns">
		<h1 class="heading"><? echo ($c_h1_text)? $c_h1_text : $c_title; ?></h1>

<ul class="list_products categories">
	<? foreach ($children as $k => $c): ?>
		<li>
			<a href="<? echo $HTTP; ?><? echo $c['child_parent_path_uri']; ?>/<? echo $c['child_uri']; ?>/">
				<span class="image">
						<img src="<? echo $HTTP; ?>pub/categories/<? echo $c['child_id']; ?>/180x190/<? echo $c['child_image_filename']; ?>" alt="<? echo $c['child_title']; ?>">
				</span>
				<span class="category">
					<? echo $c['child_title']; ?>
				</span>
			</a>
		</li>
	<? endforeach; ?>
</ul>
    <div class="description_category">
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
<? endif; ?>

	</div>
</div>


<? if ($product_found): ?>
	<div class="row">
	<h1 class="heading"><? if (empty($c_title)) echo $c_parent_path; else echo $c_title;  ?></h1>
        <div class="three columns">
            <aside class="left_aside">
                <form action="#" class="custom">
                    <span class="title">Брэнд</span>
					<? if ($brand_found): ?>
                    <ul>
						<? foreach($brand_arr as $item1): ?>
                        <li onclick='window.location.href = "?brand="+<? echo $item1['id']; ?>'>
                            <label for="<? echo $item1['uri']; ?>">
                                <input type="checkbox" id="<? echo $item1['uri']; ?>" style="display: none;"><span class="custom checkbox  <? if ($item1['id'] === $brand) echo 'checked'; ?>"></span><? echo $item1['title']; ?>
                            </label>
                        </li>
						<? endforeach; ?>
                    </ul>
					<? endif; ?>
                    <span class="title">Цена</span>
                    <ul>
                        <li>
                            <label for="price1">
                                <input type="checkbox" id="price1" style="display: none;"><span class="custom checkbox"></span>$200.00 - $250.00
                            </label>
                        </li>
                        <li>
                            <label for="price2">
                                <input type="checkbox" id="price2" style="display: none;"><span class="custom checkbox"></span>$250.00 - $300.00
                            </label>
                        </li>
                        <li>
                            <label for="price3">
                                <input type="checkbox" id="price3" style="display: none;"><span class="custom checkbox"></span>$400.00 - $450.00
                            </label>
                        </li>
                        <li>
                            <label for="price4">
                                <input type="checkbox" id="price4" style="display: none;"><span class="custom checkbox"></span>$450.00 - $500.00
                            </label>
                        </li>
                        <li>
                            <label for="price5">
                                <input type="checkbox" id="price5" style="display: none;"><span class="custom checkbox"></span>$500.00 - $1000.00
                            </label>
                        </li>
                    </ul>
                </form>
            </aside>
        </div>
        <div class="nine columns">
            <div class="top_actions">
                <form action="#" class="custom clearfix">
                    <div class="item">
                        Показано  <span>1-9</span> из <span>52</span>
                    </div>
                    <div class="item_page">
                        Показывать по  <span class="items_line"><a href="#" class="active">9</a><a href="#">18</a><a href="#">36</a></span> на странице
                    </div>
                    <select style="display:none;">
                        <option selected>Сортировать по</option>
                        <option>Сортировать</option>
                        <option>по</option>
                    </select>
                    <div class="custom dropdown small">
                        <a href="#" class="current">
                            Сортировать по
                        </a>
                        <a href="#" class="selector"></a>
                        <ul>
                            <li>Сортировать по</li>
                            <li>Сортировать</li>
                            <li>по</li>
                        </ul>
                    </div>
                </form>
            </div>

            <ul class="list_products medium">
				<? foreach($product_arr as $item1): ?>
                <li>
                    <a href="<? echo $HTTP.$item1['c_parent_path_uri'].'/'.$item1['c_uri'].'/'; ?><? echo $item1['uri']; ?>.html">
						<? if ($item1['is_new']): ?>
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
							<? echo $item1['price'] ?>
                        </del>
                        <span class="price">
							<? echo $item1['discount']; ?>
						</span>
						<? else: ?>
                        <span class="price">
							<?php if ($item1['price'] != 0): ?>
								<? echo $item1['price']; ?>
							<?php else: ?>
								<span class="soon">Скоро в продаже</span>
							<?php endif; ?>
						</span>
						<? endif; ?>
                    </a>
                </li>
				<? endforeach; ?>
            </ul>
            <div class="wrapper_pagination">
                <ul class="pagination">
					<? CControl::process('Pager', 'Products'); ?>
                </ul>
            </div>
        </div>
	</div>
<? endif; ?>
