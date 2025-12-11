<?php
global $ajaxValidator;
$router = $app->get_module('Router');
$router->add_route('/', 'CIndexPage', 'CIndexPage.php', 'indexpage.tpl');
$router->add_route('/index.html', 'CIndexPage', 'CIndexPage.php', 'indexpage.tpl');

$router->add_route('/search/', 'CSearchPage', 'CSearchPage.php', 'searchpage.tpl');
$router->add_route('/search/page-([0-9]+)/', 'CSearchPage', 'CSearchPage.php', 'searchpage.tpl', array(1 => 'page'));
$router->add_route('/news.html', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl', array(1 => 'page'));
$router->add_route('/articles.html', 'CArticlesPage', 'CArticlesPage.php', 'articlepage.tpl');
$router->add_route('/(about|actions|delivery|subscribe|vacancy|contacts|thank|otlozhennoe|servis-i-garantiya|shopping-cart|success|fail).html', 'CPagesPage', 'CPagesPage.php', 'pagespage.tpl', array(1 => 'static_page'));
$router->add_route('/consultation.html', 'CConsultationPage', 'CConsultationPage.php', 'consultationpage.tpl');
$router->add_route('/([\w-_]+).html', 'CPagesPage', 'CPagesPage.php', 'pagespage.tpl', array(1 => 'static_page'));

$router->add_route('/articles/page-([0-9]+)/', 'CArticlesPage', 'CArticlesPage.php', 'articlepage.tpl', array(1 => 'page'));
$router->add_route('/articles/([\w-_]+).html', 'CArticlesPage', 'CArticlesPage.php', 'articlepage.tpl', array(1 => 'article_uri'));
$router->add_route('/articles/([0-9]{4})/([0-9]{2})/', 'CArticlesPage', 'CArticlesPage.php', 'articlepage.tpl');

$router->add_route('/news/page-([0-9]+)/', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl', array(1 => 'page'));
$router->add_route('/news/([\w-_]+).html', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl', array(1 => 'news_uri'));
$router->add_route('/news/tag/([\w-_]+)/', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl', array(1 => 'tag_uri'));
$router->add_route('/news/([0-9]{4})/([0-9]{2})/', 'CNewsPage', 'CNewsPage.php', 'newspage.tpl');

$router->add_route('/actions/page-([0-9]+)/', 'CActionsPage', 'CActionsPage.php', 'actionspage.tpl', array(1 => 'page'));
$router->add_route('/action/([\w-_]+).html', 'CActionsPage', 'CActionsPage.php', 'actionspage.tpl', array(1 => 'action_uri'));

$router->add_route('/vk-auth', 'CVKapiAuthPage', 'CVKapiAuthPage.php');
$router->add_route('/fb-auth', 'CFBapiAuthPage', 'CFBapiAuthPage.php');
$router->add_route('/page-not-found.html', 'CNotFoundPage', 'CNotFoundPage.php', 'page_not_found.tpl');

$router->add_route('/sale.html', 'CSalePage', 'CSalePage.php', 'salepage.tpl');
$router->add_route('/sale/page-([0-9]+)', 'CSalePage', 'CSalePage.php', 'salepage.tpl', array(1 => 'page'));

/* --- production --- */

/* categories */

$router->add_route('/([\w-_]+)/', 'CCategoriesPage', 'CCategoriesPage.php', 'categoriespage.tpl', array(1 => 'category_uri'));
//$router->add_route('/([\w-_]+)/([\w-_]+)/', 'CCategoriesPage', 'CCategoriesPage.php', 'categoriespage.tpl', array(1 => 'parent_category_uri', 2 => 'category_uri'));

/* products */
$router->add_route('/snoubord/snoubord-deki/', 'CSnoubordPage', 'CSnoubordPage.php', 'snoubordpage.tpl');
$router->add_route('/([\w-_]+)/([\w-_]+)/', 'CProductsPage', 'CProductsPage.php', 'productspage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'c3_uri'));
$router->add_route('/([\w-_]+)/([\w-_]+)/([\w-_]+)/', 'CProductsPage', 'CProductsPage.php', 'productspage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'c3_uri'));
$router->add_route('/([\w-_]+)/([\w-_]+)/([\w-_]+)/page-([0-9]+)/', 'CProductsPage', 'CProductsPage.php', 'productspage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'c3_uri', 4 => 'page', 5 => 'brand'));
$router->add_route('/([\w-_]+)/([\w-_]+)/([\w-_]+).html', 'CProductPage', 'CProductPage.php', 'productpage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'product_uri'));
$router->add_route('/([\w-_]+)/([\w-_]+)/([\w-_]+)/([\w-_]+).html', 'CProductPage', 'CProductPage.php', 'productpage.tpl', array(1 => 'c1_uri', 2 => 'c2_uri', 3 => 'c3_uri', 4 => 'product_uri'));

$page = $router->get_current_page();
if (!is_null($page)) {
	$page->parse_data();
	$page->parse_state();
	$page->output_page();
}
elseif(!$ajaxValidator) {
	@header('HTTP/1.0 302 Moved Temporarily');
	@header('Status: 302 Moved Temporarily');
	@header('Location: http://'.$_SERVER['SERVER_NAME'].'/page-not-found.html');
}
?>