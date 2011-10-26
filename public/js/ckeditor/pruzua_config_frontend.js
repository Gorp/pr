/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColior = '#AADC6E';
	config.width = 680;
	config.height = 400;
        //config.language = 'uk';
        config.toolbar = 'Custom';
        
        config.toolbar_Custom =
        [
            [  'Bold','Italic','Underline'],
              ['Styles','Format','Font','FontSize'],
                //['Link','Unlink','Anchor'],
                //['NumberedList','BulletedList','CreateDiv'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
                
        ];
};
