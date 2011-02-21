<?php
class Base_Rowset extends Zend_Db_Table_Rowset_Abstract
{
	public $countData=0;
	public function setTable(Zend_Db_Table_Abstract $table,$IntegrityCheck=true)
    {
        $this->_table = $table;
        $this->_connected = false;
        // @todo This works only if we have iterated through
        // the result set once to instantiate the rows.
        foreach ($this as $row) {
            $connected = $row->setTable($table,$IntegrityCheck);
            if ($connected == true) {
                $this->_connected = true;
            }
        }
        return $this->_connected;
    }
}