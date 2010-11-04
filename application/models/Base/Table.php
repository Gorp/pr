<?php
abstract class Model_Base_Table extends Zend_Db_Table_Abstract {
    protected $_name = null;
    protected $_primary = null;
    
    public function returnData($data) {
        return
                new Zend_Paginator(
                new Zend_Paginator_Adapter_DbTableSelect($data));

    }


}