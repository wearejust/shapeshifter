// Document ready
$(function() {
    JUSTTable = new JUSTTable();
//    VideoPreview = new VideoPreview();
//    FileAttribute = new FileAttribute();

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

    $('input.datepicker').datepicker({
        dateFormat: "dd-mm-yy"
    });

    $('input.datetimepicker').datetimepicker();
    $('.embedded-video').videoPreview();
    $('.onetomany-relation-content').oneToManyLoader();

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

// Accordion
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
            console.log('yeey ' + data);
        },
        error: function (data) {
            console.log(JSON.parse(data.responseText).error);
        }
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


var VideoPreview = function(node) {
    this.container = $(node);
    this.video = this.container.find('.embedded-video-input');
    this.videoPreview = this.container.find('.video-preview');
    this.loadImage = this.container.find('.video-preview-loader');

    this.video.on('change', this.previewVideo.bind(this));

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
        this.videoPreview.attr('src', '//player.vimeo.com/video/'+vimeo[2]+'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff');
    }
    else if (youtube && youtube[1].length)
    {
        this.videoPreview.attr('src', '//youtube.com/embed/' + youtube[1]);
    }
    else
    {
        this.videoPreview.hide();
        return;
    }

    this.loadImage.show();
    this.videoPreview.on('load', function() {
        this.loadImage.hide();
        this.videoPreview.show();
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

var JUSTTable = function() {

    this.table = $('#datatable');
    this.tbody = this.table.find('tbody');
    this.searchInput = $('input#search');
    this.addBtn = $('#add-item');
    this.cancelBtn = $('#cancel-order');
    this.changeBtn = $('#change-order');
    this.table = this.table.dataTable({
        bPaginate: false,
        bInfo: false,
    });

    $('#datatable_filter').remove();

    this.changeBtn.on('click', this.toggleDrag.bind(this));
    this.cancelBtn.on('click', this.reset.bind(this));

    this.searchInput.on('keyup', function(e) {
        var value = $(e.currentTarget).val();
        this.table.fnFilter(value);
        this.changeBtn.attr('disabled', value || false);
        this.addBtn.attr('disabled', value || false);
    }.bind(this));

    this.tbody.sortable({
        'axis': 'y',
        'opacity': 0.7,
        'placeholder': "ui-state-highlight",
        'cursor': 'move',
        'helper': function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        },
    }).disableSelection().sortable('disable');
}

JUSTTable.prototype.reset = function() {
    this.changeBtn.toggleClass('active');
    this.changeBtn.text('Volgorde wijzigen');

    this.unsortable();
    this.cancelBtn.hide();
    this.addBtn.show();
    this.table.fnSortNeutral();
}

JUSTTable.prototype.toggleDrag = function(e) {
    var current = $(e.currentTarget);
    current.toggleClass('active');
    this.tbody.find('tr').toggleClass('table-actions');

    if (current.hasClass('active')) {
        this.cancelBtn.show();
        this.addBtn.hide();
        current.text('Opslaan');
        this.sortable();
    } else {
        this.cancelBtn.hide();
        this.addBtn.show();
        current.text('Volgorde wijzigen');
        this.unsortable();
        this.updateOrder(
            this.tbody.sortable('toArray', {
                'attribute': 'data-record-id'
            })
        );
    }
}

JUSTTable.prototype.unsortable = function() {
    this.searchInput.prop('disabled', false);
    this.table.find('th').each(function(index, element) {
        this.table.fnSortListener(element, index);
    }.bind(this));

    this.tbody.sortable('disable');
}

JUSTTable.prototype.sortable = function() {
    $('.alert').remove();
    this.searchInput.prop('disabled', true);
    this.table.find('th').unbind('click');
    this.tbody.sortable('enable');
}

JUSTTable.prototype.updateOrder = function(order) {
    $.ajax({
        'type': 'POST',
        'url': '/admin/ajax/sortorderchange',
        'dataType': 'json',
        'data': {
            order: order,
            model: model
        },
        'success': function(data) {
            JUSTTable.table.before(data.message);
        },
        'error': function() {
            alert('Error: Neem contact op met JUST');
        }
    });
}
