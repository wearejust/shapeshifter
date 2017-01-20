// -----------------------------------------------------------
// TEXT EXPANDER
// -----------------------------------------------------------
var TextExpander = MediumEditor.extensions.button.extend({
    'name': 'textexpander',
    'contentFA': '<i class="fa fa-indent"></i>',
    'aria': 'text expander',

    'init': function () {
        MediumEditor.extensions.button.prototype.init.call(this);

        this.container = $(this.base.elements).first();
        this.container.find('.js-textexpander').each(function(index, item) {
            new TextExpanderElement(this.base, this.container, $(item));
        }.bind(this));
    },

    'handleClick': function () {
        var element = $(window.getSelection().anchorNode.parentNode);
        if (element.hasClass('js-textexpander')) {
            element.data('TextExpanderElement').show();

        } else {
            new TextExpanderElement(this.base, this.container);
        }
    },

    'isAlreadyApplied': function (node) {
        return $(node).hasClass('js-textexpander');
    }
});



// -----------------------------------------------------------
// TEXT EXPANDER ELEMENT
// -----------------------------------------------------------
var TextExpanderElement = function(base, container, element) {
    this.base = base;
    this.container = container;

    if (!element) {
        var selection = window.getSelection();
        var range = selection.getRangeAt(0);
        var span = document.createElement('span');
        span.className = 'js-textexpander-temp';
        span.appendChild(range.extractContents());
        range.insertNode(span);
        selection.removeAllRanges();

        element = $('.js-textexpander-temp');
        element.removeClass('js-textexpander-temp');
        element.addClass('js-textexpander');
        element.attr('id', 'js-textexpander-' + new Date().getTime());
    }

    this.element = element;
    this.element.data('TextExpanderElement', this);
    this.id = this.element.attr('id');
    this.active = !!this.element.parent().length;

    this.closeBound = this.close.bind(this);

    this.modal = $('<div class="js-textexpander-modal"> \
                        <div class="js-textexpander-modal-content">\
                            <textarea></textarea>\
                            <button type="button">Annuleren</button>\
                            <button type="button">OK</button>\
                        </div>\
                    </div>');
    this.modalTextarea = this.modal.find('textarea');
    this.modalClose = this.modal.find('button').first();
    this.modalClose.on('click', this.closeBound);
    this.modalSave = this.modal.find('button').last();
    this.modalSave.on('click', this.save.bind(this));

    this.content = $('.js-textexpander-content[rel="' + this.id + '"]');
    if (!this.content.length) {
        this.content = $('<div class="js-textexpander-content" rel="' + this.id + '"></div>');
        if (this.active) {
            this.container.append(this.content);
            this.show();
        }
    }

    this.container.bind('DOMSubtreeModified', this.change.bind(this));
};

TextExpanderElement.prototype.change = function() {
    if (this.element.parent().length) {
        if (!this.active) {
            this.active = true;
            this.container.append(this.content);
        }
    } else if (this.active) {
        this.active = false;
        this.content.detach();
    }
};

TextExpanderElement.prototype.show = function() {
    this.modalTextarea.val(this.content.html());

    $body.append(this.modal);
    var o = this.element.offset();
    var w = this.modal.outerWidth();
    this.modal.css({
        'left': Math.max(10, Math.min($window.width() - w - 10, (o.left + this.element.outerWidth() / 2) - (w / 2))),
        'top': Math.max(10, o.top - this.modal.outerHeight() - 20)
    });

    setTimeout(function() {
        this.modalCKE = this.modalTextarea.ckeditor().editor;
    }.bind(this), 100);
};

TextExpanderElement.prototype.save = function() {
    this.content.html(this.modalTextarea.val());
    this.base.trigger('editableInput', this.base.elements[0], this.base.elements[0]);
    this.hide();
};

TextExpanderElement.prototype.close = function(e) {
    if (e.target == this.modalClose[0] || !this.modal.find(e.target).length) {
        this.hide();
    }
};

TextExpanderElement.prototype.hide = function() {
    if (!this.content.text().length) {
        this.content.remove();
        this.element.contents().unwrap();
    }
    this.modalCKE.destroy();
    this.modal.detach();
};
