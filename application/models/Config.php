<?php
/**
 * Description of U
 *
 * 
 * @author dima
 */
class Model_Config extends Model_Base_Table {

    protected $_name = 'config';
    protected $_primary = 'name';
    protected static $_instance = null;

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * Get vaue by key
     * 
     */
    public static function getConfig($key=NULL) {
        $table = self::getInstance();
        $select = $table->select()->where('name = ?', $key);
        
        $res = $table->fetchRow($select);
        if (is_object($res)) {
            return $res->value;
        } else {
            return '';
        }
    }

    /*
     * Set Config
     *
     */

    public static function setConfig($key, $value) {
        $table = self::getInstance();
        //var_dump($data);        exit;
        try {
            $data = array(
                'name' => $key,
                'value' => $value
            );
            $res = self::getConfig($key);
            if (empty($res)) {
                $id = $table->insert($data);
            } else {
                $id = $table->update($data,"name = '$key'");
            }
            
            return array(true, $id);
        } catch (Exception $e) {
            return array('-1', $e->getMessage());
        }

        return array('-2', 'Помилка даних.');
    }



}

