$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=sale/subscription&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=sale/subscription.list&user_token={{ user_token }}&' + url);
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

$('#input-customer').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=customer/customer.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                json.unshift({
                    customer_id: 0,
                    customer_group_id: {{ customer_group_id }},
                name: '{{ text_none }}',
                    customer_group: '',
                    firstname: '',
                    lastname: '',
                    address: []
            });

                response($.map(json, function(item) {
                    return {
                        category: item['customer_group'],
                        label: item['name'],
                        value: item['customer_id'],
                        customer_group_id: item['customer_group_id'],
                        firstname: item['firstname'],
                        lastname: item['lastname'],
                        address: item['address']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        if (item) {
            $('#input-customer').val(decodeHTMLEntities(item['label']));
            $('#input-customer-id').val(item['value']);
            $('#input-customer-group').val(item['customer_group_id']);

            html = '<option value="0">{{ text_none|escape('js') }}</option>';

            for (i in item['address']) {
                var address = item['address'][i];

                html += '<option value="' + address['address_id'] + '">' + decodeHTMLEntities(address['firstname'] + ' ' + address['lastname'] + ', ' + (address['company'] ? address['company'] + ', ' : '') + address['address_1'] + ', ' + address['city'] + ', ' + address['country']) + '</option>';
            }

            $('#input-payment-address').html(html);
            $('#input-shipping-address').html(html);
        } else {
            $('#input-customer').val('');
            $('#input-customer-id').val(0);
            $('#input-customer-group').val({{ customer_group_id }});

            html = '<option value="0">{{ text_none|escape('js') }}</option>';

            $('#input-payment-address').html(html);
            $('#input-shipping-address').html(html);
        }
    }
});

$('#input-store, #input-language, #input-currency').on('change', function(e) {
    $('#button-refresh').trigger('click');
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
                        value: item['product_id'],
                        option: item['option'],
                        subscription: item['subscription']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-product-id').val(item['value']);
        $('#input-product').val(decodeHTMLEntities(item['label']));

        if (item['option'] != '') {
            html = '<fieldset class="mb-0">';
            html += '  <legend>{{ entry_option|escape('js') }}</legend>';

            for (i = 0; i < item['option'].length; i++) {
                option = item['option'][i];

                if (option['type'] == 'select') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label for="input-option-' + option['product_option_id'] + '" class="form-label">' + option['name'] + '</label>';
                    html += '  <select name="option[' + option['product_option_id'] + ']" id="input-option-' + option['product_option_id'] + '" class="form-select">';
                    html += '    <option value="">{{ text_select|escape('js') }}</option>';

                    for (j = 0; j < option['product_option_value'].length; j++) {
                        option_value = option['product_option_value'][j];

                        html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

                        if (option_value['price']) {
                            html += ' (' + option_value['price_prefix'] + '<x-currency code="{{ currency_code }}" amount="' + option_value['price'] + '"></x-currency>)';
                        }

                        html += '</option>';
                    }

                    html += '  </select>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }

                if (option['type'] == 'radio') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label for="input-option-' + option['product_option_id'] + '" class="form-label">' + option['name'] + '</label>';
                    html += '  <select name="option[' + option['product_option_id'] + ']" id="input-option-' + option['product_option_id'] + '" class="form-select">';
                    html += '    <option value="">{{ text_select|escape('js') }}</option>';

                    for (j = 0; j < option['product_option_value'].length; j++) {
                        option_value = option['product_option_value'][j];

                        html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

                        if (option_value['price']) {
                            html += ' (' + option_value['price_prefix'] + '<x-currency code="{{ currency_code }}" amount="' + option_value['price'] + '"></x-currency>)';
                        }

                        html += '</option>';
                    }

                    html += '  </select>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }

                if (option['type'] == 'checkbox') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label class="form-label">' + option['name'] + '</label>';
                    html += '  <div id="input-option-' + option['product_option_id'] + '" class="form-control">';

                    for (j = 0; j < option['product_option_value'].length; j++) {
                        option_value = option['product_option_value'][j];

                        html += '<div class="form-check">';
                        html += '  <input type="checkbox" name="option[' + option['product_option_id'] + '][]" value="' + option_value['product_option_value_id'] + '" id="input-option-value-' + option_value['product_option_value_id'] + '" class="form-check-input"/>';
                        html += '  <label for="input-option-value-' + option_value['product_option_value_id'] + '" class="form-check-label">' + option_value['name'];

                        if (option_value['price']) {
                            html += ' (' + option_value['price_prefix'] + '<x-currency code="{{ currency_code }}" amount="' + option_value['price'] + '"></x-currency>)';
                        }

                        html += '  </label>';
                        html += '</div>';
                    }

                    html += '  </div>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }

                if (option['type'] == 'image') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label for="input-option-' + option['product_option_id'] + '" class="form-label">' + option['name'] + '</label>';

                    html += '  <select name="option[' + option['product_option_id'] + ']" id="input-option-' + option['product_option_id'] + '" class="form-select">';
                    html += '    <option value="">{{ text_select|escape('js') }}</option>';

                    for (j = 0; j < option['product_option_value'].length; j++) {
                        option_value = option['product_option_value'][j];

                        html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

                        if (option_value['price']) {
                            html += ' (' + option_value['price_prefix'] + '<x-currency code="{{ currency_code }}" amount="' + option_value['price'] + '"></x-currency>)';
                        }

                        html += '</option>';
                    }

                    html += '  </select>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }

                if (option['type'] == 'text') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label for="input-option-' + option['product_option_id'] + '" class="form-label">' + option['name'] + '</label>';
                    html += '  <input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" id="input-option-' + option['product_option_id'] + '" class="form-control"/>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }

                if (option['type'] == 'textarea') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label for="input-option-' + option['product_option_id'] + '" class="form-label">' + option['name'] + '</label>';
                    html += '  <textarea name="option[' + option['product_option_id'] + ']" rows="5" placeholder="' + option['name'] + '" id="input-option-' + option['product_option_id'] + '" class="form-control">' + option['value'] + '</textarea>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }

                if (option['type'] == 'file') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label class="form-label">' + option['name'] + '</label>';
                    html += '  <div>';
                    html += '    <button type="button" data-oc-toggle="upload" data-oc-url="{{ upload }}" data-oc-target="#input-option-' + option['product_option_id'] + '" data-oc-size-max="{{ config_file_max_size }}" data-oc-size-error="{{ error_upload_size|escape('js') }}" class="btn btn-light"><i class="fa-solid fa-upload"></i> {{ button_upload|escape('js') }}</button>';
                    html += '    <a href="" class="btn btn-light"><i class="fa-solid fa-download"></i></a>';
                    html += '    <input type="hidden" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option-' + option['product_option_id'] + '"/>';
                    html += '  </div>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }

                if (option['type'] == 'date') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label for="input-option-' + option['product_option_id'] + '" class="form-label">' + option['name'] + '</label>';
                    html += '  <input type="date" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" id="input-option-' + option['product_option_id'] + '" class="form-control"/>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }

                if (option['type'] == 'time') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label for="input-option-' + option['product_option_id'] + '" class="form-label">' + option['name'] + '</label>';
                    html += '  <input type="time" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" id="input-option-' + option['product_option_id'] + '" class="form-control"/>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }

                if (option['type'] == 'datetime') {
                    html += '<div class="mb-3' + (option['required'] == 1 ? ' required' : '') + '">';
                    html += '  <label for="input-option-' + option['product_option_id'] + '" class="form-label">' + option['name'] + '</label>';
                    html += '  <input type="datetime-local" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" id="input-option-' + option['product_option_id'] + '" class="form-control"/>';
                    html += '  <div id="error-option-' + option['product_option_id'] + '" class="invalid-feedback"></div>';
                    html += '</div>';
                }
            }

            html += '</fieldset>';

            $('#option').html(html);
        } else {
            $('#option').html('');
        }
    }
});

var subscription_product_row = {{ subscription_product_row }};

$('#form-product-add').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=sale/subscription.call&user_token={{ user_token }}&call=product_add&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-subscription-plan, #form-cart, #form-product-add, #form-shipping-address, #form-payment-address').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-product-add').button('loading');
        },
        complete: function() {
            $('#button-product-add').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').removeClass('d-block');

            if (typeof json['error'] == 'string') {
                $('#form-product-add').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
                if (json['error']['warning']) {
                    $('#form-product-add').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#form-product-add').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                html = '<tr>';

                if (json['products']) {
                    for (i in json['products']) {
                        product = json['products'][i];

                        html += '<tr>';
                        html += '  <td><a href="index.php?route=catalog/product.form&user_token={{ user_token }}&product_id=' + product['product_id'] + '" target="_blank">' + product['name'] + '</a>';
                        html += '    <div id="error-product-' + subscription_product_row + '-product" class="invalid-feedback mt-0"></div>';

                        html += '<ul class="list-unstyled mb-0">';

                        html += '<li>';
                        html += '  <small> - {{ text_model|escape('js') }}: ' + product['model'] + '</small>';
                        html += '</li>';

                        for (j = 0; j < product['option'].length; j++) {
                            option = product['option'][j];

                            html += '<li>';
                            html += '  <small> - ' + option['name'] + ': ' + option['value'] + '</small>';
                            html += '  <div id="error-product-' + subscription_product_row + '-option-' + option['product_option_id'] + '" class="invalid-feedback mt-0"></div>';
                            html += '</li>';
                        }

                        html += '</ul>';

                        html += '<input type="hidden" name="product[' + subscription_product_row + '][product_id]" value="' + product['product_id'] + '"/>';
                        html += '<input type="hidden" name="product[' + subscription_product_row + '][quantity]" value="' + product['quantity'] + '"/>';

                        for (j = 0; j < product['option'].length; j++) {
                            option = product['option'][j];

                            if (option['type'] == 'select' || option['type'] == 'radio') {
                                html += '<input type="hidden" name="product[' + subscription_product_row + '][option][' + option['product_option_id'] + ']" value="' + option['product_option_value_id'] + '"/>';
                            }

                            if (option['type'] == 'checkbox') {
                                html += '<input type="hidden" name="product[' + subscription_product_row + '][option][' + option['product_option_id'] + '][]" value="' + option['product_option_value_id'] + '"/>';
                            }

                            if (option['type'] == 'text' || option['type'] == 'textarea' || option['type'] == 'file' || option['type'] == 'date' || option['type'] == 'datetime' || option['type'] == 'time') {
                                html += '<input type="hidden" name="product[' + subscription_product_row + '][option][' + option['product_option_id'] + ']" value="' + option['value'] + '"/>';
                            }
                        }

                        html += '    <input type="hidden" name="product[' + subscription_product_row + '][subscription_plan_id]" value="' + product['subscription_plan_id'] + '"/>';
                        html += '  </td>';
                        html += '  <td class="text-end">' + product['quantity'] + '</td>';
                        html += '  <td class="text-end"><x-currency code="{{ currency_code }}" amount="' + product['price'] + '"></x-currency></td>';
                        html += '  <td class="text-end"><x-currency code="{{ currency_code }}" amount="' + product['total'] + '"></x-currency></td>';
                        html += '  <td class="text-end"><button type="button" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
                        html += '</tr>';

                        $('#subscription-product').html(html);

                        subscription_product_row++;
                    }
                } else {
                    html += '<tr>';
                    html += '  <td colspan="5" class="text-center">{{ text_no_results|escape('js') }}</td>';
                    html += '</tr>';
                }

                if (json['shipping_required']) {
                    $('#shipping-address').removeClass('d-none');
                    $('#shipping-method').removeClass('d-none');
                    $('#button-shipping-method').prop('disabled', false);
                } else {
                    $('#shipping-address').addClass('d-none');
                    $('#shipping-method').addClass('d-none');
                    $('#button-shipping-method').prop('disabled', true);
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Remove product
$('#subscription-product').on('click', 'button', function(e) {
    $(this).parent().parent().remove();
});

// Shipping Method
var shipping_method = [];

$('#button-shipping-methods').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=sale/subscription.call&user_token={{ user_token }}&call=shipping_methods&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-cart, #form-shipping-address, #form-payment-address').serialize(),
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

            if (typeof json['error'] == 'string') {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            html = '';

            if (json['shipping_methods']) {
                var k = 0;

                html += '<ul class="list-unstyled">';

                for (i in json['shipping_methods']) {
                    html += '<li class="mb-3"><strong>' + json['shipping_methods'][i]['name'] + '</strong>';
                    html += '<ul class="list-unstyled mt-2">';

                    if (!json['shipping_methods'][i]['error']) {
                        for (j in json['shipping_methods'][i]['quote']) {
                            shipping_method[json['shipping_methods'][i]['quote'][j]['code']] = json['shipping_methods'][i]['quote'][j];

                            html += '<li><input type="radio" name="choose_shipping" value="' + json['shipping_methods'][i]['quote'][j]['code'] + '" id="input-shipping-method-' + k + '"';

                            if (json['shipping_methods'][i]['quote'][j]['code'] == $('#input-shipping-method-code').val()) {
                                html += ' checked';
                            }

                            html += '/>';

                            html += '  <label for="input-shipping-method-' + k + '">' + json['shipping_methods'][i]['quote'][j]['name'] + ' - <x-currency code="' + $('#input-currency').val() + '" amount="' + json['shipping_methods'][i]['quote'][j]['cost'] + '"></x-currency></label>';
                            html += '</li>';

                            k++;
                        }
                    } else {
                        html += '<li><div class="alert alert-danger">' + json['shipping_methods'][i]['error'] + '</div></li>';
                    }

                    html += '</ul>';
                    html += '</li>';
                }

                html += '</ul>';

                $('#shipping-methods').html(html);

                $('#modal-shipping-method').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#form-shipping-method').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    var code = $('input[name=\'choose_shipping\']').val();

    if (shipping_method[code]) {
        $('#input-shipping-method-name').val(shipping_method[code]['name']);
        $('#input-shipping-method-code').val(shipping_method[code]['code']);
        $('#input-shipping-method-cost').val(shipping_method[code]['cost']);
        $('#input-shipping-method-tax-class').val(shipping_method[code]['tax_class_id']);
    }

    $('#output-shipping-method').html($('#input-shipping-method-name').val());

    $('#modal-shipping-method').modal('hide');
});

// Payment Method
var payment_method = [];

$('#button-payment-methods').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=sale/subscription.call&user_token={{ user_token }}&call=payment_methods&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-cart, #form-shipping-address, #form-payment-address, #form-shipping-method').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (typeof json['error'] == 'string') {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            var html = '';

            if (json['payment_methods']) {
                var k = 0;

                html += '<ul class="list-unstyled">';

                for (i in json['payment_methods']) {
                    html += '<li><p><strong>' + json['payment_methods'][i]['name'] + '</strong></p>';
                    html += '<ul class="list-unstyled mt-2">';

                    if (!json['payment_methods'][i]['error']) {
                        for (j in json['payment_methods'][i]['option']) {
                            payment_method[json['payment_methods'][i]['option'][j]['code']] = json['payment_methods'][i]['option'][j];

                            html += '<li><input type="radio" name="choose_payment" value="' + json['payment_methods'][i]['option'][j]['code'] + '" id="input-payment-method-' + k + '"';

                            if (json['payment_methods'][i]['option'][j]['code'] == $('#input-payment-method-code').val()) {
                                html += ' checked';
                            }

                            html += '/>';
                            html += '  <label for="input-payment-method-' + k + '">' + json['payment_methods'][i]['option'][j]['name'] + '</label>';
                            html += '</li>';

                            k++;
                        }
                    } else {
                        html += '<li><div class="alert alert-danger">' + json['payment_methods'][i]['error'] + '</div></li>';
                    }

                    html += '</ul>';
                    html += '</li>';
                }

                html += '</ul>';

                $('#payment-methods').html(html);

                $('#modal-payment-method').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#form-payment-method').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    var code = $('input[name=\'choose_payment\']').val();

    if (payment_method[code]) {
        $('#input-payment-method-name').val(payment_method[code]['name']);
        $('#input-payment-method-code').val(payment_method[code]['code']);
    }

    $('#output-payment-method').html($('#input-payment-method-name').val());

    $('#modal-payment-method').modal('hide');
});

$('#button-refresh').on('click', function() {
    $.ajax({
        url: 'index.php?route=sale/subscription.call&user_token={{ user_token }}&call=cart&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-cart, #form-shipping-address, #form-payment-address, #form-shipping-method, #form-payment-method').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-refresh').button('loading');
        },
        complete: function() {
            $('#button-refresh').button('reset');
        },
        success: function(json) {
            console.log(json);

            if (json['error']) {
                if (typeof json['error'] == 'string') {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#button-confirm').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=sale/subscription.call&user_token={{ user_token }}&call=confirm&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-subscription-plan, #form-cart, #form-shipping-address, #form-payment-address, #form-shipping-method, #form-payment-method, #form-comment, #input-subscription-id').serialize(),
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

            if (json['error']) {
                if (json['error']['shipping_method']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['shipping_method'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#input-shipping-method-info').addClass('is-invalid');

                    delete json['error']['shipping_method'];
                }

                if (json['error']['payment_method']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['payment_method'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#input-payment-method-info').addClass('is-invalid');

                    delete json['error']['payment_method'];
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                if (json['subscription_id']) {
                    $('#input-subscription-id').val(json['subscription_id']);
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
        url: 'index.php?route=sale/subscription.addHistory&user_token={{ user_token }}&subscription_id=' + $('#input-subscription-id').val(),
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
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#history').load('index.php?route=sale/subscription.history&user_token={{ user_token }}&subscription_id=' + $('#input-subscription-id').val());

                $('#input-history').val('');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#order').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#order').load(this.href);
});

$('#log').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#log').load(this.href);
});
