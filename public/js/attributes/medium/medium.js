// -----------------------------------------------------------
// EDITOR
// -----------------------------------------------------------
var i, ext, buttons = ['bold', 'italic', 'anchor', 'h2', 'h3', 'orderedlist', 'unorderedlist', 'blockquote_small', 'blockquote_big'];
if (window.removeExtensions && window.removeExtensions.length) {
    for (i=0; i<window.removeExtensions.length; i++) {
        buttons.remove(window.removeExtensions[i]);
    }
}
if (window.addExtensions && window.addExtensions.length) {
    for (i=0; i<window.addExtensions.length; i++) {
        ext = window.addExtensions[i];
        if (!buttons.has(ext)) {
            buttons.push(ext);
        }
    }
}

$(function () {
    var editor, extensions = {};
    $('.medium-editable').each(function(index, item) {

        if (buttons.has('blockquote_small')) {
            extensions.blockquote_small = new MediumButton({
                'label':'<i class="fa fa-quote-right">&nbsp;&nbsp;<sub>Klein</sub></i>',
                'start':'<blockquote class="small">',
                'end':'</blockquote>'
            });
        }
        if (buttons.has('blockquote_big')) {
            extensions.blockquote_big = new MediumButton({
                'label':'<i class="fa fa-quote-right">&nbsp;&nbsp;<sub>Groot</sub></i>',
                'start':'<blockquote class="big">',
                'end':'</blockquote>'
            });
        }
        if (buttons.has('textexpander')) {
            extensions.textexpander = new TextExpander();
        }

        editor = new MediumEditor(item, {
            'toolbar': {
                'allowMultiParagraphSelection': true,
                'buttons': buttons,
                'sticky': true,
                'updateOnEmptySelection': true,
                'diffLeft': 0,
                'diffTop': -5
            },
            'paste': {
                'forcePlainText': true,
                'cleanPastedHTML': true,
                'cleanReplacements': [],
                'cleanAttrs': ['class', 'style', 'dir'],
                'cleanTags': ['meta']
            },
            'imageDragging': false,
            'buttonLabels': 'fontawesome',
            'extensions': extensions,
            'disableExtraSpaces': true,
            'anchor': {
                'targetCheckbox': true
            }
        });

        $(editor.elements).data('MediumEditor', editor);

        item = $(item);
        item.data('MediumEditor', editor);
        item.mediumInsert({
            'editor': editor,
            'enabled': true,
            'addons': {
                'filebrowser': true,
                'images': false,
                'embeds': {
                    'label': '<span class="fa fa-code"></span>',
                    'placeholder': mediumAttribute.translations.embedPlaceholder,
                    'styles': null,
                    'oembedProxy': mediumAttribute.oembedProxy
                }
            }
        });
    });
    
    setTimeout(function(){
        $('.medium-insert-embed iframe').each(function(key, item){
            var iframe = $(item);
            iframe.attr('src', iframe.data('iframely-url'));
        });
    }, 1000);
});
