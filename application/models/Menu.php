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
    protected $_rowClass='Model_Row_Menu';
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

    public static function getAll($parent=NULL,$lang = 'ua') {
        $table = self::getInstance();
        $select = $table->select();
        if ($parent !== NULL) {
            $select->where('parent = ?', $parent);
        }
        $select->where('lang = ?', $lang)
               ->order('sort');
        return $table->fetchAll($select);
    }

    /**
     * Get info about menu by id
     * @param idmenu 
     * @return RowSet
     * 
     */
    public static function getById($idmenu=NULL, $lang = 'ua') {
        $table = self::getInstance();
        $select = $table->select();
        if ($idmenu !== NULL) {
            $select->where('idmenu = ?', $idmenu)->where('lang = ?', $lang);
             $res = $table->fetchRow($select);
            //echo $select;            var_dump($res);exit; 
            if ( is_object($res)) { return $res; }
             
             // якщо в нас нема запису для вибраної мови, то дадаємо запис
//             $select->where('idmenu = ?', $idmenu)->where('lang = ?', 'ua');
//             $res = $table->fetchRow($select);
//             
//             if ( is_object($res)) {
//                 foreach ()
//             }

            return $table->fetchNew();
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

                $select = $table->select()
                    ->where("idmenu = ?", $data['idmenu'])
                    ->where("lang = ?", $data['lang']);
                //echo $select;
                $cur = $table->fetchRow($select);
//                var_dump($cur);
//                exit;
                if (is_object($cur)) {
                    $table->update($data, 'idmenu =  ' . $id. " and lang = '".$cur->lang."'");
                } else {
//                    $max = $table->select()
//                                 ->from(array($table->_name),array('idlang','text'));
//                    $t = $table->fetchRow($max);
//                    
                    $t = $table->fetchRow("idmenu = ".$data['idmenu']);
                    $data['sort'] = $t->sort;
                    $table->insert($data);
                }
            } else {

                $data['sort'] = $table->getMaxSort();
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

    /**
     *
     * @param <type> $direction - куда будемо рухати меню
     * 
     */
    public static function movemenu($idmenu, $direction) {
        $table = self::getInstance();

        $cur = $table->fetchRow($table->select()->where("idmenu = ?", $idmenu, "INTEGER"));

        $select = $table->select();
        if ( $direction == 'up') {
            $select->where("sort < ?", $cur->sort, "INTEGER")
                   ->where("parent = ?", $cur->parent, "INTEGER") 
                   ->order("sort desc")
                   ->group("idmenu")
                   ->limit(1);

        } else {
            $select->where("sort > ?", $cur->sort, "INTEGER")
                   ->where("parent = ?", $cur->parent, "INTEGER")
                   ->order("sort")
                   ->group("idmenu")
                   ->limit(1);

        }

        $res = $table->fetchRow($select);

        if ( count($res) == 1) {
            $t = $cur->sort;
            $cur->sort = $res->sort;
            $res->sort = $t;
            $cur->save();
            $res->save();
        }

    }

}

