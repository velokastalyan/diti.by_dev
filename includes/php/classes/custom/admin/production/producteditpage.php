<?php 
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');
class CProductEditPage extends CMasterEditPage
{
    /**
     * The table name.
     *
     * @var array
     */
    protected $_table = 'product';
    protected $_module = 'Products';

    function CProductEditPage(&$app, $template)
    {
        $this->IsSecure = true;
        parent::CMasterEditPage($app, $template);
    }

    function on_page_init()
    {
        parent::on_page_init();
        $Categories = $this->Application->get_module('Categories');
        //$list_rs = $Categories->get_categories_list(3, $this->id);
        $list_rs = $Categories->get_categories();
        if($list_rs == false)
        {
            $list_rs = new CRecordSet();
            $list_rs->add_field('id');
            $list_rs->add_field('title');
            $this->tv['_errors'] = $Categories->get_last_error();
        }
        $list_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);
        CInput::set_select_data('category_id', $list_rs);
        CInput::set_select_data('type', $this->Application->list_types);

        $Brands = $this->Application->get_module('Brands');
        $brands_rs = $Brands->get_all();
        if($brands_rs == false)
            $brands_rs = new CRecordSet();
        $brands_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);
        CInput::set_select_data('brand_id', $brands_rs);
        $brands_rs->first();
        $list_rs->first();
        $sex_rs = new CRecordSet();
        $sex_rs->add_field('id');
        $sex_rs->add_field('title');
        $sex_rs->add_field('sex');
        $sex_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
        $sex_rs->add_row(array('id' => 0, 'title' => $this->Application->Localizer->get_string('Uni')));
        $sex_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('Men')));
        $sex_rs->add_row(array('id' => 2, 'title' => $this->Application->Localizer->get_string('Woman')));
        CInput::set_select_data('sex', $sex_rs);
        
        $suspension_rs = new CRecordSet();
        $suspension_rs->add_field('id');
        $suspension_rs->add_field('title');
        $suspension_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
        $suspension_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('hard-tail')));
        $suspension_rs->add_row(array('id' => 2, 'title' => $this->Application->Localizer->get_string('full_suspension')));
        CInput::set_select_data('suspension', $suspension_rs);

        $tormoza_rs = new CRecordSet();
        $tormoza_rs->add_field('id');
        $tormoza_rs->add_field('title');
        $tormoza_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
        $tormoza_rs->add_row(array('id' => 1, 'title' => "Дисковые гидравлические"));
        $tormoza_rs->add_row(array('id' => 2, 'title' => "Дисковые механические"));
        $tormoza_rs->add_row(array('id' => 3, 'title' => "Ободные"));
        CInput::set_select_data('tormoza', $tormoza_rs);

        $vilka_rs = new CRecordSet();
        $vilka_rs->add_field('id');
        $vilka_rs->add_field('title');
        $vilka_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
        $vilka_rs->add_row(array('id' => 1, 'title' => "Воздух"));
        $vilka_rs->add_row(array('id' => 2, 'title' => "Масло"));
        $vilka_rs->add_row(array('id' => 3, 'title' => "Пружина"));
        $vilka_rs->add_row(array('id' => 4, 'title' => "Ригидная"));
        CInput::set_select_data('vilka', $vilka_rs);

       $rama_rs = new CRecordSet();
$rama_rs->add_field('id');
$rama_rs->add_field('title');
$rama_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
$rama_rs->add_row(array('id' => 1, 'title' => "Алюминий"));
$rama_rs->add_row(array('id' => 2, 'title' => "Сталь Hi-ten"));
$rama_rs->add_row(array('id' => 3, 'title' => "Карбон"));
$rama_rs->add_row(array('id' => 4, 'title' => "Хром-молибденовая сталь"));
$rama_rs->add_row(array('id' => 5, 'title' => "Титан"));  

        CInput::set_select_data('rama', $rama_rs);

        $wheel_rs = new CRecordSet();
        $wheel_rs->add_field('id');
        $wheel_rs->add_field('title');
        $wheel_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
        $wheel_rs->add_row(array('id' => 1, 'title' => '27,5'));
        $wheel_rs->add_row(array('id' => 2, 'title' => '29'));
        CInput::set_select_data('wheel', $wheel_rs);

        $status_rs = new CRecordSet();
        $status_rs->add_field('id');
        $status_rs->add_field('title');
        $status_rs->add_field('status');
        $status_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
        $status_rs->add_row(array('id' => 0, 'title' => $this->Application->Localizer->get_string('in_stock')));
        $status_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('not_available')));
        $status_rs->add_row(array('id' => 2, 'title' => 'Заказ в 2-3 дня'));
        $status_rs->add_row(array('id' => 3, 'title' => 'Доставка в 5-7 дней'));
        $status_rs->add_row(array('id' => 4, 'title' => 'Доставка в 3-4 недели'));
        $status_rs->add_row(array('id' => 5, 'title' => 'Не отображать значение доставки на сайте'));
        CInput::set_select_data('status', $status_rs);

        CValidator::add('category_id', VRT_ENUMERATION, false, $list_rs, 'id');
        CValidator::add('title', VRT_TEXT, false, 0, 255);
        CValidator::add('uri', VRT_TEXT, false, 0, 255);
        CValidator::add('price', VRT_TEXT, false, 0);
        CValidator::add_nr('discount', VRT_TEXT, false, 0);
        CValidator::add_nr('is_sale', VRT_TEXT, false, 0);
        CValidator::add_nr('is_recommend', VRT_TEXT, false, 0);
        CValidator::add_nr('is_new', VRT_TEXT, false, 0);
        CValidator::add('sex', VRT_TEXT, false, $sex_rs, 'sex');
        CValidator::add('status', VRT_TEXT, false, $status_rs, 'status');
        CValidator::add('description', VRT_TEXT);
        CValidator::add('position_r', VRT_NUMBER, false, 1);
        CValidator::add_nr('video', VRT_TEXT);
        CValidator::add_nr('size_arr', VRT_TEXT, false, 0, 255);
        CValidator::add('meta_title', VRT_TEXT, false, 0, 80);
        CValidator::add('meta_keywords', VRT_TEXT, false, 0, 160);
        CValidator::add('meta_description', VRT_TEXT, false, 0, 160);
    }

    function parse_data()
    {
        if(!parent::parse_data())
            return false;
        return true;
    }

    function bind_images() {
        $query = "SELECT id,
            CONCAT('<img src=\"".$this->tv['HTTP']."pub/products/', product_id, '/75x75/', image_filename, '\" />') as image,
            IF(is_core > 0, '<img src=\"".$this->HTTP."images/icons/apply.gif\" />', '<img src=\"".$this->HTTP."images/icons/apply_disabled.gif\" />') as is_core
            FROM %prefix%product_image
            WHERE product_id=".$this->id;

        require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php'); // base application class
        $nav = new DBNavigator('product_images', $query, array('is_core'), 'id', false);
        $nav->set_table('product_image');
        $nav->title = $this->Application->Localizer->get_string('images');
        $nav->set_row_clickaction($this->Application->get_module('Navi')->getUri().".image_edit&product_id=".$this->id."&id=");
        $header_num = $nav->add_header('image');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('image'));
        $nav->headers[$header_num]->set_width("75%");
        $nav->headers[$header_num]->set_align("center");
        $header_num = $nav->add_header('is_core');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('is_core'));
        $nav->headers[$header_num]->set_width("25%");
        $nav->headers[$header_num]->set_align("center");

        if($nav->size > 0)
            $this->tv['remove_images_btn_show'] = true;
        else
            $this->tv['remove_images_btn_show'] = false;
    }

    function bind_recommend() {
        $query = "SELECT r.id, r.product_id, p.title, p.price
        FROM %prefix%product_recommend as r
        JOIN %prefix%product as p on ((p.id = r.recommend_id))
        WHERE r.product_id = ".$this->id."
        ";

        require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php'); // base application class
        $nav = new DBNavigator('product_recommend', $query, array('p.title'), 'id', false);
        $nav->set_table('product_recommend');
        $nav->title = $this->Application->Localizer->get_string('recommend');
        $nav->set_row_clickaction("");

        $header_num = $nav->add_header('title');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('title'));
        $nav->headers[$header_num]->set_width("75%");
        $nav->headers[$header_num]->set_align("center");

        $header_num = $nav->add_header('price');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('price'));
        $nav->headers[$header_num]->set_width("25%");
        $nav->headers[$header_num]->set_align("center");

        if($nav->size > 0)
            $this->tv['remove_recommend_btn_show'] = true;
        else
            $this->tv['remove_recommend_btn_show'] = false;
    }

    function bind_data()
    {
        parent::bind_data();
        $this->bind_images();
        $this->bind_recommend();
    }

    function on_product_submit($action)
    {
        $mod = $this->Application->get_module($this->_module);
        if (CForm::is_submit($this->_table)) {
            if (CValidator::validate_input()) {
                if ($this->id) {
                   $Products = $this->Application->get_module('Products');
                   $duplicate = $Products->get_by_uri(InPost('uri'));
                   if (count($duplicate) <= 1)
                       if ($mod->{'update_'.$this->_table}($this->id, $this->tv)) {
                            $this->tv['_info'] = $this->Application->Localizer->get_string('object_updated');
                            $this->tv['_return_to'] =  $this->Application->Navi->getUri('parent', false);
$cache_my = new CacheFileMY();
$cache_my->deleteAll();
                        }
                        else {
                            $this->tv['_errors'] = $mod->get_last_error();
                        }
                    else
                        $this->tv['_errors'] = 'Продукт с таким uri уже существует!';
                }
                else {
                    $Products = $this->Application->get_module('Products');
                    $duplicate = $Products->get_by_uri(InPost('uri'));
                    if (!$duplicate)
                        if ($this->tv['id'] = $mod->{'add_'.$this->_table}($this->tv)) {
                            $this->tv['_info'] = $this->Application->Localizer->get_string('object_added');
                            $this->tv['_return_to'] =  $this->Application->Navi->getUri('this', true).'id='.$this->tv['id'];
                        }
                        else {
                            $this->tv['_errors'] = $mod->get_last_error();
                    }
                    else
                        $this->tv['_errors'] = 'Продукт с таким uri уже существует!';
                }
            }
            else {
                $this->tv['_errors'] = CValidator::get_errors();
            }
        }
        elseif (CForm::is_submit($this->_table, 'close')) {
            $this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('parent', false));
        }
        elseif(CForm::is_submit($this->_table, 'add_image'))
        {
            $this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('./image_edit/', true) . 'product_id=' . $this->id);
        }
        elseif(CForm::is_submit($this->_table, 'add_recommend'))
        {
            $this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('./recommend_edit/', true) . 'product_id=' . $this->id);
        }
        elseif (CForm::is_submit($this->_table, 'delete_selected_images')){
            $ids = InGetPost("product_image_res", array());
            $where="WHERE 1=1";
            if (strlen($ids) > 0 && $ids !== '[]') {
                $where .= " AND id in (".$ids.") ";
                $sql = 'DELETE FROM %prefix%product_image ' . $where;
                if($this->Application->DataBase->select_custom_sql($sql)) {
                    $this->tv['_info'][] = $this->Application->Localizer->get_string('objects_deleted');
                }
                else $this->tv['_errors'][] = $this->Application->Localizer->get_string('internal_error');
            }
            else $this->tv['_info'][] = $this->Application->Localizer->get_string('noitem_selected');
        }
        elseif (CForm::is_submit($this->_table, 'delete_selected_recommend')){
            $ids = InGetPost("product_recommend_res", array());
            $where="WHERE 1=1";
            if (strlen($ids) > 0 && $ids !== '[]') {
                $where .= " AND id in (".$ids.") ";
                $sql = 'DELETE FROM %prefix%product_recommend ' . $where;
                if($this->Application->DataBase->select_custom_sql($sql)) {
                    $this->tv['_info'][] = $this->Application->Localizer->get_string('objects_deleted');
                }
                else $this->tv['_errors'][] = $this->Application->Localizer->get_string('internal_error');
            }
            else $this->tv['_info'][] = $this->Application->Localizer->get_string('noitem_selected');
        }

        $this->bind_data();
    }
}
?>
