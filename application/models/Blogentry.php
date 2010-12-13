<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Model_Blogentry extends Model_Base_Table {
    protected $_name = 'blog_entry';
    protected $_primary = 'identry';
    protected static $_instance = null;

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function getAll() {
        $table = self::getInstance();
        $select = $table->select()->order('identry')->group('identry');
        return $table->fetchAll($select);
    }

    public static function getLast3Blog() {
        $table = self::getInstance();
        $select = $table->select()->order('identry');
        return $table->fetchAll($select);
    }

    public static function getById($identry=NULL, $lang = 'ua') {
        $table = self::getInstance();
        $select = $table->select();
        if ($identry !== NULL) {
            $select->where('identry = ?', $identry)
                   ->where('lang = ?', $lang);
            $res = $table->fetchRow($select);
            if ( is_object($res)) { return $res; }
            return $table->fetchNew();
        } else {
            return $table->fetchNew();
        }
    }

    public static function updateentry($input) {
        $table = self::getInstance();
        $data = $input->getUnescaped();
        try {
            if (isset($data['identry'])
                && ($data['identry'] != 'new')) {
                $id = $data['identry'];

                //перевіремо чи вже э такий запис
                $select = $table->select()
                    ->where("identry = ?", $data['identry'])
                    ->where("lang = ?", $data['lang']);
                $cur = $table->fetchRow($select);
                if (is_object($cur)) {
                    $table->update($data, 'identry =  ' . $id. " and lang = '".$cur->lang."'");
                } else {
                    $table->insert($data);
                }
            } else {
                unset($data['identry']);
                $id = $table->insert($data);
            }
            return array(true, $id);
        } catch (Exception $e) {
            return array('-1', $e->getMessage());
        }
        return array('-2', 'Помилка даних.');
    }

    public static function deleteentry($identry) {
        $table = self::getInstance();
        return $table->delete("identry = ".$identry);
    }
}
