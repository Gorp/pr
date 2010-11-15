<?php

class AdminController extends Local_Controller {

    private $_user = 'admin';
    private $_password = 'admin*pr';
    public $ns;

    public function checkLogin() {
        if (!Local_Base::isLogged()) {
            $this->_redirect('/admin/login');
        }
    }

    public function init() {
        parent::init();
        $this->view->action = $this->_getParam('action', 'index');
        $this->_helper->layout()->setLayout('admin');
        $this->view->menu_menu = '';
        $this->view->menu_page = '';
        $this->view->menu_gallery = '';
        $this->view->menu_blog = '';
        $this->view->action = $this->_getParam('action');
        if ($this->view->action != 'login') {
            $this->checkLogin();
        }
    }

    public function indexAction() {
        // action body
        $this->checkLogin();
    }

    // login form
    public function loginAction() {
        $ns = new Zend_Session_Namespace('admin');
        $ns->set = 4;
        if ($ns->__isset('set')) {
            $r = (string) $ns->set;
            echo strlen($r);
        }
        //echo $ns->set;exit;
        if ($this->_request->isPost()) {
            $user = $this->_getParam('user');
            $pass = $this->_getParam('password');

            if ($user == $this->_user && $pass == $this->_password) {
                $ns->islogin = true;
                $ns->setExpirationSeconds(3600);
                return $this->_redirect('/admin/index');
            } else {
                $this->view->error = 'Не вірний користувач чи пароль';
                $this->view->username = $user;
            }
        }
    }

    // logout Action
    public function logoutAction() {
        $ns = new Zend_Session_Namespace('admin');
        $ns->__unset('islogin');
        $this->checkLogin();
    }

    public function menuAction() {
        $this->view->menu_menu = 'selected';
        $this->view->item = $this->_getParam('item', 'new');
        //var_dump(Model_Users::getDefaultAdapter());
        $this->view->mainmenu = Model_Menu::getAll(0);


        //перевіряємо на видалення
        $delete = $this->_getParam('delete', false);
        if (($delete) && Zend_Validate::is($this->view->item, 'Digits')) {
            Model_Menu::deletemenu($this->view->item);
            $this->_redirect('/admin/menu');
        }

        // якщо треба отримати дані за id меню
        if (Zend_Validate::is($this->view->item, 'Digits')) {
            $this->view->menudata = Model_Menu::getById($this->view->item);
            if ($this->view->menudata->parent == 0) {
                $this->view->curmenu = $this->view->menudata->idmenu;
            } else {
                $this->view->curmenu = $this->view->menudata->parent;
            }
            $this->view->submenu = Model_Menu::getAll($this->view->curmenu);
        } else {
            $this->view->menudata = Model_Menu::getById(NULL);
        }
        //var_dump($this->view->submenu);exit;
    }

    /*
     * Menu updater
     * 
     */

    public function savemenuAction() {
        if ($this->_request->isPost()) {
            $input = $this->menuvalid($_POST);
            if ($input->isValid()) {
                $res = Model_Menu::updatemenu($input);
                if ($res[0] > 0) {
                    $this->_redirect('/admin/menu/item/' . $res[1]);
                } else {
                    var_dump($res[1]);
                }
            }
            var_dump($input->getMessages());
        }
    }

    private function menuvalid($input) {

        $filters = array(
            '*' => array(array('PregReplace', "/[\\\\']/", ''), /* array('StripTags'), */ array('StringTrim'))
        );
        $options = array(
            'escapeFilter' => new Zend_Filter_HtmlEntities(null, 'UTF-8')
        );
        $validators = array(
            'parent' => array(
                'presence' => 'required'
            //'Digits',
            //'Identical' => '1-1'
            ),
            'name' => array(
                'allowEmpty' => false
            ),
            'idmenu' => array()
        );

        return new Zend_Filter_Input($filters, $validators, $input, $options);
    }

    /*
     * Тут працюэмо з галерея
     *
     *
     */

    public function galleryAction() {
        $this->view->menu_gallery = 'selected';
        $this->view->item = $this->_getParam('item', 'new');
        $this->view->gallery = Model_Gallery::getAll();

        //перевіряємо на видалення
        $delete = $this->_getParam('delete', false);
        if (($delete) && Zend_Validate::is($this->view->item, 'Digits')) {
            Model_Gallery::deletegallery($this->view->item);
            $this->_redirect('/admin/gallery');
        }
        // якщо треба отримати дані за id меню
        if (Zend_Validate::is($this->view->item, 'Digits')) {
            $this->view->data = Model_Gallery::getById($this->view->item);
        } else {
            $this->view->data = Model_Gallery::getById(NULL);
        }
    }

    /*
     * Menu updater
     *
     */

    public function savegalleryAction() {
        if ($this->_request->isPost()) {

            $input = $this->galleryvalid($_POST);

            if ($input->isValid()) {
                $res = Model_Gallery::updategallery($input);
                if ($res[0] > 0) {
                    $this->_redirect('/admin/gallery/item/' . $res[1]);
                } else {
                    var_dump($res[1]);
                    exit;
                }
            }
            var_dump($input->getMessages());
            exit;
        }
    }

    private function galleryvalid($input) {

        $filters = array(
            '*' => array(array('PregReplace', "/[\\\\']/", ''), /* array('StripTags'), */ array('StringTrim'))
        );
        $options = array(
            'escapeFilter' => new Zend_Filter_HtmlEntities(null, 'UTF-8')
        );
        $validators = array(
            'name' => array(
                'allowEmpty' => false,
                'messages' => array('dddd', '222')
            ),
            'idgallery' => array()
        );

        return new Zend_Filter_Input($filters, $validators, $input, $options);
    }

    public function pageAction() {
        $this->view->item = $this->_getParam('item', 'new');
        $this->view->pages = Model_Page::getAll();

        //перевіряємо на видалення
        $delete = $this->_getParam('delete', false);
        if (($delete) && Zend_Validate::is($this->view->item, 'Digits')) {
            Model_Page::deletepage($this->view->item);
            $this->_redirect('/admin/page');
        }
        // якщо треба отримати дані за id сторінки
        if (Zend_Validate::is($this->view->item, 'Digits')) {
            $this->view->data = Model_Page::getById($this->view->item);
        } else {
            $this->view->data = Model_Page::getById(NULL);
        }
    }

    public function savepageAction() {
        if ($this->_request->isPost()) {
            $input = $this->pagevalid($_POST);
            if ($input->isValid()) {
                $res = Model_Page::updatepage($input);
                if ($res[0] > 0) {
                    $this->_redirect('/admin/page/item/' . $res[1]);
                } else {
                    var_dump($res[1]);
                    exit;
                }
            }
            var_dump($input->getMessages());
            exit;
        }
    }

    private function pagevalid($input) {
        $filters = array(
            '*' => array(array('StringTrim'))
        );
        $options = array(
            'escapeFilter' => new Zend_Filter_HtmlEntities(null, 'UTF-8')
        );
        $validators = array(
            'title' => array(
                'allowEmpty' => false
            ),
            'description' => array(
                'allowEmpty' => false
            ),
            'richtext' => array(
                'allowEmpty' => false
            ),
            'idpage' => array()
        );
        return new Zend_Filter_Input($filters, $validators, $input, $options);
    }

    public function  getpageimgAction() {
        $this->_helper->layout->disableLayout();
        if ($this->_request->isPost()) {
            if (copy($_FILES['upload']['tmp_name'],
                    'public/img/'.$_FILES['upload']['name'])) {
                    $this->view->message = 'Файл збережений як public/img/'.$_FILES['upload']['name'];
            } else {
                $this->view->message = 'Помилка.';
            }
        }
    }
}