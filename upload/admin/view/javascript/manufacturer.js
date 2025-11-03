$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=catalog/manufacturer&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=catalog/manufacturer.list&user_token={{ user_token }}&' + url);
});

$('#input-name').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/manufacturer.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['manufacturer_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-name').val(item['label']);
    }
});

$('textarea[data-oc-toggle=\'ckeditor\']').ckeditor({
    language: '{{ ckeditor }}'
});