$('input[type="file"]').change(function(e) {
    var img = $(this).closest('.controls').find('img');
    if (this.files && this.files[0]) {
        img.closest('.row').removeClass('hide');
        var reader = new FileReader();
        reader.onload = function(e) {
            if (!img.attr('data-original')) {
                img.attr('data-original', img.attr('src'));
            }
            img.attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    } else {
        if (!$(this).val()) {
            img.closest('.row').addClass('hide');
        }
        img.attr('src', img.attr('data-original'));
    }
});
