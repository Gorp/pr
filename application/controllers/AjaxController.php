<?php

class AjaxController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout()->setLayout('ajax');
    }

    public function indexAction() {
        // action body
    }

    /*
     * Обробка закаченних через аякс картинок
     *
     */
    public function imguploadAction() {
        if ($this->_request->isPost()) {

            /* Uploading Document File on Server */
            $upload = new Zend_File_Transfer_Adapter_Http();
            $upload->setDestination("tmp");
            try {
                // upload received file(s)
                $upload->receive();
                $t = $upload->getFileInfo();
                $oldfilename = $t['Filedata']['name'];
                $idgallery = $this->_getParam('item', null);
                list($status, $idimage) = Model_Image::addImage($idgallery);
                if (!$status) return false;
                $filename = $idimage.".jpg";
                copy("tmp/" . $oldfilename, 'public/gallery/full/' . $idgallery . '_' . $filename);
                unlink("tmp/" . $oldfilename);
                $ar = getimagesize('public/gallery/full/' . $idgallery. '_' . $filename);
                $res2width = true;
                if ($ar[0] < $ar[1]) {
                    $res2width = false;
                }

                $image_mid = new Asido_Image('public/gallery/full/' . $idgallery. '_' . $filename, 'public/gallery/mid/' . $idgallery . '_' . $filename);
                $image_small = new Asido_Image('public/gallery/full/' . $idgallery . '_' . $filename, 'public/gallery/small/' . $idgallery. '_' . $filename);
                $image_big = new Asido_Image('public/gallery/full/' . $idgallery . '_' . $filename, 'public/gallery/big/' . $idgallery. '_' . $filename);

                $asido = new Asido_Api();
                //$asido_drive =
                $asido->_driver(new Asido_Driver_GD());

                //mid
//                if ($res2width && (($ar[0] / $ar[1]) > 1.5 )) {
//                    $asido->height($image_mid, 144);
//                } else {
//                    $asido->width($image_mid, 216);
//                }
                
                //$asido->crop($image_mid, 0, 0, 216, 144);
                $asido->frame($image_mid, 216, 144,  Asido_Api::color(0, 0, 0));
                $image_mid->save(ASIDO_OVERWRITE_ENABLED);

                //big
//                if ($res2width && (($ar[0] / $ar[1]) > 1.75 )) {
//                    $asido->height($image_big, 400);
//                } else {
//                    $asido->width($image_big, 700);
//                }
//                $asido->crop($image_big, 0, 0, 700, 400);
                $asido->frame($image_big, 700, 400, Asido_Api::color(0, 0, 0));
                $image_big->save(ASIDO_OVERWRITE_ENABLED);

//                if ($res2width) {
//                    $asido->height($image_small, 70);
//                } else {
//                    $asido->width($image_small, 70);
//                }
                //$asido->crop($image_small, 0, 0, 70, 70);
                $asido->frame($image_small, 70, 70, Asido_Api::color(0, 0, 0));
                $image_small->save(ASIDO_OVERWRITE_ENABLED);

                // update permition
                @chmod('public/gallery/full/' . $idgallery. '_' . $filename, 0777);
                @chmod('public/gallery/big/' . $idgallery . '_' . $filename, 0777);
                @chmod('public/gallery/mid/' . $idgallery . '_' . $filename, 0777);
                @chmod('public/gallery/small/' . $idgallery . '_' . $filename, 0777);
                echo json_encode(array('status' => 'success', 'idgallery' => $idgallery));
                exit;
            } catch (Zend_File_Transfer_Exception $e) {
                echo json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
                exit;
            } catch (Exception $e) {
                echo json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
                exit;
            }
        }
        echo json_encode(array('status' => 'error', 'msg' => 'non-action'));
        exit;
    }


    /*
     * Закачка картинок для тумбов
     *
     */
    public function thumbuploadAction() {
        if ($this->_request->isPost()) {
            $type = $this->_getParam('type', 'page');
            $item = $this->_getParam('item');

            //якщо нема id (блога чи сторінки) виходимо з помилкою
            if (empty($item)) {
                echo "Код пустой";
                exit;
            }

            // в залежності від типу тсорінки вибираємо місце де буде картинка
            switch ($type) {
                case 'blog':
                        $dest= 'public/img/blog/thumb';
                    break;
                case 'page':
                        $dest= 'public/img/blog/thumb';
                    break;

                default:
                    break;
            }


            /* Uploading Document File on Server */
            $upload = new Zend_File_Transfer_Adapter_Http();
            $upload->setDestination("tmp");
            try {
                // upload received file(s)
                $upload->receive();
                $t = $upload->getFileInfo();
                $t = $upload->getFileInfo();
                $oldfilename = 'tmp/'.$t['Filedata']['name'];
                $filename = "{$dest}/{$item}.jpg";
                

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
                echo json_encode(array('status' => 'success', 'item' => $item));
                exit;
            } catch (Zend_File_Transfer_Exception $e) {
                echo json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
                exit;
            } catch (Exception $e) {
                echo json_encode(array('status' => 'error', 'msg' => $e->getMessage()));
                exit;
            }
        }
        echo json_encode(array('status' => 'error', 'msg' => 'non-action'));
        exit;
    }



    // отримати список всіх зображень для вибраної нерухостоі
    public function getimgAction() {

        $idgallery = $this->_request->getParam('item', null);

        // показати картинки
        $this->imagesList($idgallery);

        exit;
    }

    // отримати список всіх зображень для вибраної нерухостоі
    public function delimgAction() {
        $idimage = $this->_request->getParam('item', null);

        // Видаляємо картинки    
        $image = Model_Image::getById($idimage);
        $this->deleteImage($idimage);
        
        // показати картинки
        $this->imagesList($image->idgallery);

        exit;
    }

    public function descAction() {
        if ($this->_request->isPost()) {
            $idimage = $this->_getParam('item','');
            $desc = $this->_getParam('desc','');
            $data = array(
                'desc' => $desc
            );
            if ( is_object($image = Model_Image::getById($idimage)) ) {
                $image->desc = $desc;
                $image->save();
            }
        }
        exit;
    }    
    
    
    
    public function pathAction() {
        if ($this->_request->isPost()) {
            $idimage = $this->_getParam('item','');
            $path = $this->_getParam('path','');
            $data = array(
                'path' => $path
            );
            if ( is_object($image = Model_Image::getById($idimage)) ) {
                $image->path = $path;
                $image->save();
            }
            var_dump($image);
            exit;
        }
        exit;
    }



    private function imagesList($idgallery) {

        $data = Model_Image::getAll($idgallery);
        if (is_object($data)) {
            $res = '';
            foreach ($data as $value) :
                $res .= "
<div style=\"float: left; text-align: center; margin-bottom: 4px;\">
    <img  src=\"/public/gallery/small/" . $value["idgallery"] . "_" . $value["idimage"] . ".jpg\" style=\"float: left; border: 1px solid; margin-left: 2px;\"><br>
    <input type=\"text\" onchange=\"$.post('/ajax/path', {item: " . $value["idimage"] . ", path: $(this).val()})\"
    value=\"". $value["path"] . "\"><br />
    <input type=\"button\" onclick=\"$.get('/ajax/delimg/item/" . $value["idimage"] . "', function(data){ $('.photolist').html(data)});\" class=\"admin_buttonfield\"
    value=\"Видалити\">
</div>";
            endforeach;
            echo $res;
        }
    }
    
    private function deleteImage($idimage) {
        // отримуемо інформацію про файл
        $image = Model_Image::getById($idimage);
        // видалити файли
        @unlink('public/gallery/full/' . $image->idgallery . '_' . $image->idimage.".jpg");
        @unlink('public/gallery/big/' . $image->idgallery . '_' . $image->idimage.".jpg");
        @unlink('public/gallery/mid/' . $image->idgallery . '_' . $image->idimage.".jpg");
        @unlink('public/gallery/small/' . $image->idgallery . '_' . $image->idimage.".jpg");
        //видалити запис про картинку
        Model_Image::deleteImage($idimage);
        
    }
    
    // отримати всі картинки в галереях
    public function getallgalimagesAction() {
        $imaget = Model_Image::getInstance();
        $select = $imaget->select()->order("idimage desc");
        /*$listgal = $imaget->fetchAll($select);
        foreach ($listgal as $key => $image) { ?>
            <div style="float: left; text-align: center; margin-bottom: 4px;">
                <?php //echo $image->idimage;?>
                <a class="gallery" href="/public/gallery/full/<?php echo $image->idgallery . '_' . $image->idimage;?>.jpg" >
                <img style="float: left; border: 1px solid; margin-left: 2px;" src="/public/gallery/small/<?php echo $image->idgallery . '_' . $image->idimage;?>.jpg" />
                </a><br>
                <input type="button" value="Видалити" class="admin_buttonfield" onclick="delImage('<?php echo $image->idimage;?>','g')" />
            </div>
        <?php
        }*/
        echo '<div style="clear:both;"><hr /></div>';
        $listpage = scandir('public/img/page');
        $x = '.png|.jpg|.gif';
        foreach( array_diff($listpage,array('.','..')) as $f) :
                //echo preg_match('/'.$x.'$/i',$f)."<br />";
                if(is_file('public/img/page/'.$f)
                        && (($x)?preg_match('/'.$x.'$/i',$f):1) ) :?>
                    <div style="float: left; text-align: center; margin-bottom: 4px;">
                        <?php //echo $image->idimage;?>
                        <a class="gallery" href="/public/img/page/<?php echo $f;?>">
                        <img style="float: left; border: 1px solid; margin-left: 2px; width: 70px; height: 70px;" src="/public/img/page/<?php echo $f;?>" /><br>
                        </a>
                        <input type="button" value="Видалити" class="admin_buttonfield" onclick="delImage('public/img/page/<?php echo $f;?>','p')" />
                        
                    </div>                    
                <?php endif; endforeach;
        exit;
    }
    
    // Видалити картинку
    public function delgalimgAction() {
        $idimage = $this->_request->getParam('item', null);
        $type = $this->_request->getParam('type', null);

        
        // Видаляємо картинки    
        if ($type == 'p') {
            @unlink($idimage);
        } else {
            $this->deleteImage($idimage);
        }
        exit;
    }
    

}