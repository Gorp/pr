<?php
class Model_Base_Row extends Zend_Db_Table_Row_Abstract
{
	public function setTable(Zend_Db_Table_Abstract $table = null,$IntegrityCheck=true)
    {
        if ($table == null) {
            $this->_table = null;
            $this->_connected = false;
            return false;
        }

        $tableClass = get_class($table);
        if (! $table instanceof $this->_tableClass) {
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("The specified Table is of class $tableClass, expecting class to be instance of $this->_tableClass");
        }

        $this->_table = $table;
        $this->_tableClass = $tableClass;

        $info = $this->_table->info();
				if ($IntegrityCheck)
				{echo $IntegrityCheck;exit;
	        if ($info['cols'] != array_keys($this->_data)) {
	            require_once 'Zend/Db/Table/Row/Exception.php';
	            throw new Zend_Db_Table_Row_Exception('The specified Table does not have the same columns as the Row');
	        }

	        if (! array_intersect((array) $this->_primary, $info['primary']) == (array) $this->_primary) {

	            require_once 'Zend/Db/Table/Row/Exception.php';
	            throw new Zend_Db_Table_Row_Exception("The specified Table '$tableClass' does not have the same primary key as the Row");
	        }
        }

        $this->_connected = true;
        return true;
    }
}