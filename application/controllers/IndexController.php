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
    }

}

