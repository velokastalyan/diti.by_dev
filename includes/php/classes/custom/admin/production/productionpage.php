<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CProductionPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'loc_lang';

	/**
	 * The columns array.
	 *
	 * @var array
	 */
	protected $_columns_arr = array('title', 'abbreviation');

	protected $_title = 'Languages';

	function CProductionPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
	}

	function on_page_init()
	{
		parent::on_page_init();
		$this->page_actions();
	}


    function page_actions() {
        if (CForm::is_submit('xml_form', 'generate_xml')) {
            $Categories = $this->Application->get_module('Categories');
            $categories_rs = $Categories->get_all();
            $categories_array = array();
            if($categories_rs !== false && !$categories_rs->eof())
            {
                while(!$categories_rs->eof())
                {
                    row_to_vars($categories_rs, $categories_array[count($categories_array) + 1]);
                    $categories_rs->next();
                }
            }

            $s_map = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\r\n";

            $s_map .=
                '<url>
                    <loc>'.$this->HTTP.'</loc>
                </url>'."\r\n";

            foreach($categories_array as $item)
            {
                $url = $this->HTTP;
                if ($item["parent_path_uri"])
                    $url .=  $item["parent_path_uri"].'/'.$item["uri"];
                else
                    $url .=  $item["uri"];
                $s_map .= '<url>'."\r\n";
                $s_map .= '<loc>'.$url.'</loc>'."\r\n";
                $s_map .= '</url>'."\r\n";
            }

            $Products = $this->Application->get_module('Products');
            $products_rs = $Products->get_all();
            $products_array = array();
            if($products_rs !== false && !$products_rs->eof())
            {
                while(!$products_rs->eof())
                {
                    row_to_vars($products_rs, $products_array[count($products_array) + 1]);
                    $products_rs->next();
                }
            }

            foreach($products_array as $item)
            {
                $url = $this->HTTP.$item["parent_path_uri"].'/'.$item["cat_uri"].'/'.$item["uri"].'.html';
                $s_map .= '<url>'."\r\n";
                $s_map .= '<loc>'.$url.'</loc>'."\r\n";
                $s_map .= '</url>'."\r\n";
            }

            $Pages = $this->Application->get_module('Pages');
            $pages_rs = $Pages->get_all();
            $pages_array = array();
            if($pages_rs !== false && !$pages_rs->eof())
            {
                while(!$pages_rs->eof())
                {
                    row_to_vars($pages_rs, $pages_array[count($pages_array) + 1]);
                    $pages_rs->next();
                }
            }

            foreach($pages_array as $item)
            {
                $url = $this->HTTP.$item["uri"].'.html';
                $s_map .= '<url>'."\r\n";
                $s_map .= '<loc>'.$url.'</loc>'."\r\n";
                $s_map .= '</url>'."\r\n";
            }

            $s_map .= '<url>
                        <loc>'.$this->HTTP.'news.html</loc>
                    </url>'."\r\n";

            $Articles = $this->Application->get_module('Articles');
            $articles_rs = $Articles->get_all();
            $articles_array = array();
            if($articles_rs !== false && !$articles_rs->eof())
            {
                while(!$articles_rs->eof())
                {
                    row_to_vars($articles_rs, $articles_array[count($articles_array) + 1]);
                    $articles_rs->next();
                }
            }

            foreach($articles_array as $item)
            {
                $url = $this->HTTP.'news/'.$item["uri"].'.html';
                $s_map .= '<url>'."\r\n";
                $s_map .= '<loc>'.$url.'</loc>'."\r\n";
                $s_map .= '</url>'."\r\n";
            }

            $s_map .= '<url>
                        <loc>'.$this->HTTP.'sale.html</loc>
                    </url>'."\r\n";

            $s_map .= '</urlset>';

            $fp = fopen(ROOT.'sitemap.xml', 'w');
            $test = fwrite($fp,$s_map);
        return $test;
        }
    }

	function parse_data()
	{
		if (!parent::parse_data()) return false;

		$this->bind_data();

		return true;
	}
}