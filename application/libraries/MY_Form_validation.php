<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');
class MY_Form_validation extends CI_form_validation
{
    /**
     * Constructor Method
     * @param type $config
     */
    function __construct($config = array()) {
        parent::__construct($config);
    }

    public function toArray()
    {
        return $this->_error_array;
    }
}
 ?>