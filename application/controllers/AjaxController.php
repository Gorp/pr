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
                $filename = $t['Filedata']['name'];
                $idgallery = $this->_getParam('item', null);

                copy("tmp/" . $filename, 'public/gallery/full/' . $idgallery . '_' . $filename);
                unlink("tmp/" . $filename);
                $ar = getimagesize('public/gallery/full/' . $idgallery. '_' . $filename);
                $res2width = true;
                if ($ar[0] < $ar[1]) {
                    $res2width = false;
                }

                $image_mid = new Asido_Image('public/gallery/full/' . $idgallery. '_' . $filename, 'public/gallery/mid/' . $idgallery . '_' . $filename);
                $image_small = new Asido_Image('public/gallery/full/' . $idgallery . '_' . $filename, 'public/gallery/small/' . $idgallery. '_' . $filename);

                $asido = new Asido_Api();
                //$asido_drive =
                $asido->_driver(new Asido_Driver_GD());

                if ($res2width && (($ar[0] / $ar[1]) > 1.5 )) {
                    $asido->height($image_mid, 144);
                } else {
                    $asido->width($image_mid, 216);
                }
                $asido->crop($image_mid, 0, 0, 216, 144);
                $image_mid->save(ASIDO_OVERWRITE_ENABLED);

                if ($res2width) {
                    $asido->height($image_small, 70);
                } else {
                    $asido->width($image_small, 70);
                }
                $asido->crop($image_small, 0, 0, 70, 70);
                $image_small->save(ASIDO_OVERWRITE_ENABLED);

                // update permition
                @chmod('public/gallery/full/' . $idgallery. '_' . $filename, 0777);
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

    // отримати список всіх зображень для вибраної нерухостоі
    public function getimgAction() {

        $property_id = $this->_request->getParam('property_id', null);
        $idgallery = $this->_request->getParam('item', null);

        $table = new Model_PropertyPicture();
        // перевіряємо чи вказато конкретну картинку
        if ($image_id) {
            $data = $table->fetchAll("id = " . $image_id);
        } else {
            $data = $table->fetchAll("property_id = " . $property_id);
        }

        if (is_object($data)) {
            $res = '';
            foreach ($data as $value) :
                $res .= "
<div style=\"float: left; text-align: center; margin-bottom: 4px;\">
    <img  src=\"/public/property/mid/" . $value["id"] . "_" . $value["picture"] . "\" style=\"float: left; border: 1px solid; margin-left: 2px;\"><br>
    <input type=\"button\" onclick=\"$.get('/ajax/delimg/image_id/" . $value["id"] . "', function(data){ $('.photolist').html(data)});\" class=\"admin_buttonfield\"
    value=\"Delete\">
</div>";

            endforeach;
            // echo json_encode(array('status' => 'success', 'data' => $res));
            echo $res;
        }
        exit;
    }

    // отримати список всіх зображень для вибраної нерухостоі
    public function delimgAction() {
        $image_id = $this->_request->getParam('image_id', null);

        // отримуемо інформацію про файл
        $table = new Model_PropertyPicture();
        $image = $table->fetchRow('id = ' . $image_id);
        // видалити файли
        @unlink('public/property/full/' . $image->id . '_' . $image->picture);
        @unlink('public/property/mid/' . $image->id . '_' . $image->picture);
        @unlink('public/property/big/' . $image->id . '_' . $image->picture);
        @unlink('public/property/small/' . $image->id . '_' . $image->picture);
        @unlink('public/property/350x220/' . $image->id . '_' . $image->picture);
        @unlink('public/property/addimg/' . $image->id . '_' . $image->picture);
        $property_id = $image->property_id;
        //видалити запис про картинку
        Model_PropertyPicture::deletePictureById($image->id);

        //
        $data = $table->fetchAll("property_id = " . $property_id);


        if (is_object($data)) {
            $res = '';
            foreach ($data as $value) :
                $res .= "
<div style=\"float: left; text-align: center; margin-bottom: 4px;\">
    <img  src=\"/public/property/mid/" . $value["id"] . "_" . $value["picture"] . "\" style=\"float: left; border: 1px solid; margin-left: 2px;\"><br>
    <input type=\"button\" onclick=\"$.get('/ajax/delimg/image_id/" . $value["id"] . "', function(data){ $('.photolist').html(data)});\" class=\"admin_buttonfield\"
    value=\"Delete\">
</div>";

            endforeach;
            // echo json_encode(array('status' => 'success', 'data' => $res));
            echo $res;
        }
        exit;
    }

}