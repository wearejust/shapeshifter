/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {

    config.filebrowserBrowseUrl = '/admin/elfinder';

    config.entities = false;
    config.entities_latin = false;

    // Define changes to default configuration here. For example:
    config.language = 'nl';
    config.lang = 'nl';

    config.contentsLanguage = 'nl';
    config.defaultLanguage = 'nl';

    config.uiColor = '#eceae7';
    config.undoStackSize = 250;

    config.format_tags = 'p;h2';

    config.resize_enabled = false;

    config.startupFocus = false;


    config.scayt_autoStartup = false;
    //config.scayt_autoStartup = true;

    config.disableNativeSpellChecker = false;

    config.extraPlugins = 'autogrow';
    config.removePlugins = 'elementspath, contextmenu, resize';

    config.autoGrow_maxHeight = 500;


    // Define changes to default configuration here.
    // For the complete reference:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // The toolbar groups arrangement, optimized for two toolbar rows.

    config.toolbar = [
        [ 'Format', 'Bold', 'Italic', 'Subscript', 'Superscript', 'PasteFromWord', 'Link' ]
    ];

    // Remove some buttons, provided by the standard plugins, which we don't
    // need to have in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript';

    // Se the most common block elements.
    config.format_tags = 'p;h2';

    // Make dialogs simpler.
    config.removeDialogTabs = 'image:advanced;link:advanced';
    config.linkShowAdvancedTab = false;
    config.linkShowTargetTab = false;


    config.pasteFromWordRemoveFontStyles = true;
    config.pasteFromWordRemoveStyles = true;
    config.pasteFromWordNumberedHeadingToList = true;
    // config.pasteFromWordPromptCleanup = true; // Moet toch gebeuren, dus geen melding geven

    config.forcePasteAsPlainText = true; // Webkit double paste bug

    config.skin ='just';
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