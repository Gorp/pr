<?php
class Model_Page extends Model_Base_Table {
    protected $_name = 'page';
    protected $_primary = 'idpage';
    protected static $_instance = null;

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function getAll() {
        $table = self::getInstance();
        $select = $table->select()->order('title')->group('idpage');
        return $table->fetchAll($select);
    }

    public static function getById($idpage=NULL, $lang = 'ua') {
        $table = self::getInstance();
        $select = $table->select();
        if ($idpage !== NULL) {
            $select->where('idpage = ?', $idpage)
                   ->where('lang = ?', $lang);
            $res = $table->fetchRow($select);
            if ( is_object($res)) { return $res; }
            return $table->fetchNew();
        } else {
            return $table->fetchNew();
        }
    }

    public static function updatepage($input) {
        $table = self::getInstance();
        $data = $input->getUnescaped();
        try {            
            if (isset($data['idpage'])
                && ($data['idpage'] != 'new')) {
                $id = $data['idpage'];
                //перевіремо чи вже э такий запис
                $select = $table->select()
                    ->where("idpage = ?", $data['idpage'])
                    ->where("lang = ?", $data['lang']);
//                echo $select;exit;
                $cur = $table->fetchRow($select);
                //var_dump($cur, $data);                exit;
                if (is_object($cur)) {
                    $table->update($data, 'idpage =  ' . $id. " and lang = '".$cur->lang."'");
                } else {
                    $table->insert($data);
                }
                
            } else {
                unset($data['idpage']);
                $id = $table->insert($data);
            }
            return array(true, $id);
        } catch (Exception $e) {
            return array('-1', $e->getMessage());
        }
        return array('-2', 'Помилка даних.');
    }

    public static function deletepage($idpage) {
        $table = self::getInstance();
        return $table->delete("idpage = ".$idpage);
    }
}
