<?php
global $ajaxValidator;
$router = $app->get_module('Router');
$router->add_route('/sportmax/', 'CIndexPage', 'CIndexPage.php', 'indexpage.tpl');
$router->add_route('/sportmax/index.html', 'CIndexPage', 'CIndexPage.php', 'indexpage.tpl');

$router->add_route('/sportmax/search/', 'CSearchPage', 'CSearchPage.php', 'searchpage.tpl');
$router->add_route('/sportmax/search/page-([0-9]+)/', 'CSearchPage', 'CSearchPage.php', 'searchpage.tpl', array(1 => 'page'));
$router->add_route('/sportmax/news.html', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl', array(1 => 'page'));
$router->add_route('/sportmax/articles.html', 'CArticlesPage', 'CArticlesPage.php', 'articlepage.tpl');
$router->add_route('/sportmax/(about|actions|delivery|vacancy|contacts).html', 'CPagesPage', 'CPagesPage.php', 'pagespage.tpl', array(1 => 'static_page'));

$router->add_route('/sportmax/articles/page-([0-9]+)/', 'CArticlesPage', 'CArticlesPage.php', 'articlepage.tpl', array(1 => 'page'));
$router->add_route('/sportmax/articles/([\w-_]+).html', 'CArticlesPage', 'CArticlesPage.php', 'articlepage.tpl', array(1 => 'article_uri'));
$router->add_route('/sportmax/articles/([0-9]{4})/([0-9]{2})/', 'CArticlesPage', 'CArticlesPage.php', 'articlepage.tpl');

$router->add_route('/sportmax/news/page-([0-9]+)/', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl', array(1 => 'page'));
$router->add_route('/sportmax/news/([\w-_]+).html', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl', array(1 => 'news_uri'));
$router->add_route('/sportmax/news/tag/([\w-_]+)/', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl', array(1 => 'tag_uri'));
$router->add_route('/sportmax/news/([0-9]{4})/([0-9]{2})/', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl');

$router->add_route('/sportmax/actions/page-([0-9]+)/', 'CActionsPage', 'CActionsPage.php', 'actionspage.tpl', array(1 => 'page'));
$router->add_route('/sportmax/action/([\w-_]+).html', 'CActionsPage', 'CActionsPage.php', 'actionspage.tpl', array(1 => 'action_uri'));

$router->add_route('/sportmax/vk-auth', 'CVKapiAuthPage', 'CVKapiAuthPage.php');
$router->add_route('/sportmax/fb-auth', 'CFBapiAuthPage', 'CFBapiAuthPage.php');
$router->add_route('/sportmax/page-not-found.html', 'CNotFoundPage', 'CNotFoundPage.php', 'page_not_found.tpl');

$router->add_route('/sportmax/sale.html', 'CSalePage', 'CSalePage.php', 'salepage.tpl');
$router->add_route('/sportmax/sale/page-([0-9]+)', 'CSalePage', 'CSalePage.php', 'salepage.tpl', array(1 => 'page'));

/* --- production --- */

/* categories */

$router->add_route('/sportmax/([\w-_]+)/', 'CCategoriesPage', 'CCategoriesPage.php', 'categoriespage.tpl', array(1 => 'category_uri'));
//$router->add_route('/([\w-_]+)/([\w-_]+)/', 'CCategoriesPage', 'CCategoriesPage.php', 'categoriespage.tpl', array(1 => 'parent_category_uri', 2 => 'category_uri'));

/* products */
$router->add_route('/sportmax/([\w-_]+)/([\w-_]+)/', 'CProductsPage', 'CProductsPage.php', 'productspage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'c3_uri'));
$router->add_route('/sportmax/([\w-_]+)/([\w-_]+)/([\w-_]+)/', 'CProductsPage', 'CProductsPage.php', 'productspage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'c3_uri'));
$router->add_route('/sportmax/([\w-_]+)/([\w-_]+)/([\w-_]+)/page-([0-9]+)/', 'CProductsPage', 'CProductsPage.php', 'productspage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'c3_uri', 4 => 'page', 5 => 'brand'));
$router->add_route('/sportmax/([\w-_]+)/([\w-_]+)/([\w-_]+).html', 'CProductPage', 'CProductPage.php', 'productpage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'product_uri'));
$router->add_route('/sportmax/([\w-_]+)/([\w-_]+)/([\w-_]+)/([\w-_]+).html', 'CProductPage', 'CProductPage.php', 'productpage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'c3_uri', 4 => 'product_uri'));

$page = $router->get_current_page();
if (!is_null($page)) {
	$page->parse_data();
	$page->parse_state();
	$page->output_page();
}
elseif(!$ajaxValidator) {
	@header('HTTP/1.0 302 Moved Temporarily');
	@header('Status: 302 Moved Temporarily');
	@header('Location: http://'.$_SERVER['SERVER_NAME'].'/sportmax/page-not-found.html');
}
?>