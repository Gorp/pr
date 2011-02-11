<?php

class IndexController extends Local_Controller
{

    public function init(){
        parent::init();
        $this->view->showvideo = false;
    }


    public function indexAction(){
// action body
        $this->view->last = Model_Blogentry::getLast3Blog($this->view->lang);
        $this->view->showvideo = true;
    }


    public function loginAction(){
        // action body
    }

    public function sendmessageAction() {
        if ($this->_request->isPost()) {
            $input = $this->messagevalid($_POST);
            if ($input->isValid()) {
                try {
                    $mail = new Zend_Mail($charset = 'utf-8');
                    $mail->addTo($this->view->config->resources->mail->admin);
                    $mail->setFrom(null, $input->sender);
                    $text = $this->view->config->resources->mail->template;
                    $text = str_replace("{sender}", $input->sender, $text);
                    $text = str_replace("{phone}", $input->phone, $text);
                    $text = str_replace("{message}", $input->message, $text);
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
                'Digits'
            ),
            'message' => array(
                'allowEmpty' => false
            )
        );
        return new Zend_Filter_Input($filters, $validators, $input, $options);
    }

    public function pageAction(){
        // action body
        $idpage = $this->_getParam('idpage',0);
        $this->view->content = Model_Page::getById($idpage,$this->view->lang);
        $this->view->gallery = Model_Image::getAll(5);
        //@TODO додати на сторінку title keywords description
    }

    public function blogAction() {
        $ispost = $this->_getParam('idpost', false);
        if ($ispost) {
            $this->view->post = Model_Blogentry::getById($ispost, $this->view->lang);
            return $this->renderScript('/index/blogentry.phtml');
        } 
        $this->view->blogs = $this->setPaginator(Model_Blogentry::getBlogs($this->view->lang));
        
    }
}

