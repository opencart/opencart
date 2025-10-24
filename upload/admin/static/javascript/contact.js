$('#input-to').on('change', function() {
    $('.to').hide();

    $('#to-' + this.value.replaceAll('_', '-')).show();
});

$('#input-to').trigger('change');

// Customers
$('#input-customer').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=customer/customer.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['customer_id']
                    }
                }));
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'select': function(item) {
        $('#input-customer').val('');

        $('#mail-customer-' + item['value']).remove();

        html = '<tr id="mail-customer-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="customer[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#mail-customer tbody').append(html);
    }
});

$('#mail-customer').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

// Affiliates
$('#input-affiliate').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=customer/customer.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request) + '&filter_affiliate=1',
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['customer_id']
                    }
                }));
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'select': function(item) {
        $('#input-affiliate').val('');

        $('#mail-affiliate-' + item['value']).remove();

        html = '<tr id="mail-affiliate-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="affiliate[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#mail-affiliate tbody').append(html);
    }
});

$('#input-affiliate').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

// Products
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
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'select': function(item) {
        $('#input-product').val('');

        $('#mail-product-' + item['value']).remove();

        html = '<tr id="mail-product-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="product[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#mail-product tbody').append(html);
    }
});

$('#mail-product').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

$('textarea[data-oc-toggle=\'ckeditor\']').ckeditor({
    language: '{{ ckeditor }}'
});

$('#button-send').on('click', function() {
    var element = this;

    $(element).button('loading');

    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }

    var next = 'index.php?route=marketing/contact.send&user_token={{ user_token }}';

    var send = function() {
        return $.ajax({
            url: next,
            type: 'post',
            data: $('#content form').serialize(),
            dataType: 'json',
            success: function(json) {
                console.log(json);

                $('.invalid-feedback').removeClass('d-block');

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    }

                    for (key in json['error']) {
                        $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }

                    $(element).button('reset');
                }

                if (json['text']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle-circle"></i> ' + json['text'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $(element).button('reset');
                }

                if (json['next']) {
                    next = json['next'];

                    chain.attach(send);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                $(element).button('reset');
            }
        });
    };

    chain.attach(send);
});