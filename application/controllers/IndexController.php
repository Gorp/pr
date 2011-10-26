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
                $recaptcha_challenge_field = $this->_getParam('recaptcha_challenge_field', 'default');
                $recaptcha_response_field = $this->_getParam('recaptcha_response_field', 'default');
                $result = $this->view->token->verify(
                        $recaptcha_challenge_field, $recaptcha_response_field
                );
                  //var_dump($recaptcha_challenge_field, $recaptcha_response_field);
                  
                if ($result->isValid()) {
                    Model_Comment::updatepage($data);
                    // send mail
                    try {
                        $mail = new Zend_Mail($charset = 'utf-8');
                        $mail->addTo($this->view->config->resources->mail->admin);
                        $mail->setFrom($this->view->config->resources->mail->sender);
                        $text = $this->view->config->resources->mail->template;
                        $text = "Був доданий коментар до сторінки " .
                                $this->view->config->baseurl . $this->getRequest()->getRequestUri() . "\n";
                        $text .= "Дата: " . $this->view->localDate(time(), $this->view->lang) . "\n";
                        $text .= "Имя: $name \n";
                        $text .= "Email: $email \n";
                        $text .= "Текст: $message \n";
                        $mail->setSubject('Коментар на сайті Party Zone');
                        $mail->setBodyText($text);
                        $t = $mail->send();
                    } catch (Zend_Mail_Exception $e) {
                        echo json_encode(array('status' => 'error', 'msg' => $e->getMessages()));
                        exit;
                    }

                    $this->_redirect($this->getRequest()->getRequestUri());
                }
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

    public function blognewAction() {
        $this->view->input = array(
            'title' => '',
            'author' => '',
            'email' => '',
            'richtext' => '',
        );
        $this->view->errors = array(
            'title' => array(''),
            'author' => array(''),
            'email' => array(''),
            'richtext' => array(''),
            'captcha' => array(''),
        );
        $this->view->token = new Zend_Service_ReCaptcha($this->config->capcha->pub, $this->config->capcha->priv);
        if ($this->_request->isPost()) {
            // перевіряємо капчу 
            $recaptcha_challenge_field = $this->_getParam('recaptcha_challenge_field', 'default');
            $recaptcha_response_field = $this->_getParam('recaptcha_response_field', 'default');
            $result = $this->view->token->verify(
                    $recaptcha_challenge_field, $recaptcha_response_field
            );       

            
            if ( ! $result->isValid() ) {
                $this->view->errors['captcha'][0] = 'Введений не вірний текст';
            }
            
            // валідація введених даних
            $_POST['keyword'] = $_POST['description'] = $_POST['title'];
            $input = $this->blogvalidate($_POST);
            if (true || $input->isValid() && empty($this->view->errors['captcha'][0])) {
                $res = Model_Blogentry::updateentry($input);
                if ($res[0] > 0) {
                        foreach ($this->view->langs as $key) {
                            if ($key !== $input->lang) {
                                $data = $_POST;
                                $data['identry'] = $res[1];
                                $data['lang'] = $key;
                                $input = $this->blogvalidate($data);
                                Model_Blogentry::updateentry($input);
                            }
                        }
                    // upload image     
                                /* Uploading Document File on Server */
            $dest= 'public/img/blog/thumb';
            $upload = new Zend_File_Transfer_Adapter_Http();
            $upload->setDestination("tmp");
            try {
                // upload received file(s)
                $upload->receive();
                $t = $upload->getFileInfo();
        
                $oldfilename = 'tmp/'.$t['photo']['name'];
                $filename = "{$dest}/{$res[1]}.jpg";

                // ініціалізуємо модулі лдля роботи з картинками
                $thumb = new Asido_Image($oldfilename, $filename);
                $asido = new Asido_Api();
                $asido->_driver(new Asido_Driver_GD());

                // визначаэмо пропорції картинки, щоб дізнатися з якої сторони вирізати
                $ar = getimagesize($oldfilename);
                $res2width = true;
                if ($ar[0] < $ar[1]) {
                    $res2width = false;
                }

//                if ($res2width && (($ar[0] / $ar[1]) > 1.5 )) {
//                    $asido->height($thumb, 153);
//                } else {
//                    $asido->width($thumb, 224);
//                }
//                $asido->crop($thumb, 0, 0, 224, 153);
                $asido->frame($thumb, 224, 153, Asido_Api::color(0, 0, 0));
                $thumb->save(ASIDO_OVERWRITE_ENABLED);

                // update permition
                @chmod($filename, 0777);
                @unlink($oldfilename);    
            } catch (Exception $e)   {
                
            }      
                      
                     try {
                         
                         
                         
                        $mail = new Zend_Mail($charset = 'utf-8');
                        $mail->addTo($this->view->config->resources->mail->admin);
                        $mail->setFrom($this->view->config->resources->mail->sender);
                        $text = $this->view->config->resources->mail->template;
                        $text = "Був додане поздоровлення  " .
                                $this->view->config->baseurl . $this->getRequest()->getRequestUri() . "\n";
                        $text .= "Дата: " . $this->view->localDate(time(), $this->view->lang) . "\n";
                        $text .= "Имя: ".$input->author." \n";
                        $text .= "Email: ".$input->email."\n";
                        $text .= "Текст: ".$input->richtext." \n";
                        $mail->setSubject('Поздоровлення на сайті Party Zone');
                        $mail->setBodyText($text);
                        $t = $mail->send();
                    } catch (Zend_Mail_Exception $e) {
                        echo json_encode(array('status' => 'error', 'msg' => $e->getMessages()));
                        exit;
                    }   
                        
                        
                        
                        
                    $url = array(
                        'lang' => $this->view->lang,
                        'title' => Local_String::translit($input->title),
                        'idpost' => $res[1]
                    );
                    return $this->_redirect($this->view->url($url, 'blogpage'));
                }
            } else {
                $this->view->input = array_merge($this->view->input, $input->getUnescaped());

                $this->view->errors = array_merge($this->view->errors, $input->getErrors());
                //var_dump($input->getErrors());
            }
        }
    }

    private function blogvalidate($input) {
        $filters = array(
            '*' => array(array('PregReplace', "/[\\\\']/", ''),
                array('StripTags', array('p', 'font', 'b', 'i', 'u', 'span', 'strong', 'em', 'h1', 'h2', 'h3', 'h4', 'style', 'font')), array('StringTrim')),
            'richtext' => array(array('PregReplace', "/[\\\\']/", ''),
                array('StringTrim'))
        );
        $options = array(
            //'escapeFilter' => new Zend_Filter_HtmlEntities(null, 'UTF-8')
            'notEmptyMessage' => "A non-empty value is required for field '%field%'"
        );
        $validators = array(
            'title' => array(
                'messages' => array('notDigits' => 'A month must consist only of digits')
            ),
            'author' => array(
                'allowEmpty' => false,
                'presence' => 'required',
            ),
            'email' => array(
                'presence' => 'required',
                'EmailAddress'
            ),
            'identry' => array(),
            'keyword' => array(),
            'description' => array(),
            'lang' => array(
                'default' => 'ua'
            ),
            'richtext' => array(
                'presence' => 'required',
                new Zend_Validate_StringLength(array('iconv' => 'utf-8', 'min' => 20, 'max' => 3000))
            )
        );

        return new Zend_Filter_Input($filters, $validators, $input, $options);
    }

}

