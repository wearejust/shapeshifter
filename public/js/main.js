// -----------------------------------------------------------
// DOCUMENT ON READY 
// -----------------------------------------------------------
$(function() {
	
//    $('#dialog-confirm').dialog({autoOpen: false});

    Menu = new Menu();

	$('.js-datatable').sortableTable();
	$('.acc-container').accordion();
	$(".tokeninput").tokenInput(null,
	{
		theme: "facebook",
		preventDuplicates: true,
		hintText: "Typ om te zoeken",
		noResultsText: "Geen resultaten",
		searchingText: "Zoeken",
		processPrePopulate: true
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


	$('input.datepicker').datepicker({
		dateFormat: "dd-mm-yy"
	});

	$('input.datetimepicker').datetimepicker();
	$('.embedded-video').videoPreview();
	$('.onetomany-relation-content').oneToManyLoader();
	$('.confirm-delete-dialog').removeDialog();

	$('.alert-success').addClass('alert-success-active').execute(3800, function(){
		$(this).removeClass('alert-success-active');
	});

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


// -----------------------------------------------------------
// MENU
// -----------------------------------------------------------
var Menu = function() {
	this.element = $('#menu');
	if (!this.element.length) return;


	this.subs = this.element.find('.sub-list');
	this.subs.each(function(index, item) {
		item = $(item);
		if (item.hasClass('js-hide')) {
			item.hide().removeClass('js-hide');
			item.siblings('.main-nav-link').on('click', this.subToggle.bind(this));
		}
	}.bind(this));

	$('.menu-nav-button').on('click', this.menuToggle.bind(this));
}

Menu.prototype.menuToggle = function(e) {
	e.preventDefault();
	$body.toggleClass('menu-activated');
}
Menu.prototype.subToggle = function(e) {
	e.preventDefault();
	var sub = $(e.currentTarget).siblings('.sub-list');
	var boo = sub.hasClass('active');
	this.subs.filter('.active').removeClass('active').slideUp();
	if (!boo) sub.addClass('active').slideDown();
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
// FILE ATTRIBUTE
// -----------------------------------------------------------
var FileAttribute = function() {
	this.input = $('input[type="file"]');
	this.form = this.input.closest('form');

	this.input.on('change', this.change.bind(this));

	this.dialog();
}

FileAttribute.prototype.dialog = function() {
	this.confirmDialog = $('.dialog-confirm');

	this.confirmTrigger = $('.js-confirm-dialog-trigger');
	this.confirmTrigger.on('click', function(e) {
		e.preventDefault();
		this.confirmInit(e);
		this.confirmDialog.dialog('open');
	}.bind(this));
}

FileAttribute.prototype.confirmInit = function(e) {
	this.confirmDialog.dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
			'Ja, ik weet het zeker': function() {
				var name = $(e.currentTarget).attr('data-name');
				var hidden = '<input type="hidden" name="delete-image[]" value="' + name + '">';

				$(e.currentTarget).parents('form').append(hidden);
				$(e.currentTarget).closest('.js-image-container').find('img').slideUp('slow');

				$(this).dialog('close');
			},
			Annuleren: function() {
				$(this).dialog('close');
			}
		}
	});
}

FileAttribute.prototype.change = function(e) {
	var target = e.currentTarget;
	var $target = $(target);

	if (target.files && target.files[0]) {
		img.closest('.row').removeClass('hide');
		var reader = new FileReader();
		reader.onload = function(e) {
			if (!img.attr('data-original')) {
				img.attr('data-original', img.attr('src'));
			}
			if (e.target.result.indexOf('image/') != -1) {
				img.attr('src', e.target.result);
			} else {
				img.addClass("hide");
			}
		}
		reader.readAsDataURL(target.files[0]);
	} else {
		if (!$target.val()) {
			img.closest('.row').addClass('hide');
		}
		img.attr('src', img.attr('data-original'));
	}
}



// -----------------------------------------------------------
// ONETOMANY LOADER
// -----------------------------------------------------------
var OneToManyLoader = function(node) {
	this.container = $(node);
	this.url = this.container.data('url');

	this.ajax();
}

OneToManyLoader.prototype.ajax = function()
{
	$.ajax({
		type: "GET",
		url: this.url,
		success: function (data) {
			data = $(data);
			container = data.find('.content-body-inner');

			this.container.append(container);

            $('.js-datatable').sortableTable();
			$('.confirm-delete-dialog').removeDialog();

		}.bind(this),
		error: function (data) {
			console.log(JSON.parse(data.responseText).error);
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
	this.container = this.trigger.parents('td');
	this.dialogContainer = $('#dialog-confirm');
	this.form = this.container.find('form').first();

	this.dialogContainer.dialog({
        'resizable': false,
        'modal': true,
        'autoOpen': false,
        'position': 'center',
        'buttons': [
            {
                'class': 'btn btn-default',
                'text': 'Ja, verwijderen',
                'click': function () {
                    switch (this.trigger.attr('data-callback')) {
                        case 'removeImage':
                            this.trigger.closest('form').append('<input type="hidden" name="delete-image[]" value="' + this.trigger.attr('data-name') + '">');
                            this.trigger.parents('.media-wrapper').html('');

                            $('#dialog-confirm').dialog('close');

                            break;
                        default:
                            this.form.submit();
                            break;
                    }
                }.bind(this)
            },
            {
                'class': 'btn btn-cancel',
                'text': 'Nee, toch niet',
                'click': function () {
                    $(this).dialog('close');
                }
            }
        ]
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
// VIDEO PREVIEW
// -----------------------------------------------------------
var VideoPreview = function(node) {
	this.container = $(node);
	this.video = this.container.find('.embedded-video-input');
	this.videoPreview = this.container.find('.video-preview');
	this.loadImage = this.container.find('.video-preview-loader');

	this.video.on('change keyup', this.previewVideo.bind(this));

	if (this.video.val())
	{
		this.previewVideo();
	}
}

VideoPreview.prototype.previewVideo = function() {
	var vimeoPattern = /(videos|video|channels|\.com)\/([\d]+)/;
	var youtubePattern = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;

	var vimeo = (this.video.val().match(vimeoPattern));
	var youtube = (this.video.val().match(youtubePattern));

	if (vimeo && vimeo[2].length)
	{
		this.videoPreview.find('iframe').attr('src', '//player.vimeo.com/video/'+vimeo[2]+'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff');
	}
	else if (youtube && youtube[1].length)
	{
		this.videoPreview.find('iframe').attr('src', '//youtube.com/embed/' + youtube[1]+'?modestbranding=1&showinfo=0&rel=0&autohide=1&iv_load_policy=3&hd=1');
	}
	else
	{
		this.videoPreview.addClass('hide');
		return;
	}

	this.loadImage.removeClass('hide');
	this.videoPreview.find('iframe').on('load', function() {
		this.loadImage.addClass('hide');
		this.videoPreview.removeClass('hide');
	}.bind(this));
}

$.fn.videoPreview = function() {
	$(this).each(function(index, el) {
		el = $(el);
		var instance = el.data('videoPreview');
		if (!instance) {
			el.data('videoPreview', new VideoPreview(this));
		}
	})
}







// -----------------------------------------------------------
// SORTABLE TABLE
// -----------------------------------------------------------
$.fn.sortableTable = function() {
	var items = $(this);
	items.each(function(index, item) {
		item = $(item);
		if (!item.data('sortableTable')) {
			item.data('sortableTable', new SortableTable(item));
		}
	});
	return items;
}

var SortableTable = function(table) {

	this.table = table;
	this.element = this.table.closest('.content-body');
	this.tbody = this.table.find('tbody');

	this.options = {
		'itemsMax': 30,
		'sortable': this.table.hasClass('js-datatable-order')
	};

	this.element.find('.search-control').on('keyup blur', this.search.bind(this));

	this.hideButton = $('<button class="btn add-item-button" type="button" style="display: none;">Show less</button>');
	this.element.append(this.hideButton);
	this.hideButton.on('click', this.itemsHide.bind(this));

	this.showButton = $('<button class="btn add-item-button" type="button" style="display: none;">Show more</button>');
	this.element.append(this.showButton);
	this.showButton.on('click', this.itemsShow.bind(this));

    $.fn.dataTableExt.oStdClasses.sRowEmpty = "table-cell";
    $.fn.dataTableExt.oStdClasses.sSortDesc = 'table-header-sort-item-active-asc';
    $.fn.dataTableExt.oStdClasses.sSortAsc = 'table-header-sort-item-active-desc';

	this.table = this.table.dataTable({
		'sDom': 't',
        'aoColumnDefs': [{
            'bSortable': false,
            'aTargets': ['js-disable-sort']
        }],
		'bPaginate': false,
		'bSort' : !this.options.sortable,
		'aaSorting': [],
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
	this.items.find('td:not(.table-order, .table-control)').on('click', function(e) {
		window.location = $(e.currentTarget).closest('.table-row').attr('data-edit-href');
	});

	if (TOUCH) {
		this.items.on('touchstart', function(e) {
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
			'opacity': 0.7,
			'cancel': 'input,textarea,select,option,button:not(.js-sortable-handle)',
			'handle': '.js-sortable-handle',
			'containment':'parent',
			'placeholder': "ui-state-highlight",
			'cursor': 'move',
			'update': this.update.bind(this),
			'helper': function(e, ui) {
				if (this.message) this.message.remove();
				ui.css('width','100%');
				ui.children().each(function() {
					$(this).width($(this).width());
				});
				return ui;
			}.bind(this)
		});
	}

	this.itemsHide();
}

SortableTable.prototype.update = function() {

	$.ajax({
		'type': 'POST',
		'url': '/admin/ajax/sortorderchange',
		'dataType': 'json',
		'data': {
			'order': this.tbody.sortable('toArray', {'attribute': 'data-record-id'}),
			'model': model,
            'url': window.location.pathname,
            'relation': this.tbody.closest('.onetomany-relation-content').attr('data-relation')
        },
		'success': function(data) {
			this.message = $(data.message);
			this.table.before(this.message);
		}.bind(this),
		'error': function() {
			//alert('Error: Neem contact op met Just.');
		}.bind(this)
	});
}

SortableTable.prototype.search = function(e) {
	var value = $(e.currentTarget).val();
	this.table.fnFilter(value);
	this.sortHandles.toggle(!value.length);
}

SortableTable.prototype.itemsHide = function(e) {
	var items = this.tbody.children();
	if (items.length > this.options.itemsMax) {
		this.showButton.show();
		this.hideButton.hide();

		if (!this.wrap) {
			items.slice(this.options.itemsMax).hide();
		} else {

			var item = items.eq(this.options.itemsMax-1);
			this.wrap.animate({'height': (item.position().top + item.outerHeight() + parseFloat(this.table.css('margin-bottom')))+'px'}, function() {
				items.slice(this.options.itemsMax).hide();
				this.wrap.css('height', '');
			}.bind(this));
		}
	}
}

SortableTable.prototype.itemsShow = function(e) {
	var items = this.tbody.children();
	if (items.length > this.options.itemsMax) {
		if (!this.wrap) {
			this.wrap = this.element.find('.dataTables_wrapper');
			this.wrap.css('overflow', 'hidden');
			this.wrap.css('position', 'relative');
		}

		this.wrap.css('height', this.wrap.height()+'px');
		items.slice(this.options.itemsMax).show();
		this.showButton.hide();
		this.hideButton.show();

		var item = items.last();
		this.wrap.animate({'height': (item.position().top + item.outerHeight() + parseFloat(this.table.css('margin-bottom')))+'px'}, function() {
			this.wrap.css('height', '');
		}.bind(this));

	}
}
