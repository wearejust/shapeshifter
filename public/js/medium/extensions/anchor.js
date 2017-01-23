// -----------------------------------------------------------
// ANCHOR
// -----------------------------------------------------------

// var selectedFile = '';
var Anchor = MediumEditor.extensions.anchor.extend({
    'init': function () {
        MediumEditor.extensions.anchor.prototype.init.call(this);
    },

    'getForm': function () {
        if (!this.form) {
            this.form = this.createForm();
            this.$form = $(this.form);
            this.$form.addClass('medium-editor-toolbar-anchor');
            this.$input = this.$form.find('input').first();

            if (this.filebrowser) {
                var button = $('<button type="button" class="medium-editor-toolbar-anchor-browse"><i class="fa fa-search"></i></button>');
                button.on('click', this.browse.bind(this));

                var rows = this.$form.find('.medium-editor-toolbar-form-row');
                if (rows.length) {
                    rows.first().before(button);
                } else {
                    this.$form.append(button);
                }
            }
        }
        return this.form;
    },

    'browse': function () {
        window.processSelectedFile = this.processSelectedFile.bind(this);

        $.colorbox({
            'href': '/admin/elfinder/popup/input',
            'fastIframe': true,
            'iframe': true,
            'width': '70%',
            'height': '50%',
            'onCleanup': function() {
                this.$form.closest('.medium-editor-toolbar').addClass('medium-editor-toolbar-active');
            }.bind(this)
        });
    },

    'processSelectedFile': function (filePath) {
        this.$input.val(this.filebrowser + filePath);
    }
});

