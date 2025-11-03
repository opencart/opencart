$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=sale/returns&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=sale/returns.list&user_token={{ user_token }}&' + url);
});

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
            }
        });
    },
    'select': function(item) {
        $('#input-customer').val(decodeHTMLEntities(item['label']));
    }
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

$('#input-model').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product.autocomplete&user_token={{ user_token }}&filter_model=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['model'],
                        value: item['product_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-model').val(decodeHTMLEntities(item['label']));
    }
});

var products = [];

$('#form-order').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=sale/order.autocomplete&user_token={{ user_token }}&order_id=' + $('#input-order-id').val(),
        dataType: 'json',
        beforeSend: function() {
            $('#button-order').button('loading');
        },
        complete: function() {
            $('#button-order').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').removeClass('d-block');

            // Check for errors
            if (json['error']) {
                $('#form-order').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['order_id']) {
                $('#output-order').html('<a href="index.php?route=sale/order.form&user_token={{ user_token }}&order_id=' + json['order_id'] + '" target="_blank">' + json['order_id'] + '</a>');

                $('#input-date-ordered').val(json['date_added']);
                $('#input-customer').val(json['firstname'] + ' ' + json['lastname']);
                $('#input-customer-id').val(json['customer_id']);
                $('#input-firstname').val(json['firstname']);
                $('#input-lastname').val(json['lastname']);
                $('#input-email').val(json['email']);
                $('#input-telephone').val(json['telephone']);

                $('#output-customer').html('<a href="index.php?route=customer/customer.form&user_token={{ user_token }}&customer_id=' + json['customer_id'] + '" target="_blank">' + json['firstname'] + ' ' + json['lastname'] + '</a>');

                html = '<option value="0">{{ text_select }}</option>';

                for (i = 0; i < json['products'].length; i++) {
                    html += '<option value="' + i + '"' + (json['products'][i]['product_id'] == $('#input-product-id').val() ? ' selected' : '') + '>' + json['products'][i]['name'] + '</option>';
                }

                $('#input-return').html(html);

                products = json['products'];
            }

            $('#modal-order').modal('hide');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-customer').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=customer/customer.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        category: item['customer_group'],
                        label: item['name'],
                        value: item['customer_id'],
                        firstname: item['firstname'],
                        lastname: item['lastname'],
                        email: item['email'],
                        telephone: item['telephone']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-customer').val(decodeHTMLEntities(item['label']));
        $('#input-customer-id').val(item['value']);
        $('#input-firstname').val(decodeHTMLEntities(item['firstname']));
        $('#input-lastname').val(decodeHTMLEntities(item['lastname']));
        $('#input-email').val(decodeHTMLEntities(item['email']));
        $('#input-telephone').val(decodeHTMLEntities(item['telephone']));
    }
});

$('#form-customer').on('submit', function(e) {
    e.preventDefault();

    var customer_id = $('#input-customer-id').val();

    if (customer_id) {
        $('#output-customer').html('<a href="index.php?route=customer/customer.form&user_token={{ user_token }}&customer_id=' + customer_id + '" target="_blank">' + $('#input-firstname').val() + ' ' + $('#input-lastname').val() + '</a>');
    } else {
        $('#output-customer').html(decodeHTMLEntities($('#input-firstname').val() + ' ' + $('#input-lastname').val()));
    }

    $('#modal-customer').modal('hide');
});

$('#input-return').on('change', function(e) {
    if (products[this.value]) {
        $('#input-product').val(products[this.value]['name']);
        $('#input-model').val(products[this.value]['model']);
    }
});

$('#form-product').on('submit', function(e) {
    e.preventDefault();

    html  = '<tr>';

    if ($('#input-return').val()) {
        html += '<td><a href="index.php?route=catalog/product.form&user_token={{ user_token }}&product_id=' + $('#input-return').val() + '" target="_blank">' + $('#input-product').val() + '</a></td>';
        html += '<td>' + $('#input-model').val() + '</td>';
        html += '<td class="text-end">' + $('#input-quantity').val() + '</td>';
    } else {
        html += '<td colspan="3" class="text-center">{{ text_no_results }}</td>';
    }

    html += '</tr>';

    $('#return-product').html(html);

    $('#modal-product').modal('hide');
});

$('#button-save').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=sale/returns.save&user_token={{ user_token }}',
        type: 'post',
        data: $('#form-order, #form-customer, #form-product, #form-return-reason, #form-opened, #form-return-action, #form-comment, #input-return-status, #input-return-id').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').removeClass('d-block');

            // Check for errors
            if (json['error']) {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }

                // Highlight any invalid fields
                $('.is-invalid').parents('form').each(function(index, element) {
                    $($(element).attr('data-oc-target')).addClass('is-invalid');
                });
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                if (json['return_id']) {
                    $('#input-return-id').val(json['return_id']);
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#history').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#history').load(this.href);
});

$('#form-history').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=sale/returns.addHistory&user_token={{ user_token }}&return_id=' + $('#input-return-id').val(),
        type: 'post',
        dataType: 'json',
        data: $('#form-history').serialize(),
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-history').button('loading');
        },
        complete: function() {
            $('#button-history').button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#history').load('index.php?route=sale/returns.history&user_token={{ user_token }}&return_id=' + $('#input-return-id').val());

                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#input-history').val('');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});