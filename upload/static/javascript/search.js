$('#button-search').on('click', function() {
    url = 'index.php?route=product/search&language={{ language }}';

    var search = $('#input-search').val();

    if (search) {
        url += '&search=' + encodeURIComponent(search);
    }

    var category_id = $('#input-category').prop('value');

    if (category_id > 0) {
        url += '&category_id=' + encodeURIComponent(category_id);
    }

    var sub_category = $('#input-sub-category:checked').prop('value');

    if (sub_category) {
        url += '&sub_category=1';
    }

    var description = $('#input-description:checked').prop('value');

    if (description) {
        url += '&description=1';
    }

    location = url;
});

$('#input-search').on('keydown', function(e) {
    if (e.keyCode == 13) {
        $('#button-search').trigger('click');
    }
});

$('#input-category').on('change', function() {
    $('#input-sub-category').prop('disabled', (this.value == '0' ? true : false));
});

$('#input-category').trigger('change');