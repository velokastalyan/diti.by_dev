<?
class CAjax
{
    protected $Application;
    protected $DataBase;
    protected $tv;
    protected $last_error;
    protected $Categories;
    protected $Products;
    protected $Votes;

    function CAjax(&$app)
    {
        $this->Application = &$app;
        $this->tv = &$app->tv;
        $this->DataBase = &$this->Application->DataBase;
        $this->Categories = $this->Application->get_module('Categories');
        $this->Products = $this->Application->get_module('Products');
    }

    function get_last_error()
    {
        return $this->last_error;
    }

    function generate_question($form_n)
    {
        if(!is_numeric($form_n) || intval($form_n) < 1 || intval($form_n) > 3)
        {
            $this->last_error = $this->Application->get_string('invalid_input');
            return false;
        }
        $n1 = rand(1, 20);
        $n2 = rand(1, 20);
        $result = $n1+$n2;
        $bf = &$this->Application->get_module('BF');
        $result = $bf->makeid($result);
        $_SESSION['bf_r'.$form_n] = $result;
        return $n1.'+'.$n2.'=';
    }

};