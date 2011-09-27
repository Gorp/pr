<?php

class Local_Controller extends Zend_Controller_Action {

    public function init() {
        $frontController = Zend_Controller_Front::getInstance();
        $bootstrap = $frontController->getParam('bootstrap');

        $this->config = $this->view->config = new Zend_Config($bootstrap->getOptions());
        
        // вибранна мова
        $this->view->lang = $this->_getParam('lang', 'ua');

        // потрібно для перегляду по сторінкам
        $this->view->page = $this->_getParam('page',1);

        // отримуємо головне меню
        $this->view->menu = Model_Menu::getAll(NULL,$this->view->lang);

        //скільки мов підтримуємо
        $this->view->langs = explode(',', $this->view->config->langs);
        if ($this->getRequest()->getControllerName() == 'admin') {
            $this->view->langs = explode(',', $this->view->config->adminlangs);
        }
        

        // показувати відео тільки на першій сторінці
        $this->view->showvideo = false;
        if ( ($this->_getParam('action') == 'index' ) && ($this->_getParam('controller') == 'index' )  ) {
            $this->view->showvideo = true;
        }

        //текущий номер сторінки
        $this->view->idpage = $this->_getParam('idpage', 0);

        switch ($this->view->idpage) {
            case '32':
                $this->view->headerurl = '/public/img/header_diskotek.jpg';
                break;
            case '33':
                $this->view->headerurl = '/public/img/header_livemusic.jpg';
                break;
            case '37':
                $this->view->headerurl = '/public/img/header_stars.jpg';
                break;
            case '39':
                $this->view->headerurl = '/public/img/header_sound.jpg';
                break;
            case '40':
                $this->view->headerurl = '/public/img/header_rent.jpg';
                break;

            default:
                $this->view->headerurl = '/public/img/header_main.jpg';
                break;
        }

        //налаштування транспорту пошти
//        Zend_Mail::setDefaultTransport(
//                new Zend_Mail_Transport_Smtp(
//                            $this->view->config->resources->mail->transport->host,
//                            $this->view->config->resources->mail->transport->toArray()));
    }

    public function setPaginator($paginator ) {
        $paginator->setItemCountPerPage($this->view->config->pagination->itemperpage);
        $paginator->setCurrentPageNumber($this->view->page);
        return $paginator;
    }

}