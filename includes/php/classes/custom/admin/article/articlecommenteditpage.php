<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CArticleCommentEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'article_comment';

	protected $_module = 'Articles';

	function CArticleCommentEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();
		$Articles = $this->Application->get_module('Articles');
		$article_rs = $Articles->get_all('', $this->id);
		if($article_rs == false)
		{
			$article_rs = new CRecordSet();
			$article_rs->add_field('id');
			$article_rs->add_field('title');
			$this->tv['_errors'] = $Articles->get_last_error();
		}
		$article_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);

		$status_rs = new CRecordSet();
		$status_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$status_rs->add_row(array('id' => 'active', 'title' => $this->Application->Localizer->get_string('Active')));
		$status_rs->add_row(array('id' => 'not_active', 'title' => $this->Application->Localizer->get_string('Not_active')));

		CInput::set_select_data('status', $status_rs, 'id', 'title');
		CInput::set_select_data('article_id', $article_rs);

		CValidator::add('name', VRT_TEXT, false, 0, 255);
		CValidator::add('description', VRT_TEXT);
		CValidator::add('status', VRT_TEXT);

	}

	function parse_data()
	{
		if(!parent::parse_data())
			return false;

		return true;
	}

	function bind_data()
	{
		parent::bind_data();
	}
}
?>