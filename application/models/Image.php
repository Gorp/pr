<?php
/**
 * Description of U
 *
 * 
 * @author dima
 */
class Model_Image extends Model_Base_Table {

    protected $_name = 'image';
    protected $_primary = 'idimage';
    protected static $_instance = null;

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /* get all images by Gallry
     *
     * @parent - parent menu id
     *
     */

    public static function getAll($idgallery) {
        $table = self::getInstance();
        $select = $table->select()
                ->where('idgallery = ?', $idgallery)
                ->order('idimage');
        return $table->fetchAll($select);
    }

    /**
     * Get info about image by id
     * @param idimage
     * @return RowSet
     * 
     */
    public static function getById($idimage=NULL) {
        $table = self::getInstance();
        $select = $table->select();
        if ($idimage !== NULL) {
            $select->where('idimage = ?', $idimage);
            return $table->fetchRow($select);
        } else {
            return $table->fetchNew();
        }
    }

    /*
     * Add image
     *
     */

    public static function addImage($idgallery) {
        $table = self::getInstance();
        //var_dump($data);        exit;
        try {
            $data = array(
                'idgallery' => $idgallery
            );
            $id = $table->insert($data);
            return array(true, $id);
        } catch (Exception $e) {
            return array('-1', $e->getMessage());
        }

        return array('-2', 'Помилка даних.');
    }
    
    
    public static function addTrack($idgallery, $trackname) {
        $table = self::getInstance();
        //var_dump($data);        exit;
        try {
            $data = array(
                'idgallery' => $idgallery,
                'path' => $trackname
            );
            $id = $table->insert($data);
            return array(true, $id);
        } catch (Exception $e) {
            return array('-1', $e->getMessage());
        }

        return array('-2', 'Помилка даних.');
    }

    /**
     * Видалення пункта картинку за id
     *
     * @param idimage
     */
    public static function deleteImage($idimage) {
        $table = self::getInstance();
        
        return $table->delete("idimage = ".$idimage);
    }


    public static function savePath($idimage, $path) {
        $table = self::getInstance();
        
    }
}

