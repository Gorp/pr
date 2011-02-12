<?php
/**
 * Description of Users
 *
 * 
 * @author dima
 */
class Model_Gallery extends Model_Base_Table {

    protected $_name = 'gallery';
    protected $_primary = 'idgallery';
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

    public static function getAll($type='image') {
        $table = self::getInstance();
        $select = $table->select()
                ->where('type = ?', $type)
                ->order('idgallery');
        return $table->fetchAll($select);
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

    /*
     * Gallery updater
     *
     */

    public static function updategallery($input) {
        $table = self::getInstance();
        $data = $input->getUnescaped();
        //var_dump($data);        exit;
        try {
            if (isset($data['idgallery']) &&
                    ($data['idgallery'] != 'new' )) {
                $id = $data['idgallery'];
                $table->update($data, 'idgallery =  ' . $id);
            } else {
                unset($data['idgallery']);
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
    public static function deletegallery($idgallery) {
        $table = self::getInstance();
        
        // видалити файли
        array_map('unlink',glob("public/gallery/full/{$idgallery}_*.jpg"));
        array_map('unlink',glob("public/gallery/big/{$idgallery}_*.jpg"));
        array_map('unlink',glob("public/gallery/mid/{$idgallery}_*.jpg"));
        array_map('unlink',glob("public/gallery/small/{$idgallery}_*.jpg"));

        return $table->delete("idgallery = ".$idgallery);
    }

}

