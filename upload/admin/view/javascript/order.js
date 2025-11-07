$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=sale/order&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=sale/order.list&user_token={{ user_token }}&' + url);
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

$('#content').on('change', 'input[name^=\'selected\']', function() {
    $('#button-shipping, #button-invoice').prop('disabled', true);

    var selected = $('input[name^=\'selected\']:checked');

    if (selected.length) {
        $('#button-invoice').prop('disabled', false);
    }

    for (i = 0; i < selected.length; i++) {
        if ($(selected[i]).parent().find('input[name^=\'shipping_method\']').val()) {
            $('#button-shipping').prop('disabled', false);
            break;
        }
    }
});

$('#button-shipping, #button-invoice').prop('disabled', true);

$('#button-collapse').on('click', function() {
    var element = this;

    var target = $('#collapse-order');

    if (!target.is(':hidden')) {
        target.slideUp('400', function() {
            $(element).html('{{ text_more }} <i class="fa-solid fa-angle-down"></i>');
        });
    } else {
        target.slideDown('400', function() {
            $(element).html('{{ text_less }} <i class="fa-solid fa-angle-up"></i>');
        });
    }
});

$(document).on('click', '#button-invoice', function() {
    $.ajax({
        url: 'index.php?route=sale/order.createInvoiceNo&user_token={{ user_token }}&order_id=' + $('#input-order-id').val(),
        dataType: 'json',
        beforeSend: function() {
            $('#button-invoice').button('loading');
        },
        complete: function() {
            $('#button-invoice').button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#output-invoice').html(json['invoice_no']);

                $('#button-invoice').replaceWith('<button disabled="disabled" class="btn btn-outline-primary"><i class="fa-solid fa-cog"></i></button>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Customer
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
                    email: '',
                    telephone: '',
                    custom_field: [],
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
                        email: item['email'],
                        telephone: item['telephone'],
                        custom_field: item['custom_field'],
                        address: item['address']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        // Reset all custom fields
        $('#form-customer input[type=\'text\'], #form-customer textarea').not('#input-customer, #input-customer-id').val('');
        $('#form-customer select option').removeAttr('selected');
        $('#form-customer input[type=\'checkbox\'], #form-customer input[type=\'radio\']').removeAttr('checked');

        $('#input-customer-id').val(item['value']);
        $('#input-customer-group').val(item['customer_group_id']);
        $('#input-firstname').val(decodeHTMLEntities(item['firstname']));
        $('#input-lastname').val(decodeHTMLEntities(item['lastname']));
        $('#input-email').val(decodeHTMLEntities(item['email']));
        $('#input-telephone').val(item['telephone']);

        for (i in item.custom_field) {
            $('#input-custom-field-' + i).val(item.custom_field[i]);

            if (item.custom_field[i] instanceof Array) {
                for (j = 0; j < item.custom_field[i].length; j++) {
                    $('#input-custom-field-value-' + item.custom_field[i][j]).prop('checked', true);
                }
            }
        }

        $('#input-customer-group').trigger('change');

        html = '<option value="0">{{ text_none|escape('js') }}</option>';

        for (i in item['address']) {
            html += '<option value="' + item['address'][i]['address_id'] + '">' + decodeHTMLEntities(item['address'][i]['firstname']) + ' ' + decodeHTMLEntities(item['address'][i]['lastname']) + ', ' + (item['address'][i]['company'] ? decodeHTMLEntities(item['address'][i]['company']) + ', ' : '') + decodeHTMLEntities(item['address'][i]['address_1']) + ', ' + decodeHTMLEntities(item['address'][i]['city']) + ', ' + decodeHTMLEntities(item['address'][i]['country']) + '</option>';
        }

        $('#input-payment-address').html(html);
        $('#input-shipping-address').html(html);
    }
});

// Custom Fields
$('#input-customer-group').on('change', function() {
    $.ajax({
        url: 'index.php?route=customer/customer.customfield&user_token={{ user_token }}&customer_group_id=' + this.value,
        dataType: 'json',
        success: function(json) {
            $('.custom-field').hide();
            $('.custom-field').removeClass('required');

            for (i = 0; i < json.length; i++) {
                custom_field = json[i];

                $('.custom-field-' + custom_field['custom_field_id']).show();

                if (custom_field['required']) {
                    $('.custom-field-' + custom_field['custom_field_id']).addClass('required');
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#form-customer').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=customer&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer').serialize(),
        dataType: 'json',
        beforeSend: function() {
            $('#button-customer').button('loading');
        },
        complete: function() {
            $('#button-customer').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').removeClass('d-block');

            if (typeof json['error'] == 'string') {
                $('#form-customer').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
                if (json['error']['warning']) {
                    $('#form-customer').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#form-customer').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#output-customer').html('<a href="index.php?route=customer/customer.form&user_token={{ user_token }}&customer_id=' + $('#input-customer-id').val() + '" target="_blank">' + $('#input-firstname').val() + ' ' + $('#input-lastname').val() + '</a>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-customer-group').trigger('change');

$('#input-store, #input-language, #input-currency').on('change', function(e) {
    $('#button-refresh').trigger('click');
});

// Product
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
                        subscription: item['subscription'],
                        price: item['price']
                    }
                }));
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
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
                            html += ' (' + option_value['price_prefix'] + '<x-currency code="{{ currency_code }}" amount="' + option_value['price'] + '" value="{{ currency_value }}"></x-currency>)';
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
                            html += ' (' + option_value['price_prefix'] + '<x-currency code="{{ currency_code }}" amount="' + option_value['price'] + '" value="{{ currency_value }}"></x-currency>)';
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
                            html += ' (' + option_value['price_prefix'] + '<x-currency code="{{ currency_code }}" amount="' + option_value['price'] + '" value="{{ currency_value }}"></x-currency>)';
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
                            html += ' (' + option_value['price_prefix'] + '<x-currency code="{{ currency_code }}" amount="' + option_value['price'] + '" value="{{ currency_value }}"></x-currency>)';
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

        if (item['subscription'] != '') {
            html = '<fieldset class="mb-0">';
            html += '  <legend>{{ entry_subscription|escape('js') }}</legend>';
            html += '  <div class="mb-3">';
            html += '    <select name="subscription_plan_id" id="input-subscription-plan" class="form-select" required>';
            html += '      <option value="">{{ text_select|escape('js') }}</option>';

            for (i = 0; i < item['subscription'].length; i++) {
                if (item['subscription'][i]['customer_group_id'] == $('#input-customer-group').val()) {
                    html += '<option value="' + item['subscription'][i]['subscription_plan_id'] + '">' + item['subscription'][i]['name'] + '</option>';
                }
            }

            html += '  </select>';

            for (i = 0; i < item['subscription'].length; i++) {
                html += '<div id="subscription-description-' + item['subscription'][i]['subscription_plan_id'] + '-' + item['subscription'][i]['customer_group_id'] + '" class="form-text subscription-description d-none">' + item['subscription'][i]['description'] + '</div>';
            }

            html += '<div id="error-subscription" class="invalid-feedback"></div>';

            elements = $('#input-customer-group option');

            for (i = 0; i < elements.length; i++) {
                html += '<datalist id="subscription-plan-' + $(elements[i]).val() + '">';
                html += '  <option value="">{{ text_select|escape('js') }}</option>';

                for (j = 0; j < item['subscription'].length; j++) {
                    if (item['subscription'][j]['customer_group_id'] == $(elements[i]).val()) {
                        html += '<option value="' + item['subscription'][i]['subscription_plan_id'] + '">' + item['subscription'][i]['name'] + '</option>';
                    }
                }

                html += '</datalist>';
            }

            html += '  </div>';
            html += '</fieldset>';

            $('#subscription').html(html);
        } else {
            $('#subscription').html('');
        }
    }
});

$('#form-product-add').on('change', '#input-subscription-plan', function(e) {
    var element = this;

    $('.subscription-description').addClass('d-none');

    $('#subscription-description-' + $(element).val() + '-' + $('#input-customer-group').val()).removeClass('d-none');
});

$('#input-customer-group').on('change', function(e) {
    $('#input-subscription-plan').html($('#subscription-plan-' + this.value).html());

    // Change subscription description
    $('#input-subscription-plan').trigger('click');
});

$('#form-product-add').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=product_add&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-product-add, #form-customer, #form-cart, #form-shipping-address, #form-shipping-method, #form-payment-address, #form-payment-method, #collapse-order form').serialize(),
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

                cart_render(json['products'], json['totals'], json['shipping_required']);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Remove product
$('#order-product').on('click', 'button', function(e) {
    $(this).parent().parent().remove();

    // Refresh products and totals
    $('#button-refresh').trigger('click');
});

// Payment Address
$('#input-payment-address').on('change', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=customer/address.address&user_token={{ user_token }}&address_id=' + this.value,
        dataType: 'json',
        beforeSend: function() {
            $(element).prop('disabled', true);
        },
        complete: function() {
            $(element).prop('disabled', false);
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#form-payment-address').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            // Reset all fields
            $('#form-payment-address input[type=\'text\'], #form-payment-address textarea').val('');
            $('#form-payment-address select option').not('#input-payment-address').removeAttr('selected');
            $('#form-payment-address input[type=\'checkbox\'], #form-payment-address input[type=\'radio\']').removeAttr('checked');

            $('#input-payment-firstname').val(json['firstname']);
            $('#input-payment-lastname').val(json['lastname']);
            $('#input-payment-company').val(json['company']);
            $('#input-payment-address-1').val(json['address_1']);
            $('#input-payment-address-2').val(json['address_2']);
            $('#input-payment-city').val(json['city']);
            $('#input-payment-postcode').val(json['postcode']);
            $('#x-payment-country').attr('value', json['country_id']);
            $('#x-payment-zone').attr('value', json['zone_id']);

            for (i in json['custom_field']) {
                $('#input-payment-custom-field-' + i).val(json['custom_field'][i]);

                if (json['custom_field'][i] instanceof Array) {
                    for (j = 0; j < json['custom_field'][i].length; j++) {
                        $('#input-payment-custom-field-value-' + json['custom_field'][i][j]).prop('checked', true);
                    }
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});



$('#form-payment-address').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=payment_address&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-payment-address').serialize(),
        dataType: 'json',
        beforeSend: function() {
            $('#button-payment-address').button('loading');
        },
        complete: function() {
            $('#button-payment-address').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').removeClass('d-block');

            if (typeof json['error'] == 'string') {
                $('#form-payment-address').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
                if (json['error']['warning']) {
                    $('#form-payment-address').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#form-payment-address').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                var address = $('#input-payment-firstname').val() + ' ' + $('#input-payment-lastname').val() + '<br/>';

                var company = $('#input-payment-company').val();

                if (company) {
                    address += $('#input-payment-company').val() + '<br/>';
                }

                address += $('#input-payment-address-1').val() + '<br/>';

                var address_2 = $('#input-payment-address-2').val();

                if (address_2) {
                    address += $('#input-payment-address-2').val() + '<br/>';
                }

                address += $('#input-payment-city').val() + '<br/>';

                var postcode = $('#input-payment-postcode').val();

                if (postcode) {
                    address += postcode + '<br/>';
                }

                address += $('#input-payment-zone option:selected').text() + '<br/>';
                address += $('#input-payment-country option:selected').text();

                $('#output-payment-address').html(address);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Shipping Address
$('#input-shipping-address').on('change', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=customer/address.address&user_token={{ user_token }}&address_id=' + this.value,
        dataType: 'json',
        beforeSend: function() {
            $(element).prop('disabled', true);
        },
        complete: function() {
            $(element).prop('disabled', false);
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#form-shipping-address').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            // Reset all fields
            $('#form-shipping-address input[type=\'text\'], #form-shipping-address input[type=\'text\'], #form-shipping-address textarea').val('');
            $('#form-shipping-address select option').not('#input-shipping-address').removeAttr('selected');
            $('#form-shipping-address input[type=\'checkbox\'], #form-shipping-address input[type=\'radio\']').removeAttr('checked');

            $('#input-shipping-firstname').val(json['firstname']);
            $('#input-shipping-lastname').val(json['lastname']);
            $('#input-shipping-company').val(json['company']);
            $('#input-shipping-address-1').val(json['address_1']);
            $('#input-shipping-address-2').val(json['address_2']);
            $('#input-shipping-city').val(json['city']);
            $('#input-shipping-postcode').val(json['postcode']);
            $('#x-shipping-country').attr('value', json['country_id']);
            $('#x-shipping-zone').attr('value', json['zone_id']);

            for (i in json['custom_field']) {
                $('#input-shipping-custom-field-' + i).val(json['custom_field'][i]);

                if (json['custom_field'][i] instanceof Array) {
                    for (j = 0; j < json['custom_field'][i].length; j++) {
                        $('#input-shipping-custom-field-value-' + json['custom_field'][i][j]).prop('checked', true);
                    }
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#form-shipping-address').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=shipping_address&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-cart, #form-shipping-address').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-shipping-address').button('loading');
        },
        complete: function() {
            $('#button-shipping-address').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('#form-shipping-address .alert-dismissible').remove();
            $('#form-shipping-address .is-invalid').removeClass('is-invalid');
            $('#form-shipping-address .invalid-feedback').removeClass('d-block');

            if (typeof json['error'] == 'string') {
                $('#form-shipping-address').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
                if (json['error']['warning']) {
                    $('#form-shipping-address').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#form-shipping-address').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                var address = $('#input-shipping-firstname').val() + ' ' + $('#input-shipping-lastname').val() + '<br/>';

                var company = $('#input-shipping-company').val();

                if (company) {
                    address += $('#input-shipping-company').val() + '<br/>';
                }

                address += $('#input-shipping-address-1').val() + '<br/>';

                var address_2 = $('#input-shipping-address-2').val();

                if (address_2) {
                    address += $('#input-shipping-address-2').val() + '<br/>';
                }

                address += $('#input-shipping-city').val() + '<br/>';

                var postcode = $('#input-shipping-postcode').val();

                if (postcode) {
                    address += postcode + '<br/>';
                }

                address += $('#input-shipping-zone option:selected').text() + '<br/>';
                address += $('#input-shipping-country option:selected').text();

                $('#output-shipping-address').html(address);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Shipping Method
var shipping_method = [];

$('#button-shipping-methods').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=shipping_methods&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-cart, #form-shipping-address, #form-shipping-method, #form-payment-address, #form-payment-method, #collapse-order form').serialize(),
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

$('#modal-shipping-method').on('change', 'input[name=\'choose_shipping\']', function() {
    var code = $(this).val();

    if (shipping_method[code]) {
        $('#input-shipping-method-name').val(shipping_method[code]['name']);
        $('#input-shipping-method-code').val(shipping_method[code]['code']);
        $('#input-shipping-method-cost').val(shipping_method[code]['cost']);
        $('#input-shipping-method-tax-class').val(shipping_method[code]['tax_class_id']);
    }
});

$('#form-shipping-method').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=shipping_method&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-cart, #form-shipping-address, #form-shipping-method, #form-payment-address, #form-payment-method, #collapse-order form').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-shipping-method').button('loading');
        },
        complete: function() {
            $('#button-shipping-method').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').removeClass('d-block');

            if (typeof json['error'] == 'string') {
                $('#modal-shipping-method .modal-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
                if (json['error']['warning']) {
                    $('#modal-shipping-method .modal-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#modal-shipping-method .modal-body').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#output-shipping-method').html($('#input-shipping-method-name').val());

                cart_render(json['products'], json['totals'], json['shipping_required']);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Payment Method
var payment_method = [];

$('#button-payment-methods').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=payment_methods&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-cart, #form-shipping-address, #form-shipping-method, #form-payment-address, #form-payment-method, #collapse-order form').serialize(),
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
                    html += '<li class="mb-3"><strong>' + json['payment_methods'][i]['name'] + '</strong>';
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

$('#modal-payment-method').on('change', 'input[name=\'choose_payment\']', function() {
    var code = $(this).val();

    if (payment_method[code]) {
        $('#input-payment-method-name').val(payment_method[code]['name']);
        $('#input-payment-method-code').val(payment_method[code]['code']);
    }
});

$('#form-payment-method').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=payment_method&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-cart, #form-shipping-address, #form-shipping-method, #form-payment-address, #form-payment-method, #collapse-order form').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-payment-method').button('loading');
        },
        complete: function() {
            $('#button-payment-method').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (typeof json['error'] == 'string') {
                $('#modal-payment-method .modal-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
                if (json['error']['warning']) {
                    $('#modal-payment-method .modal-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#modal-payment-method .modal-body').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#output-payment-method').html($('#input-payment-method-name').val());

                cart_render(json['products'], json['totals'], json['shipping_required']);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#form-comment').on('submit', function(e) {
    e.preventDefault();

    $('#output-comment').html($('#input-comment').val().replace(/<[^>]*>?/gm, '').replace(/(?:\r\n|\r|\n)/g, '<br/>'));
});

$('#button-refresh').on('click', function() {
    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=cart&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-cart, #form-shipping-address, #form-shipping-method, #form-payment-address, #form-payment-method, #collapse-order form').serialize(),
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

            if (json['success']) {
                cart_render(json['products'], json['totals'], json['shipping_required']);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#button-confirm').on('click', function() {
    var element = this;

    console.log($('#form-customer, #form-cart, #form-shipping-address, #form-shipping-method, #form-payment-address, #form-payment-method, #collapse-order form, #form-comment, #input-order-id').serialize());

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=confirm&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-customer, #form-cart, #form-shipping-address, #form-shipping-method, #form-payment-address, #form-payment-method, #collapse-order form, #form-comment, #input-order-id').serialize(),
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
            if (typeof json['error'] == 'string') {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
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

                if (json['order_id']) {
                    $('#input-order-id').val(json['order_id']);
                }

                if (json['points']) {
                    $('#output-point').html(json['points']);
                    $('#button-reward-add').prop('disabled', false);
                    $('#button-reward-remove').prop('disabled', false);
                } else {
                    $('#output-point').html(0);
                    $('#button-reward-add').prop('disabled', true);
                    $('#button-reward-remove').prop('disabled', true);
                }

                if (json['commission']) {
                    $('#output-commission').html('<x-currency code="' + $('#input-currency').val() + '" amount="' + json['commission'] + '" value="{{ currency_value }}"></x-currency>');
                    $('#button-commission-add').prop('disabled', false);
                    $('#button-commission-remove').prop('disabled', false);
                } else {
                    $('#output-commission').html('&nbsp;');
                    $('#button-commission-add').prop('disabled', true);
                    $('#button-commission-remove').prop('disabled', true);
                }

                cart_render(json['products'], json['totals'], json['shipping_required']);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Refresh all products and totals
var order_product_row = {{ order_product_row }};

function cart_render(products, totals, shipping_required) {
    html = '';

    if (products) {
        for (i in products) {
            product = products[i];

            html += '<tr>';
            html += '  <td><a href="index.php?route=catalog/product.form&user_token={{ user_token }}&product_id=' + product['product_id'] + '" target="_blank">' + product['name'] + '</a>' + (!product['stock'] ? ' <span class="text-danger">***</span>' : '');
            html += '    <div id="error-product-' + order_product_row + '-product" class="invalid-feedback mt-0"></div>';

            html += '<ul class="list-unstyled mb-0">';

            html += '<li>';
            html += '  <small> - {{ text_model|escape('js') }}: ' + product['model'] + '</small>';
            html += '</li>';

            for (j = 0; j < product['option'].length; j++) {
                option = product['option'][j];

                html += '<li>';
                html += '  <small> - ' + option['name'] + ': ' + option['value'] + '</small>';
                html += '  <div id="error-product-' + order_product_row + '-option-' + option['product_option_id'] + '" class="invalid-feedback mt-0"></div>';
                html += '</li>';
            }

            if (product['subscription']) {
                html += '<li>';
                html += '  <small> - {{ text_subscription|escape('js') }}: ' + product['subscription'] + '</small>';
                html += '  <div id="error-product-' + order_product_row + '-subscription" class="invalid-feedback mt-0"></div>';
                html += '</li>';
            }

            if (product['reward']) {
                html += '<li>';
                html += '  <small> - {{ text_points|escape('js') }}: ' + product['reward'] + '</small>';
                html += '</li>';
            }

            html += '</ul>';

            html += '<input type="hidden" name="product[' + order_product_row + '][product_id]" value="' + product['product_id'] + '"/>';
            html += '<input type="hidden" name="product[' + order_product_row + '][quantity]" value="' + product['quantity'] + '"/>';

            for (j = 0; j < product['option'].length; j++) {
                option = product['option'][j];

                if (option['type'] == 'select' || option['type'] == 'radio') {
                    html += '<input type="hidden" name="product[' + order_product_row + '][option][' + option['product_option_id'] + ']" value="' + option['product_option_value_id'] + '"/>';
                }

                if (option['type'] == 'checkbox') {
                    html += '<input type="hidden" name="product[' + order_product_row + '][option][' + option['product_option_id'] + '][]" value="' + option['product_option_value_id'] + '"/>';
                }

                if (option['type'] == 'text' || option['type'] == 'textarea' || option['type'] == 'file' || option['type'] == 'date' || option['type'] == 'datetime' || option['type'] == 'time') {
                    html += '<input type="hidden" name="product[' + order_product_row + '][option][' + option['product_option_id'] + ']" value="' + option['value'] + '"/>';
                }
            }

            html += '    <input type="hidden" name="product[' + order_product_row + '][subscription_plan_id]" value="' + product['subscription_plan_id'] + '"/>';
            html += '  </td>';
            html += '  <td class="text-end">' + product['quantity'] + '</td>';
            html += '  <td class="text-end"><x-currency code="' + $('#input-currency').val() + '" amount="' + product['price'] + '" value="{{ currency_value }}"></x-currency></td>';
            html += '  <td class="text-end"><x-currency code="' + $('#input-currency').val() + '" amount="' + product['total'] + '" value="{{ currency_value }}"></x-currency></td>';
            html += '  <td class="text-end"><button type="button" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
            html += '</tr>';

            order_product_row++;
        }
    } else {
        html += '<tr>';
        html += '  <td colspan="6" class="text-center">{{ text_no_results|escape('js') }}</td>';
        html += '</tr>';
    }

    $('#order-product').html(html);

    // Totals
    html = '';

    for (i in totals) {
        total = totals[i];

        html += '<tr>';
        html += '  <td class="text-end"><strong>' + total['title'] + '</strong></td>';
        html += '  <td class="text-end" style="width: 1px;"><x-currency code="' + $('#input-currency').val() + '" amount="' + total['value'] + '" value="{{ currency_value }}"></x-currency></td>';
        html += '</tr>';
    }

    $('#order-total').html(html);

    if (shipping_required) {
        $('#shipping-address').removeClass('d-none');
        $('#shipping-method').removeClass('d-none');
        $('#button-shipping-method').prop('disabled', false);
    } else {
        $('#shipping-address').addClass('d-none');
        $('#shipping-method').addClass('d-none');
        $('#button-shipping-method').prop('disabled', true);
    }
}

// Reward
$(document).on('click', '#button-reward-add, #button-reward-remove', function(e) {
    var element = this;

    if ($(element).hasClass('btn-success')) {
        var action = 'add';
    } else {
        var action = 'remove';
    }

    $.ajax({
        url: 'index.php?route=sale/order.' + action + 'Reward&user_token={{ user_token }}&order_id=' + $('#input-order-id').val(),
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                if (action == 'add') {
                    $(element).replaceWith('<button type="button" id="button-reward-remove" data-bs-toggle="tooltip" title="{{ button_reward_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button>');
                }

                if (action == 'remove') {
                    $(element).replaceWith('<button type="button" id="button-reward-add" data-bs-toggle="tooltip" title="{{ button_reward_add|escape('js') }}" class="btn btn-success"><i class="fa-solid fa-plus-circle"></i></button>');
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Affiliate
$('#input-affiliate').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=marketing/affiliate.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                json.unshift({
                    customer_id: 0,
                    name: '{{ text_none }}'
                });

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
        $('#input-affiliate-id').val(item['value']);
        $('#input-affiliate').val(decodeHTMLEntities(item['label']));
    }
});

$('#form-affiliate').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=affiliate&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val(),
        type: 'post',
        data: $('#form-affiliate').serialize(),
        dataType: 'json',
        beforeSend: function() {
            $('#button-affiliate').button('loading');
        },
        complete: function() {
            $('#button-affiliate').button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#form-affiliate').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#form-affiliate').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                if ($('#input-affiliate-id').val()) {
                    $('#output-affiliate').html('<a href="index.php?route=marketing/affiliate.form&user_token={{ user_token }}&customer_id=' + $('#input-affiliate-id').val() + '" target="_blank">' + $('#input-affiliate').val() + '</a>');
                } else {
                    $('#output-affiliate').html('');
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Commission
$(document).on('click', '#button-commission-add, #button-commission-remove', function(e) {
    var element = this;

    if ($(element).hasClass('btn-success')) {
        var action = 'add';
    } else {
        var action = 'remove';
    }

    $.ajax({
        url: 'index.php?route=sale/order.' + action + 'Commission&user_token={{ user_token }}&order_id=' + $('#input-order-id').val(),
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                if (action == 'add') {
                    $(element).replaceWith('<button type="button" id="button-commission-remove" data-bs-toggle="tooltip" title="{{ button_commission_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button>');
                }

                if (action == 'remove') {
                    $(element).replaceWith('<button type="button" id="button-commission-add" data-bs-toggle="tooltip" title="{{ button_commission_add|escape('js') }}" class="btn btn-success"><i class="fa-solid fa-plus-circle"></i></button>');
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
        url: 'index.php?route=sale/order.call&user_token={{ user_token }}&call=history_add&store_id=' + $('#input-store').val() + '&language=' + $('#input-language').val() + '&currency=' + $('#input-currency').val() + '&order_id=' + $('#input-order-id').val(),
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

                $('#history').load('index.php?route=sale/order.history&user_token={{ user_token }}&order_id=' + $('#input-order-id').val());

                $('#input-history').val('');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});