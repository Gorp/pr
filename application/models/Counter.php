<?php
/**
 * Description of Users
 *
 * 
 * @author dima
 */
class Model_Counter extends Model_Base_Table {

    protected $_name = 'counter';
    protected $_primary = 'idcounter';
    protected static $_instance = null;

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Get info about menu by id
     * @param idgallery
     * @return RowSet
     * 
     */
    public static function getById($idgallery=NULL) {
        $table = self::getInstance();
        $select = $table->select();
        if ($idgallery !== NULL) {
            $select->where('idgallery = ?', $idgallery);
            return $table->fetchRow($select);
        } else {
            return $table->fetchNew();
        }
    }

    public static function update_counter($idcounter,$ip) {
        $table = self::getInstance();
        $data = array(
            'idcounter' => $idcounter,
            'ip' => $ip
        );
        
        try {
            $table->insert($data);
        } catch (Exception $e) {
            
        }
        
        $select = $table->select()
                ->from('counter', array('total' => new Zend_Db_Expr('COUNT(*)')))
                ->where('idcounter = ?', $idcounter);
                
        $res = $table->fetchRow($select);
        
        return $res->total;
        
    }
 
    public static function countComment($idblog) {
        $table = self::getInstance();
        $select = $table->select()
             ->from('comment', array('totalcomment' => new Zend_Db_Expr('COUNT(*)')))
             ->where('idpage = ?',$idblog)   ;
        
        return $table->fetchRow($select);

    }    

}

