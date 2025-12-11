<?php

require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

require_once(CUSTOM_CLASSES_PATH . 'components/yml.php');

class CYmlPage extends CMasterPage

{
    const SiteName = 'Diti';
    const CompanyName = 'DiTi Shop';
    const SiteURL = 'http://diti.by';
    const PictureURL = 'http://diti.by/pub/products/';
    function on_page_init()
    {
        parent::on_page_init();
    }

    function parse_data()
    {
        if(!parent::parse_data())
            return false;

        return true;
    }
    function bind_data()
    {
        $query_category = "SELECT
        		id,
        		parent_id,
        		title,
				description
            FROM %prefix%category";

        $rs =  $this->Application->DataBase->select_custom_sql($query_category);

        $category_arr = array();
        if ($rs != false && !$rs->eof())
        {
            recordset_to_vars($rs, $category_arr);
        }
        $query_product = "SELECT
        		p.id,
        		p.category_id,
        		p.title,
        		p.uri,
        		p.price,
				p.description,
				c.parent_path_uri,
				c.uri AS uri_c
            FROM %prefix%product AS p, %prefix%category AS c
            WHERE p.category_id = c.id

            ";
        $res =  $this->Application->DataBase->select_custom_sql($query_product);
        $product_arr = array();
        if ($res != false && !$res->eof())
        {
            recordset_to_vars($res, $product_arr);
        }
        $site = self :: SiteName;
        $company = self :: CompanyName;
        $siteUrl = self :: SiteURL;
        $picUrl= self :: PictureURL;
        $shop_yml = new cyml();
        $shop_yml -> set_shop($site, $company, $siteUrl);
        $shop_yml -> add_currency("BYN",1);
        $args = array();
        foreach ($category_arr as $key => $cat) {
            $shop_yml -> add_category($cat['title'],$cat['id'],($cat['parent_id']?$cat['parent_id']: -1));
        }
        foreach ($product_arr as $key => $prod) {
            if ($prod['price']>0) {
                $args['url']= $siteUrl.'/'.$prod['parent_path_uri'].'/'.$prod['uri_c'].'/'.$prod['uri'].'.html';
                $args['price']= $prod['price'];
                $args['currencyId'] = 'BYN';
                $args['categoryId']= $prod['category_id'];

                $query_image = "SELECT
        		image_filename
            FROM %prefix%product_image
            WHERE product_id = ".$prod['id']."
            ORDER BY id DESC
            LIMIT 1

            ";
                $res =  $this->Application->DataBase->select_custom_sql($query_image);
                $image_arr = array();
                if ($res != false && !$res->eof())
                {
                    recordset_to_vars($res, $image_arr);
                }

                if (!empty($image_arr)) {
                    $img = str_replace(' ','_',$image_arr[0]['image_filename']);
                    $args['picture']= $picUrl.$prod['id'].'/'.$img;
                }
                $args['delivery']= 'false';
                $args['name']= $prod['title'];
                $args['description']= strip_tags($prod['description']);
                $shop_yml -> add_offer($prod['id'],$args);
            }
        }
        $res_xml = $shop_yml -> get_xml();


        $fp = fopen(ROOT.'shops.dtd', 'w');
        $test = fwrite($fp,$res_xml);
        //$zip = new ZipArchive();
        //$zip->open(ROOT.'shops.zip', ZIPARCHIVE::CREATE);
        //$zip->addFile("shops.dtd");
        //$zip->close();

        return $test;
    }
    function on_yml_submit()
    {
        if (CForm::is_submit('yml'))
        {
            $res = $this->bind_data();
            if ($res) $this->tv['_info'][] = $this->Application->Localizer->get_string('Экспорт осуществлен!');
            else echo $this->tv['_info'][] = $this->Application->Localizer->get_string('Ошибка экспорта!');
        }
    }

}

?>