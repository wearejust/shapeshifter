// -----------------------------------------------------------
// DOCUMENT ON READY 
// -----------------------------------------------------------
$(function() {
	window.AJAX_HEADERS = {
		'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
	};

	Menu = new Menu();
	Required = new Required();
	ImageSizeChecker = new ImageSizeChecker();

    $('label.js-placeholder').placeholderText();

	$('.tab-list').tabbed();
	$('.js-datatable').sortableTable();
	$('.acc-container').accordion();

	var el;
	$(".tokeninput").each(function(){
		el = $(this);
		el.selectize({
			valueField: 'id',
			labelField: 'name',
			searchField: 'name',
			'items' : el.data('prepopulate'),
			'options' : el.data('allresults'),
			'plugins' : [
				'drag_drop',
				'remove_button'
			]
		});
	});

	$(".js-mask").each(function(i, element) {
		element = $(element);
		element.mask(element.attr('data-mask'));
	});

	$(".colorpicker").spectrum({
		showInput: true,
		showPalette: true,
		showInitial: true,
		allowEmpty: true
	});

	$('a.js-medialibrary').on('click', function(e) {
		e.preventDefault();

		$.colorbox({
			href: $(this).attr('href'),
			fastIframe: true,
			iframe: true,
			width: '80%',
			height: '80%',
			fixed: true
		});
	});

	$('.datepicker').datepicker();
	$('.embedded-video').videoPreview();
	$('.onetomany-relation-content').oneToManyLoader();
	$('.confirm-delete-dialog').removeDialog();
	$('.js-image-delete-dialog').removeImageDialog();

    $('.js-simplefileattribute').simplefileattribute();
    $('.js-multiplefileattribute').multiplefileattribute();


	$('.form-group-ckeditor').on('click', function(e) {
		e.preventDefault();
		var id = $(e.currentTarget).find('.cke').attr('id').replace('cke_', '');
		CKEDITOR.instances[id].focus();
	});

    CKEDITOR.disableAutoInline = true;
	CKEDITOR.on('instanceReady', function(e) {
		e.editor.on('focus', function(e) {
			$(e.editor.container.$).closest('.form-group').addClass('highlight');
		});
		e.editor.on('blur', function(e) {
			$(e.editor.container.$).closest('.form-group').removeClass('highlight');
		});
	}.bind(this));


	alertShow();

	/*
	 $('.sortable').nestedSortable({
	 forcePlaceholderSize: true,
	 handle: 'div',
	 items: 'li',
	 toleranceElement: '> div',
	 placeholder: 'sortable-placeholder',
	 protectRoot: true
	 // isAllowed: function(item, parent) {
	 //     $('.form-group').css('opacity', 0);
	 //     return true;
	 // }
	 });

	 $('.sortable ul ul, .sortable ul').hide();
	 $('.sortable-item').click(function(e){
	 $(e.currentTarget).find('~ ul').toggle();
	 $(this).toggleClass('open');
	 });
	 $('.sortable-item ul').on('click', function(e){
	 $(e.currentTarget).find('~ ul').toggle();
	 $(this).toggleClass('open');
	 });
	 */

});

function alertShow(message) {
    if (message) {
        $('.footer.controls').last().prepend(message);
    } else {
        message = $('.alert-success');
    }

    message.execute(function() {
        message.addClass('alert-success-active');
        message.execute(3500, function(){
            message.removeClass('alert-success-active');
            message.execute(1000, function(){
                message.remove();
            });
        });
    });
}



// -----------------------------------------------------------
// IMAGE SIZE CHECKER
// -----------------------------------------------------------
var ImageSizeChecker = function() {
    this.loader = new Image();
    this.loader.onload = this.loaded.bind(this);
    this.url = window.URL || window.webkitURL;
}

ImageSizeChecker.prototype.validate = function(images, options, callback) {
    this.options = options;
    this.callback = callback;
    this.errors = [];
    this.images = [];
    for (var i=0; i<images.length; i++) {
        this.images.push(images[i]);
    }
    this.check();
}

ImageSizeChecker.prototype.check = function() {
    if (this.images.length) {
        this.image = this.images.shift();
        if (this.options.size && this.image.size > this.options.size) {
            var base = Math.log(this.image.size) / Math.log(1024);
            var suffix = ['', 'K', 'M', 'G', 'T'][Math.floor(base)];
            var size = (Math.round(Math.pow(1024, base - Math.floor(base)) * 100) / 100);
            if (size >= 100) size = Math.round(size);
            this.errors.push(this.image.name + ' is too large (' + size + suffix + 'B)');
        }

        if (this.options.width || this.options.height) {
            this.loader.src = this.url.createObjectURL(this.image);
        } else {
            this.check();
        }

    } else {
        if (this.errors.length) {
            alert(this.errors.join("\n"));
        }
        this.callback(!this.errors.length);
    }
}

ImageSizeChecker.prototype.loaded = function(e) {
    if (this.options.width && e.currentTarget.width > this.options.width) {
        this.errors.push(this.image.name + ' is too wide (' + e.currentTarget.width + 'px)');
    } else if (this.options.height && e.currentTarget.height > this.options.height) {
        this.errors.push(this.image.name + ' is too high (' + e.currentTarget.height + 'px)');
    }
    this.check();
}



// -----------------------------------------------------------
// MENU
// -----------------------------------------------------------
var Menu = function() {
	this.element = $('.header, .header-top');
	if (!this.element.length) return;

	this.content = this.element.siblings('.content-wrapper');

	this.overlay = $('<div class="menu-overlay"></div>');
	$body.append(this.overlay);

	this.subs = this.element.find('.sub-list');
	this.subs.each(function(index, item) {
		item = $(item);
		if (item.hasClass('js-hide')) {
			item.hide().removeClass('js-hide');
			item.siblings('.main-nav-link').on('click', this.subToggle.bind(this));
		}
	}.bind(this));

	$('.menu-nav-button').on('click', this.menuToggle.bind(this));

	if (TRANSFORM3D) {
		$window.on('touchstart', this.dragStart.bind(this));

		this.dragMoveCheckBound = this.dragMoveCheck.bind(this);
		this.dragMoveBound = this.dragMove.bind(this);
		this.dragStopBound = this.dragStop.bind(this);
	}
}

Menu.prototype.menuToggle = function(e) {
	e.preventDefault();
	$body.toggleClass('menu-active');
}

Menu.prototype.subToggle = function(e) {
	e.preventDefault();
	var sub = $(e.currentTarget).siblings('.sub-list');
	var boo = sub.hasClass('active');
	this.subs.filter('.active').removeClass('active').slideUp();
	if (!boo) sub.addClass('active').slideDown();
}

Menu.prototype.dragStart = function(e) {
	var touch = e.originalEvent.touches[0];
	if (!$(e.target).is('a, button, input') && (touch.pageX > ($window.width() - 50) || $(e.target).hasClass('menu-overlay'))) {
		this.dragData = {
			'scrollTop': $window.scrollTop() || -parseFloat(this.content.css('top')),
			'direction': $body.hasClass('menu-active') ? 1 : -1,
			'x': touch.pageX,
			'y': touch.pageY
		};

		this.element.addClass('no-transition');
		this.overlay.addClass('no-transition').execute(this, function() {
			this.overlay.css('opacity', this.overlay.css('opacity'));
			this.overlay.addClass('active');
			$window.on('touchmove', this.dragMoveCheckBound);
			$window.on('touchend', this.dragStopBound);
		});
	}
}

Menu.prototype.dragMoveCheck = function(e) {
	var touch = e.originalEvent.touches[0];
	if (Math.abs(touch.pageX - this.dragData.x) >= 3) {
		e.preventDefault();

		this.content.css({
			'position': 'fixed',
			'top': -this.dragData.scrollTop + 'px'
		});

		this.dragData.width = this.element.width();
		var right = this.element.transformed().x;
		this.dragData.right = right;
		this.dragData.rightPrevious = right;

		$window.off('touchmove', this.dragMoveCheckBound);
		$window.on('touchmove', this.dragMoveBound);

	} else if (Math.abs(touch.pageY - this.dragData.y) >= 3) {
		this.dragStop();
	}

}

Menu.prototype.dragMove = function(e) {
	e.preventDefault();
	var touch = e.originalEvent.touches[0];
	var right = Math.min(0, this.dragData.right + (touch.pageX - this.dragData.x));
	var n = Math.min(1, Math.abs(right / this.dragData.width));
	this.element.translate((n * -100) + '%');
	this.overlay.css('opacity', n * 0.7);

	n = right - this.dragData.rightPrevious;
	this.dragData.rightPrevious = right;

	if (Math.abs(n) > 1) {
		this.dragData.direction = (n > 0) ? -1 : 1;
	}
}

Menu.prototype.dragStop = function(e) {
	$window.off('touchmove', this.dragMoveCheckBound);
	$window.off('touchmove', this.dragMoveBound);
	$window.off('touchend', this.dragStopBound);

	this.element.removeClass('no-transition');
	this.overlay.removeClass('active no-transition').execute(this, function() {

		this.overlay.css('opacity', '');
		this.element.translate();
		var boo = this.dragData.direction == 1;
		$body.toggleClass('menu-active', boo);

		if (!boo) {
			this.content.css({
				'position': '',
				'top': ''
			});
			$window.scrollTop(this.dragData.scrollTop);
		}
	});
}



// -----------------------------------------------------------
// PLACEHOLDERTEXT
// -----------------------------------------------------------
var PlaceholderText = function() {
    this.init.apply(this, arguments);
}

PlaceholderText.prototype.default_settings = {
    'activeClass': 'active',
    'focusClass': 'focus'
}

PlaceholderText.prototype.init = function(node, settings) {
    this.settings = $.extend({}, this.default_settings, settings);
    this.label = $(node);
    this.input = $('#'+this.label.attr('for'));
    this.activeClass = this.settings.activeClass;
    this.focusClass = this.settings.focusClass;

    this.input.on('focus', this.focus.bind(this));
    this.input.on('blur', this.blur.bind(this));
    this.input.on('keydown keyup', this.keyPress.bind(this));

    this.firstInit();
}

PlaceholderText.prototype.focus = function() {
    this.label.addClass(this.focusClass);
}

PlaceholderText.prototype.blur = function() {
    if (!this.input.val()) {
        this.label.removeClass(this.activeClass);
    }
    this.label.removeClass(this.focusClass);
}

PlaceholderText.prototype.keyPress = function() {
    if (!this.input.val()) {
        this.label.removeClass(this.activeClass);
    } else {
        this.label.addClass(this.activeClass);
    }
}

PlaceholderText.prototype.firstInit = function() {
    if (this.input.val() || this.input.text()) {
        this.label.addClass(this.activeClass);
    } else {
        this.label.removeClass(this.activeClass);        
    }
}

$.fn.placeholderText = function(settings) {
    $(this).each(function(index, item) {
        $item = $(item);
        var instance = $item.data('placeholderText');
        if (!instance) {
            $item.data('placeholderText', new PlaceholderText(item, settings));
        }
    });
}



// -----------------------------------------------------------
// ACCORDION
// -----------------------------------------------------------
var Accordion = function() {
	this.init.apply(this, arguments);
}

Accordion.prototype.default_settings = {
	'containerClass': 'acc-container',
	'triggerClass': 'acc-trigger',
	'contentClass': 'acc-content',
	'activeClass': 'acc-active',
	'speed': 300
}

Accordion.prototype.init = function(node, settings) {
	this.settings = $.extend({}, this.default_settings, settings);
	this.container = $(node);
	this.trigger = this.container.find('.'+this.settings.triggerClass);
	this.content = this.container.find('> .'+this.settings.contentClass);
	this.active = this.settings.activeClass;
	this.speed = this.settings.speed;
	$window = $(window);

	this.checkStatus();

	this.checkStatusHandler = this.checkStatus.bind(this)
	this.checkStatusHandler = this.checkStatus.bind(this)
	this.trigger.bind('click', this.checkStatusHandler);
	this.bindClick = false;

	if (this.container.hasClass('is-desktop')) {
		$window.bind('resize', this.killAccordion.bind(this));
		this.killAccordion();
	}
}

Accordion.prototype.killAccordion = function() {
	if ($window.width() >= 800) {
		this.container.addClass(this.active);
		this.content.show();
		this.trigger.unbind('click', this.checkStatusHandler);
		this.bindClick = true;
	} else if (this.bindClick) {
		this.bindClick = false;
		this.trigger.bind('click', this.checkStatusHandler);
	}
}

Accordion.prototype.checkStatus = function(e) {
	if (e) {
		e.preventDefault();

		this.container = $(e.currentTarget).closest('.'+this.settings.containerClass);
		this.content = this.container.find('> .'+this.settings.contentClass);

		if (this.container.hasClass(this.active)) {
			this.hideContent()
		} else {
			this.showContent();
		}
	} else {
		if (this.container.hasClass(this.active)) {
			this.content.show();
		} else {
			this.content.hide();
		}
	}
}

Accordion.prototype.showContent = function() {
	this.content.slideDown(this.speed, function() {
		this.container.addClass(this.active);
	}.bind(this));
}

Accordion.prototype.hideContent = function() {
	this.content.slideUp(this.speed, function() {
		this.container.removeClass(this.active);
	}.bind(this));
}

$.fn.accordion = function(settings) {
	$(this).each(function(index, item) {
		item = $(item);
		var instance = item.data('accordion');
		if (!instance) {
			item.data('accordion', new Accordion(this, settings))
		}
	})
}


// -----------------------------------------------------------
// SIMPLE FILE ATTRIBUTE
// -----------------------------------------------------------
$.fn.simplefileattribute = function() {
    return $(this).each(function(index, item) {
        item = $(item);
        if (!item.data('simplefileattribute')) {
            item.data('simplefileattribute', new SimpleFileAttribute(item));
        }
    });
}

var SimpleFileAttribute = function(element) {
    if (!element.length) return;
    this.element = element;

    this.validation = {
        'width': parseInt(this.element.attr('data-max-width')),
        'height': parseInt(this.element.attr('data-max-height')),
        'size': parseInt(this.element.attr('data-max-size'))
    };

    this.input = this.element.find('input');
    this.input.on('change', this.validate.bind(this));
}

SimpleFileAttribute.prototype.validate = function(e) {
    ImageSizeChecker.validate(e.currentTarget.files, this.validation, function(passed) {
        if (!passed) {
            this.input.wrap('<form></form>');
            this.input.parent().get(0).reset();
            this.input.unwrap();
        }
    }.bind(this));
}



// -----------------------------------------------------------
// MULTIPLE FILE ATTRIBUTE
// -----------------------------------------------------------
var MultipleFileAttributes = function() {
	this.items = [];
}
MultipleFileAttributes.prototype.add = function(item) {
	this.items.push(item);
	return this.items.length;
}
MultipleFileAttributes.prototype.loaders = function(storageDir, amount) {
	var i, item;
	for (i=0; i<this.items.length; i++) {
		item = this.items[i];
		if (item.storageDir == storageDir) {
			item.loaders(amount);
		}
	}
}
MultipleFileAttributes.prototype.update = function(storageDir, data) {
	var i, item;
	for (i=0; i<this.items.length; i++) {
		item = this.items[i];
		if (item.storageDir == storageDir) {
			item.update(data);
		}
	}
}
MultipleFileAttributes = new MultipleFileAttributes();

$.fn.multiplefileattribute = function() {
	return $(this).each(function(index, item) {
		item = $(item);
		if (!item.data('multiplefileattribute')) {
			item.data('multiplefileattribute', new MultipleFileAttribute(item));
		}
	});
}

var MultipleFileAttribute = function(element) {
	if (!element.length) return;
	this.element = element;
	this.id = MultipleFileAttributes.add(this);

	this.storageDir = this.element.attr('data-storage-dir');
	this.name = this.element.attr('data-name');
    this.uploadValidation = {
        'width': parseInt(this.element.attr('data-max-width')),
        'height': parseInt(this.element.attr('data-max-height')),
        'size': parseInt(this.element.attr('data-max-size'))
    };

	var input = this.element.find('.mini-gallery-add-button').on('change', this.upload.bind(this));
	input.closest('.mini-gallery-list-item').addClass('mini-gallery-list-item-input').removeClass('hide');

	this.dragInput = this.element.find('.mini-gallery-add-button').clone();
	this.dragInput.addClass('js-multiplefileattribute-draginput');
	this.dragInput.wrap('<div class="js-multiplefileattribute-draginput-wrap fill"></div>');
	this.dragInputWrap = this.dragInput.parent();
	this.element.prepend(this.dragInputWrap);
	this.element.on('dragover dragleave', this.drag.bind(this));
	this.element.find('.mini-gallery-add-button').on('change', this.upload.bind(this));

    this.preview = this.element.find('.js-multiplefileattribute-preview');
    this.previewPlaceholder = this.element.find('.js-multiplefileattribute-preview-placeholder');

    this.clearButton = this.element.find('.btn-remove');
    this.clearButton.on('click', this.clear.bind(this));
	
	this.list = this.element.find('.mini-gallery-list');
	this.item = this.list.find('.mini-gallery-list-item').last();

    this.inputs = this.list.find('.mini-gallery-input');
    this.inputs.on('change', this.change.bind(this));

    var selected = this.inputs.filter(':checked');
    if (selected.length) {
        selected = selected.closest('.mini-gallery-list-item');
        this.list.animate({'scrollTop':selected.position().top});
    }

	if (this.item.hasClass('hide')) {
		this.item.remove();
		this.item.removeClass('hide');
	} else {
		this.item = this.item.clone();
		this.item.find('img').attr('src', '');
	}
	this.item.find('.mini-gallery-input').prop('checked', false);
	this.item.find('label').addClass('loader');
}

MultipleFileAttribute.prototype.drag = function(e) {
	if (e && e.type == 'dragover') {
		this.dragInputWrap.execute(this, function() {
			this.dragInputWrap.addClass('active');
		});
	} else {
		this.dragInputWrap.execute(this, 100, function() {
			this.dragInputWrap.removeClass('active');
		});
	}
}

MultipleFileAttribute.prototype.upload = function(e) {
	if (!this.loading && e.currentTarget.files) {
        this.loading = true;
		this.drag();
        ImageSizeChecker.validate(e.currentTarget.files, this.uploadValidation, function(passed) {
            if (passed) {
                MultipleFileAttributes.loaders(this.storageDir, e.currentTarget.files.length);
                this.uploadInput = $(e.currentTarget);
                this.uploadInput.wrap('<form class="js-multiplefileattribute-form" action="/admin/ajax/upload" method="POST" multipart/form-data></form>');
                this.uploadInput.execute(this, function() {
                    this.uploadInput.parent().ajaxSubmit({
                        'headers': window.AJAX_HEADERS,
                        'data': { 'storagedir': this.storageDir },
                        'success': this.uploaded.bind(this)
                    });
                });

            } else {
                setTimeout(function() {
                    this.loading = false;
                }.bind(this));
            }
        }.bind(this));
	}
}

MultipleFileAttribute.prototype.loaders = function(amount) {
	this.list.find('.mini-gallery-input-update').removeClass('mini-gallery-input-update');

	var i, item, input;
	for (i=0; i<amount; i++) {
		item = this.item.clone();
		item.find('label').attr('for', 'multiplefileattribute-' + this.id + '-radio-' + i);
		item.find('img').addClass('hide');
		input = item.find('.mini-gallery-input');
		input.attr('id', 'multiplefileattribute-' + this.id + '-radio-' + i);
		input.attr('value','');
		input.on('change', this.change.bind(this));
		this.list.append(item);
	}
}

MultipleFileAttribute.prototype.uploaded = function(data) {
    this.uploadInput.unwrap();
	this.list.find('.mini-gallery-input').prop('checked', false);
	this.drag();

	MultipleFileAttributes.update(this.storageDir, data);

	var item = this.list.find('.mini-gallery-input-update');
	if (item.length) {
		item.removeClass('mini-gallery-input-update');
		item.prop('checked', true);
		item.trigger('change');
	}

	this.list.execute(this, function() {
		this.list.find('label.loader').closest('.mini-gallery-list-item').remove();
        this.list.stop(true).animate({'scrollTop':this.list.children().last().position().top});
		this.loading = false;
	});
}

MultipleFileAttribute.prototype.update = function(data) {
	var i = 0, str, item, items = this.list.find('label.loader');
	for (str in data) {
		this.list.find('.mini-gallery-input[value="' + str + '"]').closest('.mini-gallery-list-item').remove();
		item = items.eq(i).closest('.mini-gallery-list-item');
		item.attr('title', str);
		item.find('label').removeClass('loader');
		item.find('img').removeClass('hide').attr('src', data[str]);
		item = item.find('.mini-gallery-input');
		item.val(str);
		if (!i) item.addClass('mini-gallery-input-update');
		i++;
	}
}

MultipleFileAttribute.prototype.change = function(e) {
	var str = this.storageDir + $(e.currentTarget).val();
	this.preview.css('background-image', "url('" + str + "')");
    this.previewPlaceholder.addClass('hide');
    this.clearButton.removeClass('hide');
}

MultipleFileAttribute.prototype.clear = function() {
    this.inputs.prop('checked', false);
    this.preview.css('background-image', '');
    this.previewPlaceholder.removeClass('hide');
    this.clearButton.addClass('hide');
    this.element.append('<input type="hidden" name="delete-image[]" value="' + this.name + '">');
}



// -----------------------------------------------------------
// ONETOMANY LOADER
// -----------------------------------------------------------
var OneToManyLoader = function(node) {
	this.container = $(node);
	this.url = this.container.data('url');
	this.loader = $('<div><h1>' + this.container.attr('data-title') + '</h1><img src="/packages/just/shapeshifter/css/images/ajax-loader-2.gif"></div>');

	this.ajax();
}

OneToManyLoader.prototype.ajax = function() {
	this.container.prepend(this.loader);

	$.ajax({
		'type': 'GET',
		'url': this.url,
		'headers': window.AJAX_HEADERS,
		'success': function (data) {
			data = $(data).find('.content-body-inner');
			this.container.append(data);

			$('.js-datatable').sortableTable();
			$('.confirm-delete-dialog').removeDialog();

		}.bind(this),
		'error': function (data) {
			console.log(JSON.parse(data.responseText).error);
		}.bind(this),
		'complete': function () {
			this.loader.detach();
		}.bind(this)
	});
}

$.fn.oneToManyLoader = function() {
	$(this).each(function(index, el) {
		el = $(el);
		var instance = el.data('oneToManyLoader');
		if (!instance) {
			el.data('oneToManyLoader', new OneToManyLoader(this));
		}
	})
}




// -----------------------------------------------------------
// REMOVE DIALOG
// -----------------------------------------------------------
var RemoveDialog = function(node) {

	this.trigger = $(node);
	this.container = this.trigger.closest('.js-remove-wrapper');

	this.dialogContainer = this.container.find('.dialog-confirm');
	this.form = this.container.find('form');

	this.dialogContainer.dialog({
		'resizable': false,
		'modal': true,
		'autoOpen': false,
		'buttons': [{
            'class': 'btn btn-default',
            'text': 'Ja, verwijderen',
            'click': function () {
                this.form.submit();
            }.bind(this)
        },
        {
            'class': 'btn btn-cancel',
            'text': 'Nee, toch niet',
            'click': function () {
                $(this).dialog('close');
            }
        }]
	});

	this.trigger.on('click', this.showDialog.bind(this));
}

RemoveDialog.prototype.showDialog = function(e)
{
	e.preventDefault();

	this.dialogContainer.dialog("open");
}


$.fn.removeDialog = function() {
	$(this).each(function(index, el) {
		el = $(el);
		var instance = el.data('removeDialog');
		if (!instance) {
			el.data('removeDialog', new RemoveDialog(this));
		}
	})
}

// -----------------------------------------------------------
// REMOVE DIALOG IMAGE
// -----------------------------------------------------------
var RemoveImageDialog = function(node) {

	this.trigger = $(node);
	this.container = this.trigger.parents('.media-wrapper');

	this.dialogContainer = this.container.find('.dialog-confirm');
	this.form = this.container.closest('form');

	this.dialogContainer.dialog({
		'resizable': false,
		'modal': true,
		'autoOpen': false,
		'buttons': [{
            'class': 'btn btn-default',
            'text': 'Ja, verwijderen',
            'click': function () {
                this.form.append('<input type="hidden" name="delete-image[]" value="' + this.trigger.attr('data-name') + '">');
                this.container.remove();

                this.dialogContainer.dialog('close');
            }.bind(this)
        },
        {
            'class': 'btn btn-cancel',
            'text': 'Nee, toch niet',
            'click': function () {
                $(this).dialog('close');
            }
        }]
	});

	this.trigger.on('click', this.showDialog.bind(this));
}

RemoveImageDialog.prototype.showDialog = function(e)
{
	e.preventDefault();

	this.dialogContainer.dialog("open");
}

$.fn.removeImageDialog = function() {
	$(this).each(function(index, el) {
		el = $(el);
		var instance = el.data('removeImageDialog');
		if (!instance) {
			el.data('removeImageDialog', new RemoveImageDialog(this));
		}
	})
}



// -----------------------------------------------------------
// TABBED
// -----------------------------------------------------------
$.fn.tabbed = function(options) {
    var items = $(this);
    items.each(function(index, item) {
        item = $(item);
        if (!item.data('tabbed')) {
            item.data('tabbed', new Tabbed(options, item));
        }
    });
    return items;
}

var Tabbed = function(options, element) {
    this.element = $(element);
    this.tabs = this.element.find('a');

    var str;
    this.pages = $([]);
    this.tabs.each(function(index, item) {
        str = $(item).attr('href');
        $(str +',' + str + '-extra').each(function(index, item) {
            item = $(item);
            item.removeClass('js-hide');
            item.attr('id', 'js-' + item.attr('id'));
            this.pages = this.pages.add(item);
        }.bind(this));
    }.bind(this));

    $window.hashchange(this.change.bind(this));
    setTimeout(this.change.bind(this), 100);
}

Tabbed.prototype.change = function(e) {
    var hash = window.location.hash.replace('#','');
    var tab = this.tabs.filter('[href="#'+hash+'"]');
    if (!e && !tab.length) {
        tab = this.tabs.eq(0);
        hash = tab.attr('href').replace('#','');
    }

    this.tabs.removeClass('tab-list-item-button-active');
    tab.addClass('tab-list-item-button-active');

    this.pages.hide();
    this.pages.filter('#js-' + hash + ', #js-' + hash + '-extra').show();
}



// -----------------------------------------------------------
// VIDEO PREVIEW
// -----------------------------------------------------------
$.fn.videoPreview = function(options) {
	var items = $(this);
	items.each(function(index, item) {
		item = $(item);
		if (!item.data('videoPreview')) {
			item.data('videoPreview', new VideoPreview(options, item));
		}
	});
	return items;
}

var VideoPreview = function(options, element) {
	this.element = $(element);
	this.input = this.element.find('.embedded-video-input');
	this.input.on('change keyup', this.change.bind(this));

	this.patternVimeo = /(videos|video|channels|\.com)\/([\d]+)/;
	this.patternYoutube = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;

	this.change();
}

VideoPreview.prototype.change = function() {
	var val = this.input.val();
	if (this.val == val) return;
	this.val = val;

	var vimeo = this.val.match(this.patternVimeo);
	var youtube = this.val.match(this.patternYoutube);

	src = '';
	if (vimeo && vimeo[2].length) {
		src = '//player.vimeo.com/video/' + vimeo[2] + '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff';
	} else if (youtube && youtube[1].length) {
		src = '//youtube.com/embed/' + youtube[1] + '?modestbranding=1&showinfo=0&rel=0&autohide=1&iv_load_policy=3&hd=1';
	}

	if (this.video) {
		this.video.remove();
	}

	if (src.length) {
		this.video = $('<span class="section block container paragraph" style="z-index: 2;"> \
							<span class="loader video-preview-loader"></span> \
							<span class="hide section section-end paragraph video"> \
								<iframe src="' + src + '" width="522" height="380" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe> \
							</span> \
						</span>');
		this.video.find('iframe').on('load', function() {
			this.video.find('.video-preview-loader').remove();
			this.video.find('.video').removeClass('hide');
		}.bind(this));
		this.element.append(this.video);
	}
}




// -----------------------------------------------------------
// SORTABLE TABLE
// -----------------------------------------------------------
$.fn.sortableTable = function(options) {
	var items = $(this);
	items.each(function(index, item) {
		item = $(item);
		if (!item.data('sortableTable')) {
			item.data('sortableTable', new SortableTable(options, item));
		}
	});
	return items;
}

var SortableTable = function(options, table) {
	this.options = $.extend({
		'itemsMax': 20
	}, options);

	this.table = table;
	this.element = this.table.closest('.content-body');
	this.tbody = this.table.find('tbody');

	this.options.itemsMaxRanged = this.options.itemsMax + 5;
	this.options.sortable = this.table.hasClass('js-datatable-order');
	this.defaultOrderIndex = this.table.find('[data-header-title="'+this.table.attr('data-sort-column')+'"]').index();
	this.defaultOrder = this.table.attr('data-sort-order');

	this.element.find('.search-control').on('keyup blur', this.search.bind(this));

	this.toggleButton = $('<button class="btn add-item-button" type="button" style="display: none;">Show <span class="toggle-button-amount"></span> <span class="toggle-button-more">more</span><span class="toggle-button-less" style="display: none;">less</span></button>');
	this.element.append(this.toggleButton);
	this.toggleButton.on('click', this.itemsToggle.bind(this));
	this.toggleButtonAmount = this.toggleButton.find('.toggle-button-amount');
	this.toggleButtonMore = this.toggleButton.find('.toggle-button-more');
	this.toggleButtonLess = this.toggleButton.find('.toggle-button-less');


	$.fn.dataTableExt.oStdClasses.sRowEmpty = "table-cell";
	$.fn.dataTableExt.oStdClasses.sSortDesc = 'table-header-sort-item-active-asc';
	$.fn.dataTableExt.oStdClasses.sSortAsc = 'table-header-sort-item-active-desc';

	if (this.defaultOrderIndex >= 0 && this.table.hasClass('js-datatable-sortable')) {
		var sorting = [[this.defaultOrderIndex, this.defaultOrder]];
	}else {
		var sorting = [];
	}

	this.table = this.table.dataTable({
		"aaSorting": sorting,
		'sDom': 't',
		'aoColumnDefs': [{
			'bSortable': false,
			'aTargets': ['js-disable-sort']
		}],
		'bPaginate': false,
		'bSort' : !this.options.sortable,
		"oLanguage": {
			"sProcessing": "Laden…",
			"sLengthMenu": "_MENU_ resultaten weergeven",
			"sZeroRecords": "Nee, geen resultaten.",
			"sInfo": "_START_ to _END_ from _TOTAL_ results",
			"sInfoEmpty": "Nee, geen items.",
			"sInfoFiltered": " (gefilterd uit _MAX_ resultaten)",
			"sInfoPostFix": "",
			"sEmptyTable": "Nee, geen resultaten in de tabel.",
			"sInfoThousands": ".",
			"sLoadingRecords": "Een moment geduld a.u.b. - bezig met laden…",
			"oPaginate": {
				"sFirst": "Eerste",
				"sLast": "Laatste",
				"sNext": "Volgende",
				"sPrevious": "Vorige"
			},
			"sSearchPlaceholder": 'Zoeken'
		}
	});

	this.items = this.tbody.children();
	this.items.filter('.table-row-editable').find('td:not(.table-order, .table-control)').on('click', function(e) {
		var url = $(e.currentTarget).closest('.table-row').attr('data-edit-href');
		if (e.button || e.ctrlKey || e.metaKey) {
			window.open(url);
		} else {
			window.location = url;
		}
	});

	if (TOUCH) {
		this.items.filter('.table-row-deletable').on('touchstart', function(e) {
			var t = e.originalEvent.touches[0];
			this.itemTouchStart = {
				'pageX': t.pageX,
				'pageY': t.pageY
			};
			$(e.currentTarget).on('touchmove', function(e) {
				var t = e.originalEvent.touches[0];
				var x = t.pageX - this.itemTouchStart.pageX;
				var y = Math.abs(t.pageY - this.itemTouchStart.pageY);
				if (Math.abs(x) > 10 && y < 5) {
					e.preventDefault();
					var item = $(e.currentTarget);
					this.items.removeClass('table-control-active');
					if (x < 0) item.addClass('table-control-active');
					item.off('touchmove');
				}
			}.bind(this));
		}.bind(this));
	}

	if (this.options.sortable) {
		this.sortHandles = this.tbody.find('.js-sortable-handle');

		this.tbody.sortable({
			'axis': 'y',
			'cancel': 'input,textarea,select,option,button:not(.js-sortable-handle)',
			'handle': '.js-sortable-handle',
			'containment':'parent',
			'tolerance': 'pointer',
			'revert': 100,
			'cursor': 'move',
			'zIndex': 1,
			'update': this.update.bind(this),
            'start': function(e, ui) {
                this.tbody.addClass('sortable-dragging');
            }.bind(this),
            'stop': function() {
                this.tbody.removeClass('sortable-dragging');
            }.bind(this),
			'helper': function(e, ui) {
				if (this.message) this.message.remove();
				ui.css('width','100%');
				ui.children().each(function() {
					var item = $(this);
					item.width(item.width());
				});
				return ui;
			}.bind(this)
		});
	}

	this.itemsHide();

	this.element.execute(this, function() {
		this.wrap = this.element.find('.dataTables_wrapper');
		this.wrap.css('position', 'relative');
	});
}

SortableTable.prototype.wrapLock = function(release) {
	if (release) {
		this.wrap.css({
			'height': '',
			'overflow': ''
		});

	} else {
		this.wrap.css({
			'height': (this.wrap.outerHeight() + parseFloat(this.table.css('margin-bottom'))) + 'px',
			'overflow': 'hidden'
		});
	}
}


SortableTable.prototype.update = function() {
	var order = this.tbody.sortable('toArray', {'attribute': 'data-record-id'});
	if (this.defaultOrder == 'desc') {
		order.reverse();
	}

	$.ajax({
		'type': 'POST',
		'url': '/admin/ajax/sortorderchange',
		'headers': window.AJAX_HEADERS,
		'dataType': 'json',
		'data': {
			'order': order,
			'model': model,
			'url': window.location.pathname,
			'relation': this.tbody.closest('.onetomany-relation-content').attr('data-relation')
		},
		'success': function(data) {
			this.message = $(data.message);
			alertShow(this.message);
		}.bind(this),
		'error': function() {
			//alert('Error: Neem contact op met Just.');
		}.bind(this)
	});
}

SortableTable.prototype.search = function(e) {
	var value = $(e.currentTarget).val();
	this.wrapLock();
	this.tbody.children().show().execute(this, function() {
		this.table.fnFilter(value);
		this.toggleButton.hide();
		this.toggleButtonMore.hide();
		this.toggleButtonLess.hide();

		if (this.tbody.children().length > this.options.itemsMaxRanged) {
			this.itemsHide();
		}
		this.wrapLock(true);
		if (this.options.sortable) {
			this.sortHandles.toggle(!value.length);
		}
	});
}

SortableTable.prototype.itemsToggle = function(e) {
	if (this.toggleButtonMore.is(':visible')) {
		this.itemsShow(e);
	} else {
		this.itemsHide(e);
	}
}

SortableTable.prototype.itemsHide = function(e) {
	var items = this.tbody.children();
	if (items.length > this.options.itemsMaxRanged) {
		this.toggleButton.show();
		this.toggleButtonMore.show();
		this.toggleButtonLess.hide();

		if (!e) {
			this.toggleButtonAmount.text(items.length - this.options.itemsMax);
			items.slice(this.options.itemsMax).hide();
		} else {
			var item = items.eq(this.options.itemsMax-1);
			this.wrap.css('overflow', 'hidden').animate({'height': (item.position().top + item.outerHeight() + parseFloat(this.table.css('margin-bottom')))+'px'}, function() {
				items.slice(this.options.itemsMax).hide();
				this.wrapLock(true);
			}.bind(this));
		}
	}
}

SortableTable.prototype.itemsShow = function(e) {
	var items = this.tbody.children();
	if (items.length > this.options.itemsMaxRanged) {
		this.wrapLock();

		items.slice(this.options.itemsMax).show();

		this.toggleButton.show();
		this.toggleButtonMore.hide();
		this.toggleButtonLess.show();

		var item = items.last();
		this.wrap.animate({'height': (item.position().top + item.outerHeight() + parseFloat(this.table.css('margin-bottom')))+'px'}, function() {
			this.wrapLock(true);
		}.bind(this));

	}
}



// -----------------------------------------------------------
// REQUIRED
// -----------------------------------------------------------
var Required = function() {
	this.items = $('.js-required');
	if (!this.items.length) return;

	this.targets = $('.js-required-target');
	this.delayer = $('<div></div>');

	CKEDITOR.on('instanceReady', function(e) {
		if (this.items.filter('#'+e.editor.name).length) {
			e.editor.on('blur', this.change.bind(this));
			this.change(e);
		}
	}.bind(this));

	this.items.on('keyup change', this.change.bind(this));
	this.change();
}

Required.prototype.change = function(e) {
	this.delayer.execute(this, e ? 300 : 0, function() {
		var disabled = false;
		this.items.each(function(index, item) {
			item = $(item);
			if (item.is('input[type="checkbox"], input[type="radio"]')) {
				if (!item.closest('.form-group').find('input:checked').length) {
					disabled = true;
				}

			} else if (item.hasClass('ckeditor')) {
				var cke = CKEDITOR.instances[item.attr('id')];
				if (!cke || !cke.getData()) {
					disabled = true;
				}

			} else if (item.hasClass('flatpickr-input')) {
				var datepicker = item.closest('.datepicker').data('Datepicker');
				if (!datepicker.validate()) {
					disabled = true;
				}

			} else if (!item.val()) {
				disabled = true;
			}

			if (disabled) return false;
		}.bind(this));
		this.targets.prop('disabled', disabled);
	});
}

// function to update the file selected by elfinder
function processSelectedFile(filePath, requestingField) {
	filePath = "/" + filePath;
	$('[rel="library-' + requestingField+'"]').val(filePath);
	$('[rel="library-' + requestingField+'-label"]').html(
		$('<a></a>').attr('href', filePath).text(filePath).attr('target', '_blank')
	);
}



// -----------------------------------------------------------
// DATEPICKER
// -----------------------------------------------------------
$.fn.datepicker = function() {
	return $(this).each(function(index, item) {
		item = $(item);
		if (!item.data('Datepicker')) {
			item.data('Datepicker', new Datepicker(item));
		}
	}.bind(this));
};

var Datepicker = function(element) {
	this.element = element;
	this.input = this.element.find('input');

	this.flatpickr = new flatpickr(this.element.get(0), {
		'dateFormat': 'd-m-Y',
		'timeFormat': 'H:i',
		'time_24hr': true,
		'onChange': function() {
			this.input.trigger('change');
		}.bind(this)
	});
};

Datepicker.prototype.validate = function() {
	var val = this.input.val();
	if (this.flatpickr.config.enableTime) {
		return /^(0[1-9]|[12]\d|3[01])-(0[1-9]|1[0-2])-(\d{4})\s([01]\d|2[0-4])\:([0-5]\d)$/.test(val);
	} else {
		return /^(0[1-9]|[12]\d|3[01])-(0[1-9]|1[0-2])-(\d{4})$/.test(val);
	}
};