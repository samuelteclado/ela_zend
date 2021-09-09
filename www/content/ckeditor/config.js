/*
 Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

        CKEDITOR.editorConfig = function(config)
        {
            // Define changes to default configuration here. For example:
            // config.language = 'fr';
            // config.uiColor = '#AADC6E';
            config.filebrowserBrowseUrl = 'ckeditor/ckfinder/ckfinder.html',
            config.filebrowserImageBrowseUrl = 'ckeditor/ckfinder/ckfinder.html?typ­e=Images',
            config.filebrowserFlashBrowseUrl = 'ckeditor/ckfinder/ckfinder.html?typ­e=Flash',
            config.filebrowserUploadUrl = 'ckeditor/ckfinder/core/connector/ph­p/connector.php?command=QuickUpload&­type=Files',
            config.filebrowserImageUploadUrl = 'ckeditor/ckfinder/core/connector/ph­p/connector.php?command=QuickUpload&­type=Images',
            config.filebrowserFlashUploadUrl = 'ckeditor/ckfinder/core/connector/ph­p/connector.php?command=QuickUpload&­type=Flash';
        };
