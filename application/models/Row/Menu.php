<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Property
 *
 *
 * @author dima
 */
class Model_Row_Menu extends Model_Base_Row {
    //put your code here
    function getPageByMenu($lang) {
        //@TODO обработать ситуацию когда получаем пустой объект
        if (empty($this->idpage)) return "no title";
        return Local_Base::translit(Model_Page::getById($this->idpage, $lang)->title);
    }
}

