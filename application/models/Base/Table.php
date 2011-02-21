<?php
abstract class Model_Base_Table extends Zend_Db_Table_Abstract {
    protected $_name = null;
    protected $_primary = null;

    public function returnData($data) {
        return
                new Zend_Paginator(
                new Zend_Paginator_Adapter_DbTableSelect($data));

    }

    public function getMaxSort() {
        $max = $this->select()
                     ->from(array($this->_name),array('mmm' => 'max(sort)'));
        $t = $this->fetchRow($max);
        return $t->mmm + 1;
    }
}