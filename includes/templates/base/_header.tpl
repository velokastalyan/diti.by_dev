<!DOCTYPE HTML>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=980">
    <meta name="yandex-verification" content="3dcd5f93aec4d577" />
    <? header("Content-Type: text/html; charset=utf-8"); ?>
    <!--[if lt IE 9]>
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
    <![endif]-->
    <?php /*$page_class = $GLOBALS['app']->Router->CurrentPage['class']; ?>
    <?php if ($page_class == 'CProductPage'): ?>
        <title><? echo $product_arr[1]['title'].'. Купить в Минске.'; ?></title>
        <meta name="description" content="<?php echo 'Купить '.$product_arr[1]['title'].' в интернет-магазине. Доставка по Беларуси и в Москву. Технические характеристики, отзывы.' ?>" />
    <?php else: ?>
        <title><? if (!empty($meta_title)) echo $meta_title; else echo 'Diti.by' ?></title>
        <? if (!empty($meta_description)) echo '<meta name="description" content="'.$meta_description.'" />';  ?>
        <? if (!empty($meta_keywords)) echo '<meta name="keywords" content="'.$meta_keywords.'" />';  ?>
    <?php endif;*/ ?>
    <title><? if (!empty($meta_title)) echo $meta_title; else echo 'Diti.by' ?></title>
    <? if (!empty($meta_description)) echo '<meta name="description" content="'.$meta_description.'" />';  ?>
    <? if (!empty($meta_keywords)) echo '<meta name="keywords" content="'.$meta_keywords.'" />';  ?>

    <link href='https://fonts.googleapis.com/css?family=Lobster&amp;subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/minify/g=css_foundation_,css_style,css_royalslider,css_sliderkit,css_lightbox,css_cloudzoom,css_fancybox,css_responsive" media="all">
    <link rel="stylesheet" type="text/css" href="/minify/g=css_print" media="print">
    <link rel="icon" type="image/png" href="<? echo $HTTP; ?>images/favico.png">
    <link rel="publisher" href="https://plus.google.com/u/0/106875895161369734428" />
    <meta name="viewport" content="width=device-width">
    <!-- IE Fix for HTML5 Tags -->
    <!--[if lt IE 9]>
	<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<script>
		function call(bll_class_name, method_name, parameters) {
			var o = new Observer;
			$.post("/ajax/handler.php", { 'ajax': 1, 'bll_class_name': bll_class_name, 'method_name': method_name, 'parameters': parameters }, function(data, status){
				o.notify(data);
			}, "json");
			return o;
		}
		//Observer
		var o = new Observer;
		function Observer() {
			this.fns = [];
		}
		Observer.prototype = {
			listen : function(fn) {
				this.fns.push(fn);
			},
			remove : function(fn) {
				this.fns = this.fns.filter(
					function(el) {
						if ( el !== fn ) {
							return el;
						}
					}
				);
			},
			notify : function(o, thisObj) {
				var scope = thisObj || window;
				for (var key in this.fns) {
					var el = this.fns[key];
					el.call(scope, o);
				}
			}
		};
	</script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WX8VL62');</script>
    <!-- End Google Tag Manager -->

<script src="https://api.callbacky.by/simple/load?domain=diti.by"></script>
    
</head>
<body class="inner">
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>
<div id="wrapper">
    <? if ($history_found && count($history_arr) > 0): ?>
    <div id="floating-panel">
        <div class="row">
            <div class="twelve columns">
                <span id="floating-title" class="title">История просмотров<span id="show_history"><? if ($show_history) echo 'Скрыть'; else echo 'Раскрыть'; ?><img src="<? echo $HTTP; ?>images/<? if ($show_history) echo 'close'; else echo 'open'; ?>.gif" /></span></span>

                <div id="carousel_floating" class="skin_floating">
                    <ul class="list_company">
                        <? foreach($history_arr as $item1): ?>
                        <li><a href="<? echo $HTTP; ?><? echo $item1['parent_path_uri']; ?>/<? echo $item1['cat_uri']; ?>/<? echo $item1['uri']; ?>.html"><img src="<? echo $HTTP; ?>pub/products/<? echo $item1['id']; ?>/75x75/<? echo $item1['image']; ?>" alt="<? echo $item1['title'] ?>"></a></li>
                        <? endforeach; ?>
                    </ul>
                </div><!-- /.carousel -->
            </div>
        </div>
    </div><!-- floating_panel -->
    <? endif; ?>