$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=catalog/category&user_token=' + url.get('user_token') + '&' + url);

    $('#list').load('index.php?route=catalog/category.list&user_token={{ user_token }}&' + url);
});

$('#button-repair').on('click', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=catalog/category.repair&user_token={{ user_token }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#list').load('index.php?route=catalog/category.list&user_token={{ user_token }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-name').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/category.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                json.unshift({
                    name: '{{ text_none }}',
                    category_id: '',
                });

                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['category_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        if (item['value']) {
            $('#input-name').val(decodeHTMLEntities(item['label']));
        } else {
            $('#input-name').val('');
        }
    }
});

$('textarea[data-oc-toggle=\'ckeditor\']').ckeditor({
    language: '{{ ckeditor }}'
});

$('#input-parent').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/category.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                json.unshift({
                    'category_id': '0',
                    'name': '{{ text_none }}'
                });

                response($.map(json, function(item) {
                    return {
                        value: item['category_id'],
                        label: item['name']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-parent').val(decodeHTMLEntities(item['label']));
        $('#input-parent-id').val(item['value']);
    }
});

$('#input-filter').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/filter.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['filter_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-filter').val('');

        $('#category-filter-' + item['value']).remove();

        html = '<tr id="category-filter-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="category_filter[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger btn-sm"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#category-filter tbody').append(html);
    }
});

$('#category-filter').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});