<?php

class Local_Controller extends Zend_Controller_Action {

    public function init() {
        $frontController = Zend_Controller_Front::getInstance();
        $bootstrap = $frontController->getParam('bootstrap');

        $this->view->config = new Zend_Config($bootstrap->getOptions());
        $this->view->headerurl = '/public/img/header_main.jpg';

        // отримуємо головне меню
        $this->view->menu = Model_Menu::getAll(0);

        // вибранна мова
        $this->view->lang = $this->_getParam('lang', 'ua');

        //скільки мов підтримуємо
        $this->view->langs = explode(',', $this->view->config->langs);

        //налаштування транспорту пошти
        Zend_Mail::setDefaultTransport(
                new Zend_Mail_Transport_Smtp(
                            $this->view->config->resources->mail->transport->host,
                            $this->view->config->resources->mail->transport->toArray()));
    }
}