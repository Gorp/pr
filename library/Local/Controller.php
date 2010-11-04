<?php

class Local_Controller extends Zend_Controller_Action {

    public function init() {
        $frontController = Zend_Controller_Front::getInstance();
        $bootstrap = $frontController->getParam('bootstrap');

        $this->view->config = new Zend_Config($bootstrap->getOptions());

        
    }
}