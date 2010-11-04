<?php
/**
 * Description of Base
 *
 * @author dima
 */
class Local_Base {

    public static function isLogged() {
        $ns = new Zend_Session_Namespace('admin');
        if (empty($ns->islogin)) {
            return false;
        }
        return true;
    }
}
