/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

// Native context menu fix
CKEDITOR.dom.element.prototype.disableContextMenu = function(){};

CKEDITOR.editorConfig = function (config) {

    config.filebrowserBrowseUrl = '/admin/elfinder';

    //config.contentsCss = '/css/mysitestyles.css';

    config.entities = false;
    config.entities_latin = false;

    // Define changes to default configuration here. For example:
    config.language = 'nl';
    config.lang = 'nl';

    config.contentsLanguage = 'nl';
    config.defaultLanguage = 'nl';

    config.uiColor = '#eceae7';
    config.undoStackSize = 250;

    config.resize_enabled = false;

    config.disableNativeSpellChecker = false;

    config.entities = false;

    config.allowedContent = 'a[href]; img[!src,alt,!width,!height]{float};' + // Note no {width,height}
    'h2 h3 p strong b em i sub sup ul ol li'

    config.extraPlugins = 'divarea';
    config.removePlugins = 'elementspath, contextmenu, resize, tabletools, forms, font, document, div, underline, magicline, flash, iframe';

    config.height = 'auto';

    config.autoGrow_onStartup = true;
    config.autoGrow_minHeight = 100;
    config.autoGrow_maxHeight = 500;

    config.startupOutlineBlocks = true;


    // Define changes to default configuration here.
    // For the complete reference:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // The toolbar groups arrangement, optimized for two toolbar rows.

    config.toolbar = [
        [ 'Format' ],
        [ 'PasteFromWord' ],
        [ 'Bold', 'Italic' ],
        [ 'NumberedList', 'BulletedList' ],
        [ 'Link', 'Unlink' ],
        [ 'Subscript', 'Superscript' ],
        [ 'SpecialChar' ]
    ];

    // Se the most common block elements.
    config.format_tags = 'p;h2';

    // Make dialogs simpler.
    config.removeDialogTabs = 'image:advanced;link:advanced';
    config.linkShowAdvancedTab = false;
    config.linkShowTargetTab = false;

    config.pasteFromWordNumberedHeadingToList = true;

    config.forcePasteAsPlainText = true; // Webkit double paste bug

    config.skin = 'just';
};


CKEDITOR.on('instanceReady', function (ev) {
    // Ends self closing tags the HTML4 way, like <br>.
    ev.editor.dataProcessor.writer.selfClosingEnd = '>';

    ev.editor.dataProcessor.writer.setRules('p',
        {
            indent: false,
            breakBeforeOpen: false,
            breakAfterOpen: false,
            breakBeforeClose: false,
            breakAfterClose: false
        });

});