<?php
class Model_Comment extends Model_Base_Table {
    protected $_name = 'comment';
    protected $_primary = 'idcomment';
    protected static $_instance = null;

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function getAll($idpage=NULL, $sort = 'desc') {
        $table = self::getInstance();
        $select = $table->select()->order('postdate '.$sort);
        if ($idpage) {
            $select->where("idpage = ?", $idpage);
        }
        return $table->fetchAll($select);
    }

    public static function getById($idpage) {
        $table = self::getInstance();
        $select = $table->select();
        $select->where('idpage = ?', $idpage);
        $res = $table->fetchRow($select);
        if ( is_object($res)) { return $res; }
        return $table->fetchNew();
    }

    public static function updatepage($data) {
        $table = self::getInstance();
        try {            
            if (isset($data['idcomment'])) {
                $table->update($data, 'idcomment =  ' . $data['idcomment']);
            } else {
                $id = $table->insert($data);
            }
            return array(true, $id);
        } catch (Exception $e) {
            return array('-1', $e->getMessage());
        }
        return array('-2', 'Помилка даних.');
    }

    public static function deletepage($idcomment) {
        $table = self::getInstance();
        return $table->delete("idcomment = ".$idcomment);
    }
    
    public static function countComment($idblog) {
        $table = self::getInstance();
        $select = $table->select()
             ->from('comment', array('totalcomment' => new Zend_Db_Expr('COUNT(*)')))
             ->where('idpage = ?',$idblog)   ;
        
        return $table->fetchRow($select);

    }
    
    
}
