<?php

class IndexController extends Local_Controller {

    public function init() {
        parent::init();
        $this->view->showvideo = false;
    }

    public function indexAction() {
// action body
        $this->view->last = Model_Blogentry::getLast3Blog($this->view->lang);
        $this->view->showvideo = true;
        $this->view->pageTitle = $this->view->tr(NULL, $this->view->lang, 'Музика для свята');
    }

    public function loginAction() {
        // action body
    }

    public function sendmessageAction() {
        if ($this->_request->isPost()) {
            $input = $this->messagevalid($_POST);
            if ($input->isValid()) {
                try {
                    $mail = new Zend_Mail($charset = 'utf-8');
                    $mail->addTo($this->view->config->resources->mail->admin);
                    $mail->setFrom($this->view->config->resources->mail->sender, $input->sender);
                    $text = $this->view->config->resources->mail->template;
                    $text = str_replace("{sender}", $input->sender, $text);
                    $text = str_replace("{phone}", $input->phone, $text);
                    $text = str_replace("{message}", $input->message, $text);
                    $mail->setSubject('Повідомлення із сайту Party Zone');
                    $mail->setBodyText($text);
                    $mail->send();
                } catch (Zend_Mail_Exception $e) {
                    echo json_encode(array('status' => 'error', 'msg' => $e->getMessages()));
                    exit;
                }
                //$this->_helper->redirector('index');
                echo json_encode(array('status' => 'success', 'msg' => 'Message Sent'));
                exit;
            }
            //$this->_helper->redirector('index');
            echo json_encode(array('status' => 'error', 'msg' => $input->getMessages()));
            exit;
        }
        exit;
    }

    private function messagevalid($input) {
        $filters = array(
            '*' => array(array('StringTrim'))
        );
        $options = array(
            'escapeFilter' => new Zend_Filter_HtmlEntities(null, 'UTF-8')
        );
        $validators = array(
            'sender' => array(
                'allowEmpty' => false
            ),
            'phone' => array(
                'allowEmpty' => true,
            ),
            'message' => array(
                'allowEmpty' => false
            )
        );
        return new Zend_Filter_Input($filters, $validators, $input, $options);
    }

    public function pageAction() {
        // action body
        $idpage = $this->_getParam('idpage', 0);
        $this->view->content = Model_Page::getById($idpage, $this->view->lang);
        $this->view->gallery = Model_Image::getAll($this->view->content->idgallery);
        $this->view->video = Model_Image::getAll($this->view->content->idvideo);
        if (is_object($this->view->content)) {
            $this->view->pageTitle = $this->view->content->title;
            $this->view->headMeta()->appendName('keywords', $this->view->content->keyword);
            $this->view->headMeta()->appendName('description', $this->view->content->description);
        }
        //@TODO додати на сторінку title keywords description
    }

    public function blogAction() {
            // The mail address we want to hide
//    $mail = 'digorp@gmail.com';
//     
//    // Create an instance of the mailhide component, passing it your public
//    // and private keys, as well as the mail address you want to hide
//    $mailHide = new Zend_Service_ReCaptcha_MailHide();
//    $mailHide->setPublicKey($this->config->capcha->pub);
//    $mailHide->setPrivateKey($this->config->capcha->priv);
//    $mailHide->setEmail($mail);
//     
//    // Display it
//    print($mailHide);exit;
        $ispost = $this->_getParam('idpost', false);
        $this->view->pageTitle = $this->view->tr(NULL, $this->view->lang, 'Наш Блог');
        if ($ispost) {
            $this->view->token = new Zend_Service_ReCaptcha($this->config->capcha->pub, $this->config->capcha->priv);
            if ($this->_request->isPost()) {
                $filter = new Zend_Filter();
                $filter = $filter->addFilter(new Zend_Filter_StripTags)
                        ->addFilter(new Zend_Filter_HtmlEntities);
                $name = $filter->filter($this->_getParam('name', ''));
                $email = $filter->filter($this->_getParam('email', ''));
                $message = $filter->filter($this->_getParam('message', ''));
                $token = $this->_getParam('token', '');
                $data = array('name' => $name, 'email' => $email, 'message' => $message, 'idpage' => $ispost);
                $result = $this->view->token->verify(
                    $_POST['recaptcha_challenge_field'],
                    $_POST['recaptcha_response_field']
                );
                if ($result->isValid()) {
                    Model_Comment::updatepage($data);
                    $this->_redirect($this->getRequest()->getRequestUri());
                }
                var_dump($result->isValid());
                exit;
            }
            $this->view->comments = Model_Comment::getAll($ispost);
            $this->view->post = Model_Blogentry::getById($ispost, $this->view->lang);
            if (is_object($this->view->post)) {
                $this->view->pageTitle = $this->view->post->title;
                $this->view->headMeta()->appendName('keywords', $this->view->post->keyword);
                $this->view->headMeta()->appendName('description', $this->view->post->description);
            }
            return $this->renderScript('/index/blogentry.phtml');
        }
        $this->view->blogs = $this->setPaginator(Model_Blogentry::getBlogs($this->view->lang));
        $this->view->blogmenu = Model_Menu::getById(16, $this->view->lang);
    }

}

