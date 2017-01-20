;(function ($, window, document, undefined) {

    'use strict';

    /** Default values */
    var pluginName = 'mediumInsert',
        addonName = 'Filebrowser', // first char is uppercase
        defaults = {
            label: '<span class="fa fa-photo"></span>',
            actions: {
                remove: {
                    label: '<span class="fa fa-times"></span>',
                    clicked: function () {
                        var $event = $.Event('keydown');

                        $event.which = 8;
                        $(document).trigger($event);
                    }
                },
                styles : null
            },
            captions: true,
            captionPlaceholder: mediumAttribute.translations.captionPlaceholder,
        };

    /**
     * Custom Addon object
     *
     * Sets options, variables and calls init() function
     *
     * @constructor
     * @param {DOM} el - DOM element to init the plugin on
     * @param {object} options - Options to override defaults
     * @return {void}
     */

    function Filebrowser (el, options) {
        this.el = el;
        this.$el = $(el);
        this.core = this.$el.data('plugin_'+ pluginName);

        this.options = $.extend(true, {}, defaults, options);
        this.templates = window.MediumInsert.Templates;

        this._defaults = defaults;
        this._name = pluginName;

        this.elfinderUrl = this.$el.attr('data-elfinder-url');

        this.init();
    }


    /**
     * Event listeners
     *
     * @return {void}
     */

    Filebrowser.prototype.events = function () {
        $(document)
            .on('click', $.proxy(this, 'unselectImage'))
            .on('keydown', $.proxy(this, 'removeImage'))
            .on('click', '.medium-insert-images-toolbar .medium-editor-action', $.proxy(this, 'toolbarAction'))
            .on('click', '.medium-insert-images-toolbar2 .medium-editor-action', $.proxy(this, 'toolbar2Action'));

        this.$el
            .on('click', '.medium-insert-images img', $.proxy(this, 'selectImage'));
    };



    /**
     * Initialization
     *
     * @return {void}
     */

    Filebrowser.prototype.init = function () {
        this.events();
    };


    /**
     * Get the Core object
     *
     * @return {object} Core object
     */
    Filebrowser.prototype.getCore = function () {
        return this.core;
    };

    /**
     * Add custom content
     *
     * This function is called when user click on the addon's icon
     *
     * @return {void}
     */

    Filebrowser.prototype.add = function () {

        this.$place = $(document).find('.medium-insert-active');
        var element = '<figure contenteditable="false"><img class="js-medium-insert-image-placeholder" src="#" alt=""></figure>';

        this.core.hideButtons();
        this.$place.html(element);

        // Replace paragraph with div, because figure elements can't be inside paragraph
        if (this.$place.is('p')) {
            this.$place.replaceWith('<div class="medium-insert-active">'+ this.$place.html() +'</div>');
            this.$place = this.$el.find('.medium-insert-active');
            this.core.moveCaret(this.$place);
        }

        this.$place.addClass('medium-insert-images');

        $.colorbox({
            href: this.elfinderUrl,
            fastIframe: true,
            iframe: true,
            width: '70%',
            height: '70%',
            onClosed: function() {
                $(document).find('[src="#"]').remove();
            }
        });
    };


    /**
     * Select clicked image
     *
     * @param {Event} e
     * @returns {void}
     */

    Filebrowser.prototype.selectImage = function (e) {


        if(this.core.options.enabled) {
            var $image = $(e.target),
                that = this;

            this.$currentImage = $image;

            // Hide keyboard on mobile devices
            this.$el.blur();

            $image.addClass('medium-insert-image-active');
            $image.closest('.medium-insert-images').addClass('medium-insert-active');

            setTimeout(function () {
                that.addToolbar();

                if (that.options.captions) {
                    that.core.addCaption($image.closest('figure'), that.options.captionPlaceholder);
                }
            }, 50);
        }

        //this.core.triggerInput();
    };

    /**
     * Unselect selected image
     *
     * @param {Event} e
     * @returns {void}
     */

    Filebrowser.prototype.unselectImage = function (e) {
        var $el = $(e.target),
            $image = this.$el.find('.medium-insert-image-active');

        if ($el.is('img') && $el.hasClass('medium-insert-image-active')) {
            $image.not($el).removeClass('medium-insert-image-active');
            $('.medium-insert-images-toolbar, .medium-insert-images-toolbar2').remove();
            this.core.removeCaptions($el);
            return;
        }

        $image.removeClass('medium-insert-image-active');
        $('.medium-insert-images-toolbar, .medium-insert-images-toolbar2').remove();

        if ($el.is('.medium-insert-caption-placeholder')) {
            this.core.removeCaptionPlaceholder($image.closest('figure'));
        } else if ($el.is('figcaption') === false) {
            this.core.removeCaptions();
        }
    };

    /**
     * Adds image toolbar to editor
     *
     * @returns {void}
     */

    Filebrowser.prototype.addToolbar = function () {
        var $image = this.$el.find('.medium-insert-image-active'),
            $p = $image.closest('.medium-insert-images'),
            active = false,
            $toolbar, $toolbar2, top;

        var mediumEditor = this.core.getEditor();
        var toolbarContainer = mediumEditor.options.elementsContainer || 'body';
        //
        $(toolbarContainer).append(this.templates['src/js/templates/images-toolbar.hbs']({
            actions: this.options.actions
        }).trim());

        $toolbar = $('.medium-insert-images-toolbar');
        $toolbar2 = $('.medium-insert-images-toolbar2');

        var top = $image.offset().top - $toolbar.height() - 8 - 2 - 5; // 8px - hight of an arrow under toolbar, 2px - height of an image outset, 5px - distance from an image
        if (top < 0) {
            top = 0;
        }

        $toolbar
            .css({
                top: top,
                left: $image.offset().left + $image.width() / 2 - $toolbar.width() / 2
            })
            .show();

        $toolbar2
            .css({
                top: $image.offset().top + 2, // 2px - distance from a border
                left: $image.offset().left + $image.width() - $toolbar2.width() - 4 // 4px - distance from a border
            })
            .show();

        $toolbar.find('button').each(function () {
            if ($p.hasClass('medium-insert-images-'+ $(this).data('action'))) {
                $(this).addClass('medium-editor-button-active');
                active = true;
            }
        });

        if (active === false) {
            $toolbar.find('button').first().addClass('medium-editor-button-active');
        }
    };

    /**
     * Fires toolbar action
     *
     * @param {Event} e
     * @returns {void}
     */

    Filebrowser.prototype.toolbarAction = function (e) {
        var $button = $(e.target).is('button') ? $(e.target) : $(e.target).closest('button'),
            $li = $button.closest('li'),
            $ul = $li.closest('ul'),
            $lis = $ul.find('li'),
            $p = this.$el.find('.medium-insert-active'),
            that = this;

        $button.addClass('medium-editor-button-active');
        $li.siblings().find('.medium-editor-button-active').removeClass('medium-editor-button-active');

        $lis.find('button').each(function () {
            var className = 'medium-insert-images-'+ $(this).data('action');

            if ($(this).hasClass('medium-editor-button-active')) {
                $p.addClass(className);

                if (that.options.styles[$(this).data('action')].added) {
                    that.options.styles[$(this).data('action')].added($p);
                }
            } else {
                $p.removeClass(className);

                if (that.options.styles[$(this).data('action')].removed) {
                    that.options.styles[$(this).data('action')].removed($p);
                }
            }
        });

        this.core.hideButtons();
        this.core.triggerInput();
    };

    /**
     * Fires toolbar2 action
     *
     * @param {Event} e
     * @returns {void}
     */

    Filebrowser.prototype.toolbar2Action = function (e) {
        var $button = $(e.target).is('button') ? $(e.target) : $(e.target).closest('button'),
            callback = this.options.actions[$button.data('action')].clicked;

        if (callback) {
            callback(this.$el.find('.medium-insert-image-active'));
        }

        this.core.hideButtons();
        this.core.triggerInput();
    };

    /**
     * Remove image
     *
     * @param {Event} e
     * @returns {void}
     */

    Filebrowser.prototype.removeImage = function (e) {
        var $image, $parent, $empty;

        if (e.which === 8 || e.which === 46) {
            $image = this.$el.find('.medium-insert-image-active');

            if ($image.length) {
                e.preventDefault();

                $parent = $image.closest('.medium-insert-images');
                $image.closest('figure').remove();

                $('.medium-insert-images-toolbar, .medium-insert-images-toolbar2').remove();

                if ($parent.find('figure').length === 0) {
                    $empty = $parent.next();
                    if ($empty.is('p') === false || $empty.text() !== '') {
                        $empty = $(this.templates['src/js/templates/core-empty-line.hbs']().trim());
                        $parent.before($empty);
                    }
                    $parent.remove();

                    // Hide addons
                    this.core.hideAddons();

                    this.core.moveCaret($empty);
                }

                this.core.triggerInput();
            }
        }

    };

    /** Addon initialization */

    $.fn[pluginName + addonName] = function (options) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName + addonName)) {
                $.data(this, 'plugin_' + pluginName + addonName, new Filebrowser(this, options));
            }
        });
    };

})(jQuery, window, document);

function processSelectedFile(filePath, requestingField) {
    var element = $('.medium-insert-active');
    var editor = element.closest('.medium-editable');
    editor = editor.siblings('[medium-editor-textarea-id="' + editor.attr('id') + '"]');

    var dir = editor.attr('data-dir');
    if (dir.substr(dir.length - 1, 1) == '/') dir = dir.substr(0, dir.length - 1);
    dir = dir.split('/');
    dir.pop();
    dir = dir.join('/') + '/';
    element.find('img').attr('src', dir + filePath).click();

    editor = editor.data('MediumEditor');
    editor.trigger('editableInput', editor.elements[0], editor.elements[0]);
}
