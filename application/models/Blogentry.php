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
        $select = $table->select()->order('identry');
        return $table->fetchAll($select);
    }

    public static function getById($identry=NULL) {
        $table = self::getInstance();
        $select = $table->select();
        if ($identry !== NULL) {
            $select->where('identry = ?', $identry);
            return $table->fetchRow($select);
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
                $table->update($data, "identry =  ".$id);
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
