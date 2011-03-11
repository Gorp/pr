<?php

class AdminController extends Local_Controller {

    private $_user = 'admin';
    private $_password = '1';
    public $ns;

    public function checkLogin() {
        if (!Local_Base::isLogged()) {
            $this->_redirect('/admin/login');
        }
    }

    public function init() {
        parent::init();
        $this->_password = Model_Config::getConfig('adminpass');
        $this->view->action = $this->_getParam('action', 'index');
        $this->_helper->layout()->setLayout('admin');
        $this->view->menu_menu = '';
        $this->view->menu_page = '';
        $this->view->menu_gallery = '';
        $this->view->menu_blogentry = '';
        $this->view->menu_medialib = '';
        $this->view->menu_config = '';
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
                $ns->setExpirationSeconds(36000000);
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
        // 0 якщо рухаємо  меню
        if (($move = $this->_getParam('move', false))) {
            Model_Menu::movemenu($this->view->item, $move);
        }
        //echo "false";


        $this->view->lang = $this->_getParam('lang', 'ua');
        //var_dump(Model_Users::getDefaultAdapter());
        $this->view->mainmenu = Model_Menu::getAll(0);
        $this->view->pages = Model_Page::getAll();

        //перевіряємо на видалення
        $delete = $this->_getParam('delete', false);
        if (($delete) && Zend_Validate::is($this->view->item, 'Digits')) {
            Model_Menu::deletemenu($this->view->item);
            $this->_redirect('/admin/menu');
        }

        // якщо треба отримати дані за id меню
        if (Zend_Validate::is($this->view->item, 'Digits')) {
            $this->view->menudata = Model_Menu::getById($this->view->item, $this->view->lang);
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
            $t = Model_Menu::getById($this->_getParam('idmenu'));
            if ( ($t->blockedit == $this->_getParam('blockedit')) && ($t->blockedit == 'block') ) {
                $this->_redirect('/admin/menu/item/'.$this->_getParam('idmenu'));
            }
            //var_dump($_POST);exit;
            $input = $this->menuvalid($_POST);
            if (($input->isValid()) /*&& ($input->blockedit == 'unblock')*/) {
                $res = Model_Menu::updatemenu($input);
                if ($res[0] > 0) {
                    // Якщо це створення нового обєкта та збережено з мовою по запиту,
                    // зберігаємо варіант для інших мов
                    //if ($_POST['idmenu'] == 'new') {
                        $ll = $input->lang;
                        foreach ($this->view->langs as $key) {
                            $t = Model_Menu::getById($this->_getParam('idmenu'),$key);
                            if (!is_null($t->idmenu)) { continue;}
                            if ($key !== $ll) {
                                $data = $_POST;
                                $data['idmenu'] = $res[1];
                                $data['lang'] = $key;
                                $input = $this->menuvalid($data);
                                Model_Menu::updatemenu($input);
                            }
                        }
                    //}
                    // Якщо ні просто переходимо до редактування обєкту
                    $this->_redirect('/admin/menu/item/' . $res[1] . '/lang/' . $_POST['lang']);
                } else {
                    if (count($res[1]) > 1) {
                        echo "Ошибка! " . $res[1];
                        exit;
                    } else {
                        echo "Редагування цього запису заблоковано.";
                        exit;
                    }
                }
            }
            $t = $input->getMessages();
            if (count($t) > 0) {
                echo "Ошибка! " . var_dump($t);;
                exit;
            } else {
                echo "Редагування цього запису заблоковано2.";
                exit;
            }

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
            'lang' => array(),
            'idmenu' => array(),
            'idpage' => array(),
            'blockedit' => array(
                'default' => 'unblock'
            )
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
    /*
     * Тут працюэмо з галерею для відео
     *
     *
     */

    public function videoAction() {
        $this->view->menu_video = 'selected';
        $this->view->item = $this->_getParam('item', 'new');
        $this->view->video = Model_Gallery::getAll('video');

        //перевіряємо на видалення
        $delete = $this->_getParam('delete', false);
        if (($delete) && Zend_Validate::is($this->view->item, 'Digits')) {
            Model_Gallery::deletegallery($this->view->item);
            $this->_redirect('/admin/video');
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

    public function savevideoAction() {
        if ($this->_request->isPost()) {

            $input = $this->videovalid($_POST);

            if ($input->isValid()) {
                $res = Model_Gallery::updategallery($input);
                if ($res[0] > 0) {
                    $this->_redirect('/admin/video/item/' . $res[1]);
                } else {
                    var_dump($res[1]);
                    exit;
                }
            }
            var_dump($input->getMessages());
            exit;
        }
    }

    private function videovalid($input) {

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
            'idgallery' => array(),
            'type' => array()
        );

        return new Zend_Filter_Input($filters, $validators, $input, $options);
    }

    public function pageAction() {
        $this->view->menu_page = 'selected';
        $this->view->item = $this->_getParam('item', 'new');
        $this->view->lang = $this->_getParam('lang', 'ua');
        $this->view->pages = Model_Page::getAll();
        $this->view->galleries = Model_Gallery::getAll('image');
        $this->view->videos = Model_Gallery::getAll('video');

        $this->view->actionname = '/admin/savepage';
        $this->view->idname = 'idpage';
        //перевіряємо на видалення
        $delete = $this->_getParam('delete', false);
        if (($delete) && Zend_Validate::is($this->view->item, 'Digits')) {
            Model_Page::deletepage($this->view->item);
            $this->_redirect('/admin/page');
        }
        // якщо треба отримати дані за id сторінки
        if (Zend_Validate::is($this->view->item, 'Digits')) {
            $this->view->data = Model_Page::getById($this->view->item, $this->view->lang);
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
                    // Якщо це створення нового обєкта та збережено з мовою по запиту, 
                    // зберігаємо варіант для інших мов
                    if ($_POST['idpage'] == 'new') {
                        foreach ($this->view->langs as $key) {
                            if ($key !== $input->lang) {
                                $data = $_POST;
                                $data['idpage'] = $res[1];
                                $data['lang'] = $key;
                                $input = $this->pagevalid($data);
                                Model_Page::updatepage($input);
                            }
                        }
                    }
                    // Якщо ні просто переходимо до редактування обєкту
                    $this->_redirect('/admin/page/item/' . $res[1] . '/lang/' . $_POST['lang']);
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
            'keyword' => array(
                'allowEmpty' => true
            ),
            'description' => array(
                'allowEmpty' => true
            ),
            'lang' => array(
                'allowEmpty' => false
            ),
            'richtext' => array(
                'allowEmpty' => false
            ),
            'idpage' => array(),
            'idgallery' => array(
                'default' => 0
            ),
            'idvideo' => array(
                'default' => 0
            )
        );
        return new Zend_Filter_Input($filters, $validators, $input, $options);
    }

    public function getpageimgAction() {
        $this->_helper->layout->disableLayout();
        if ($this->_request->isPost()) {
            $url = 'public/img/page/' . uniqid() . "." . pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
            $funcNum = $_GET['CKEditorFuncNum'];
            if (($_FILES['upload'] == "none") || (empty($_FILES['upload']['name']))) {
                $message = "Файл не завантажений.";
            } else if ($_FILES['upload']["size"] == 0) {
                $message = "Файл пустий.";
            } else if (($_FILES['upload']["type"] != "image/gif") &&
                    ($_FILES['upload']["type"] != "image/jpeg") &&
                    ($_FILES['upload']["type"] != "image/png")) {
                $message = "Невірний формат файлу. Допустимі формати JPG, GIF, PNG.";
            } else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {
                $message = "Некоректне імя тимчасового файлу.";
            } else {
                $message = '';
                move_uploaded_file($_FILES['upload']['tmp_name'], $url);
                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '/$url', '$message');</script>";
            }
            $this->view->funcNum = $funcNum;
            $this->view->message = $message;
        }
    }

    public function blogentryAction() {
        $this->view->menu_blogentry = 'selected';
        $this->view->item = $this->_getParam('item', 'new');
        $this->view->lang = $this->_getParam('lang', 'ua');
        $this->view->entrys = Model_Blogentry::getAll('ua');
        $this->view->actionname = '/admin/saveblogentry';
        $this->view->idname = 'identry';
        //перевіряємо на видалення
        $delete = $this->_getParam('delete', false);
        if (($delete) && Zend_Validate::is($this->view->item, 'Digits')) {
            Model_Blogentry::deleteentry($this->view->item);
            $this->_redirect('/admin/blogentry');
        }
        // якщо треба отримати дані за id сторінки
        if (Zend_Validate::is($this->view->item, 'Digits')) {
            $this->view->data = Model_Blogentry::getById($this->view->item, $this->view->lang);
        } else {
            $this->view->data = Model_Blogentry::getById(NULL);
        }
    }

    public function saveblogentryAction() {
        if ($this->_request->isPost()) {
            $input = $this->blogentryvalid($_POST);
            if ($input->isValid()) {
                $res = Model_Blogentry::updateentry($input);
                if ($res[0] > 0) {
                    // Якщо це створення нового обєкта та збережено з мовою по запиту,
                    // зберігаємо варіант для інших мов
                    if ($_POST['identry'] == 'new') {
                        foreach ($this->view->langs as $key) {
                            if ($key !== $input->lang) {
                                $data = $_POST;
                                $data['identry'] = $res[1];
                                $data['lang'] = $key;
                                $input = $this->blogentryvalid($data);
                                Model_Blogentry::updateentry($input);
                            }
                        }
                    }
                    // Якщо ні просто переходимо до редактування обєкту
                    $this->_redirect('/admin/blogentry/item/' . $res[1]);
                } else {
                    var_dump($res[1]);
                    exit;
                }
            }
            var_dump($input->getMessages());
            exit;
        }
    }

    private function blogentryvalid($input) {
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
            'keyword' => array(
                'allowEmpty' => true
            ),
            'lang' => array(
                'allowEmpty' => false
            ),
            'description' => array(
                'allowEmpty' => true
            ),
            'richtext' => array(
                'allowEmpty' => true
            ),
            'identry' => array(),
            'date' => array()
        );
        return new Zend_Filter_Input($filters, $validators, $input, $options);
    }

    public function translateAction() {
        $this->view->menu_translate = 'selected';
        $this->view->item = $this->_getParam('item');
        $this->view->actionname = '/admin/savetranslate';
        $this->view->idname = 'idtranslate';
        //перевіряємо на видалення
        $delete = $this->_getParam('delete', false);
        if (($delete) && Zend_Validate::is($this->view->item, 'Digits')) {
            Model_Translate::delete($this->view->item);
            $this->_redirect('/admin/translate');
        }
        // якщо треба отримати дані за id сторінки
        $this->view->data = Model_Lang::getAll();

        if ($this->_request->isPost()) {
            $data = array(
                'lang' => $this->_getParam('lang'),
                'phrase' => $this->_getParam('phrase'),
                'text' => $this->_getParam('text'),
                'idlang' => $this->view->item
            );
            Model_Lang::updateLang($data);
            $this->_redirect('/admin/translate');
        }
    }

     public function configAction() {
        $this->view->menu_config = 'selected';


        if ( $this->_request->isPost()) {
            $input = $this->configvalidate($_POST);

            foreach ($input->config as $key => $value) {
                Model_Config::setConfig($key, $value);
            }
            $this->_redirect('/admin/config');
        }
     }

     private function configvalidate($input) {
        $filters = array(
            '*' => array(array('StringTrim'))
        );
        $options = array(
            'escapeFilter' => new Zend_Filter_HtmlEntities(null, 'UTF-8')
        );
        $validators = array(
            'config' => array(
                'allowEmpty' => false
            )
        );
        return new Zend_Filter_Input($filters, $validators, $input, $options);
     }
     
     // для медія сміття
     public function medialibAction() {
        $this->view->menu_medialib = 'selected';
     }

     // для коментів
     public function commentAction() {
        // delete  
        $delete = $this->_getParam('delete', false);
        if (($delete) && Zend_Validate::is($delete, 'Digits')) {
            Model_Comment::deletepage($delete);
            $this->_redirect('/admin/comment');
        } 
        
        if ($this->_request->isPost()) {
            $data = array(
                "idcomment" => $_POST["idcomment"],
                "message" => $_POST["message"]
            );
            Model_Comment::updatepage($data);
            $this->_redirect('/admin/comment');
        }
        $this->view->menu_comment = 'selected';
        $this->view->comments = Model_Comment::getAll();
     }
}

