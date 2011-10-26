<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Zend_View_Helper_SetupEditor {
    function setupEditor($textareaId, $config='/public/js/ckeditor/pruzua_config.js') {
        return "<script type=\"text/javascript\">
            CKEDITOR.replace('".$textareaId."',{customConfig: '".$config."'});
    </script>";
    }
}
?>
