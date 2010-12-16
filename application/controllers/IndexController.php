<?php

class IndexController extends Local_Controller
{

    public function init(){
        parent::init();

    }


    public function indexAction(){
// action body
        $this->view->last = Model_Blogentry::getLast3Blog($this->view->lang);
    }


    public function loginAction(){
        // action body
    }


    public function pageAction(){
        // action body
        $idpage = $this->_getParam('idpage',0);
        $this->view->content = Model_Page::getById($idpage,$this->view->lang);
        //@TODO додати на сторінку title keywords description
    }

}

