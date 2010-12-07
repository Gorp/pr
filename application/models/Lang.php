<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Model_Lang extends Model_Base_Table {
    protected $_name = 'lang';
    protected $_primary = 'idlang';
    protected static $_instance = null;

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function getAll() {
        $table = self::getInstance();
        $select = $table->select()->group('phrase');
        return $table->fetchAll($select);
    }



    public static function getLangByPhrase($phrase, $lang=NULL) {
        $table = self::getInstance();
        $select = $table->select()
                        ->from(array('lang'),array('idlang','text'))
                        ->where('lang = ?', $lang )
                        ->where('phrase = ?', $phrase);

//        echo $select;exit;
        $res = $table->fetchRow($select);
        if (is_object($res)) {
            return $res;
        } 
        return $table->fetchNew();
    }

    public static function getLangByID($id) {
        $table = self::getInstance();
        $select = $table->select()
                        ->from(array('lang'),array('idlang','text'))
                        ->where('idlang = ?', $id);
        //echo $select;exit;
        $res = $table->fetchRow($select);
        if (is_object($res)) {
            return $res;
        }
        return $table->fetchNew();
    }




    public static function getLang($id, $lang, $phrase) {
        $table = self::getInstance();
        $select = $table->select()
                        ->from(array('lang'),array('text'))
                        ->where('phrase = ?', md5($phrase))
                        ->where('lang = ?', $lang);
        //echo $select;exit;
        $res = $table->fetchRow($select);
        if (is_object($res)) {
            return $res->text;
        } else {
            $data = array(
                'lang' => $lang,
                'phrase' => md5($phrase),
                'text'  => $phrase
            );
            self::updateLang($data);
            return $phrase;
        }
    }

    public static function updateLang($data) {
        $table = self::getInstance();
        //$data = $input->getUnescaped();
//        var_dump($data);
//        exit;
        try {
            if (isset($data['idlang']) && !empty($data['idlang'])) {
                //перевіремо чи вже э такий запис
                $where = $table->getAdapter()->quoteInto("idlang = {$data['idlang']}" );
                $table->update($data, $where);
            } else {
                unset($data['idlang']);
                $id = $table->insert($data);
            }
            return array(true, $id);
        } catch (Exception $e) {
            return array('-1', $e->getMessage());
        }
        return array('-2', 'Помилка даних.');
    }

    public static function deleteentry($idlang) {
        $table = self::getInstance();
        return $table->delete("idlang = ".$idlang);
    }
}
