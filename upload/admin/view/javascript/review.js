('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=catalog/review&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=catalog/review.list&user_token={{ user_token }}&' + url);
});

$('#input-product').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['product_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-product').val(decodeHTMLEntities(item['label']));
    }
});

$('#button-rating').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=catalog/review.rating&user_token={{ user_token }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});