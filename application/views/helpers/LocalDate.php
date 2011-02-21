<?php
/**
 * Description of Tr
 *
 * @author dima
 */
class Zend_View_Helper_LocalDate {

    function localDate($date, $lang) {
        $date = new Zend_Date($date,null,$lang);
        return $date->toString(Zend_Date::DATE_FULL);
    }
}
?>
