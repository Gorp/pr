<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tr
 *
 * @author dima
 */
class Zend_View_Helper_Tr {

    function tr($id, $lang, $phrase) {
        return Model_Lang::getLang($id, $lang, $phrase);
    }
}
?>
