<?php

/**
 * Description of Users
 *
 * 
 * @author dima
 */
class Model_Menu extends Model_Base_Table {

    protected $_name = 'menu';
    protected $_primary = 'idmenu';
    protected static $_instance = null;

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /* get all menus
     *
     * @parent - parent menu id
     *
     */

    public static function getAll($parent=NULL) {
        $table = self::getInstance();
        $select = $table->select();
        if ($parent !== NULL) {
            $select->where('parent = ?', $parent);
        }
        return $table->fetchAll($select);
    }

    /**
     * Get info about menu by id
     * @param idmenu 
     * @return RowSet
     * 
     */
    public static function getById($idmenu=NULL) {
        $table = self::getInstance();
        $select = $table->select();
        if ($idmenu !== NULL) {
            $select->where('idmenu = ?', $idmenu);
            return $table->fetchRow($select);
        } else {
            return $table->fetchNew();
        }
    }

    /*
     * Menu updater
     *
     */

    public static function updatemenu($input) {
        $table = self::getInstance();
        $data = $input->getUnescaped();
        //var_dump($data);        exit;
        try {
            if (isset($data['idmenu']) &&
                    ($data['idmenu'] != 'new' )) {
                $id = $data['idmenu'];
                $table->update($data, 'idmenu =  ' . $id);
            } else {
                unset($data['idmenu']);
                $id = $table->insert($data);
            }
            return array(true, $id);
        } catch (Exception $e) {
            return array('-1', $e->getMessage());
        }

        return array('-2', 'Помилка даних.');
    }

    /**
     * Видалення пункта меню за id
     *
     * @param <type> $input
     */
    public static function deletemenu($idmenu) {
        $table = self::getInstance();
        $table->update(array("parent"=>0), 'parent = '.$idmenu);
        return $table->delete("idmenu = ".$idmenu);
    }

}

