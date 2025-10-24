// Account
$('input[name=\'account\']').on('click', function() {
    if ($(this).val() == 1) {
        $('#password').removeClass('d-none');
    } else {
        // If guest hide password field
        $('#password').addClass('d-none');
    }

    if ($(this).val() == 1) {
        $('#register-agree').removeClass('d-none');
    } else {
        // If guest hide register agree field
        $('#register-agree').addClass('d-none');
    }
});

$('input[name=\'account\']:checked').trigger('click');

// Customer Group
$('#input-customer-group').on('change', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=account/custom_field&language={{ language }}&customer_group_id=' + $(element).val(),
        dataType: 'json',
        beforeSend: function() {
            $(element).prop('disabled', true);
        },
        complete: function() {
            $(element).prop('disabled', false);
        },
        success: function(json) {
            $('.custom-field').addClass('d-none');
            $('.custom-field').removeClass('required');

            for (i = 0; i < json.length; i++) {
                custom_field = json[i];

                $('.custom-field-' + custom_field['custom_field_id']).removeClass('d-none');

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

$('#input-customer-group').trigger('change');

$(document).on('change', '#input-address-match', function() {
    if ($(this).prop('checked')) {
        $('#shipping-address').hide();
    } else {
        $('#shipping-address').show();
    }
});

// Register
$('#form-register').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=checkout/register.save&language={{ language }}',
        type: 'post',
        dataType: 'json',
        data: $('#form-register').serialize(),
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-register').button('loading');
        },
        complete: function() {
            $('#button-register').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('#form-register').find('.is-invalid').removeClass('is-invalid');
            $('#form-register').find('.invalid-feedback').removeClass('d-block');

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                if ($('#input-register').prop('checked')) {
                    $('input[name=\'account\']').prop('disabled', true);
                    $('#input-customer-group').prop('disabled', true);
                    $('#input-password').prop('disabled', true);
                    $('#input-captcha').prop('disabled', true);
                    $('#input-register-agree').prop('disabled', true);
                }

                $('#input-shipping-method').val('');
                $('#input-payment-method').val('');

                $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('input[name=\'payment_existing\']').on('change', function() {
    if ($(this).val() == 1) {
        $('#payment-existing').show();
        $('#payment-new').hide();
    } else {
        $('#payment-existing').hide();
        $('#payment-new').show();
    }
});

// Existing Payment Address
$('#input-payment-address').on('change', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=checkout/payment_address.address&language={{ language }}&address_id=' + $(element).val(),
        dataType: 'json',
        beforeSend: function() {
            $(element).prop('disabled', true);
        },
        complete: function() {
            $(element).prop('disabled', false);
        },
        success: function(json) {
            console.log(json);

            $('#input-payment-address').removeClass('is-invalid');
            $('#error-payment-address').removeClass('d-block');

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                $('#input-payment-address').addClass('is-invalid');
                $('#error-payment-address').html(json['error']).addClass('d-block');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#input-shipping-method').val('');
                $('#input-payment-method').val('');

                $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// New Payment Address
$('#form-payment-address').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=checkout/payment_address.save&language={{ language }}',
        type: 'post',
        data: $('#form-payment-address').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-payment-address').button('loading');
        },
        complete: function() {
            $('#button-payment-address').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('#form-payment-address').find('.is-invalid').removeClass('is-invalid');
            $('#form-payment-address').find('.invalid-feedback').removeClass('d-block');

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (i in json['error']) {
                    for (key in json['error']) {
                        $('#input-payment-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-payment-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#form-payment-address')[0].reset();

                var html = '<option value="">{{ text_select|escape('js') }}</option>';

                if (json['addresses']) {
                    for (i in json['addresses']) {
                        html += '<option value="' + json['addresses'][i]['address_id'] + '">' + json['addresses'][i]['firstname'] + ' ' + json['addresses'][i]['lastname'] + ', ' + (json['addresses'][i]['company'] ? json['addresses'][i]['company'] + ', ' : '') + json['addresses'][i]['address_1'] + ', ' + json['addresses'][i]['city'] + ', ' + json['addresses'][i]['zone'] + ', ' + json['addresses'][i]['country'] + '</option>';
                    }
                }

                // Payment Address
                $('#input-payment-address').html(html);

                $('#input-payment-address').val(json['address_id']);

                $('#payment-addresses').css({display: 'block'});

                $('#input-payment-existing').trigger('click');

                // Shipping Address
                var shipping_address_id = $('#input-shipping-address').val();

                $('#input-shipping-address').html(html);

                if (shipping_address_id) {
                    $('#input-shipping-address').val(shipping_address_id);
                }

                $('#shipping-address').css({display: 'block'});
                $('#shipping-addresses').css({display: 'block'});

                $('#input-shipping-existing').trigger('click');

                $('#input-shipping-method').val('');
                $('#input-payment-method').val('');

                $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('input[name=\'shipping_existing\']').on('change', function() {
    if ($(this).val() == 1) {
        $('#shipping-existing').show();
        $('#shipping-new').hide();
    } else {
        $('#shipping-existing').hide();
        $('#shipping-new').show();
    }
});

// Existing Shipping Address
$('#input-shipping-address').on('change', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=checkout/shipping_address.address&language={{ language }}&address_id=' + $(element).val(),
        dataType: 'json',
        beforeSend: function() {
            $(element).prop('disabled', true);
        },
        complete: function() {
            $(element).prop('disabled', false);
        },
        success: function(json) {
            console.log(json);

            $('#input-shipping-address').removeClass('is-invalid');
            $('#error-shipping-address').removeClass('d-block');

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                $('#input-shipping-address').addClass('is-invalid');
                $('#error-shipping-address').html(json['error']).addClass('d-block');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#input-shipping-method').val('');
                $('#input-payment-method').val('');

                $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// New Shipping Address
$('#form-shipping-address').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=checkout/shipping_address.save&language={{ language }}',
        type: 'post',
        data: $('#form-shipping-address').serialize(),
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

            $('#form-shipping-address').find('.is-invalid').removeClass('is-invalid');
            $('#form-shipping-address').find('.invalid-feedback').removeClass('d-block');

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (i in json['error']) {
                    for (key in json['error']) {
                        $('#input-shipping-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-shipping-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#form-shipping-address')[0].reset();

                var html = '<option value="">{{ text_select|escape('js') }}</option>';

                if (json['addresses']) {
                    for (i in json['addresses']) {
                        html += '<option value="' + json['addresses'][i]['address_id'] + '">' + json['addresses'][i]['firstname'] + ' ' + json['addresses'][i]['lastname'] + ', ' + (json['addresses'][i]['company'] ? json['addresses'][i]['company'] + ', ' : '') + json['addresses'][i]['address_1'] + ', ' + json['addresses'][i]['city'] + ', ' + json['addresses'][i]['zone'] + ', ' + json['addresses'][i]['country'] + '</option>';
                    }
                }

                // Shipping Address
                $('#input-shipping-address').html(html);

                $('#input-shipping-address').val(json['address_id']);

                $('#shipping-addresses').css({display: 'block'});

                $('#input-shipping-existing').trigger('click');

                // Payment Address
                var payment_address_id = $('#input-payment-address').val();

                $('#input-payment-address').html(html);

                if (payment_address_id) {
                    $('#input-payment-address').val(payment_address_id);
                }

                $('#payment-addresses').css({display: 'block'});

                $('#input-payment-existing').trigger('click');

                $('#input-shipping-method').val('');
                $('#input-payment-method').val('');

                $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Shipping Method
$('#button-shipping-methods').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=checkout/shipping_method.quote&language={{ language }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('#input-shipping-method').removeClass('is-invalid');
            $('#error-shipping-method').removeClass('d-block');

            if (json['error']) {
                $('#input-shipping-method').addClass('is-invalid');
                $('#error-shipping-method').html(json['error']).addClass('d-block');
            }

            if (json['shipping_methods']) {
                $('#modal-shipping').remove();

                html = '<div id="modal-shipping" class="modal">';
                html += '  <div class="modal-dialog modal-dialog-centered">';
                html += '    <div class="modal-content">';
                html += '      <div class="modal-header">';
                html += '        <h5 class="modal-title"><i class="fa fa-truck"></i> {{ text_shipping_method|escape('js') }}</h5>';
                html += '        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
                html += '      </div>';
                html += '      <div class="modal-body">';
                html += '        <form id="form-shipping-method">';
                html += '          <p>{{ text_shipping|escape('js') }}</p>';

                var first = true;

                for (i in json['shipping_methods']) {
                    html += '<p><strong>' + json['shipping_methods'][i]['name'] + '</strong></p>';

                    if (!json['shipping_methods'][i]['error']) {
                        for (j in json['shipping_methods'][i]['quote']) {
                            html += '<div class="form-check">';

                            var code = i + '-' + j.replaceAll('_', '-');

                            html += '<input type="radio" name="shipping_method" value="' + json['shipping_methods'][i]['quote'][j]['code'] + '" id="input-shipping-method-' + code + '"';

                            var method = $('#input-shipping-code').val();

                            if ((json['shipping_methods'][i]['quote'][j]['code'] == method) || (!method && first)) {
                                html += ' checked';

                                first = false;
                            }

                            html += '/>';
                            html += '  <label for="input-shipping-method-' + code + '">' + json['shipping_methods'][i]['quote'][j]['name'] + ' - <x-currency code="{{ currency }}" amount="' + json['shipping_methods'][i]['quote'][j]['cost'] + '"></x-currency></label>';
                            html += '</div>';
                        }
                    } else {
                        html += '<div class="alert alert-danger">' + json['shipping_methods'][i]['error'] + '</div>';
                    }
                }

                html += '          <div class="text-end">';
                html += '            <button type="submit" id="button-shipping-method" class="btn btn-primary">{{ button_continue|escape('js') }}</button>';
                html += '          </div>';
                html += '        </form>';
                html += '      </div>';
                html += '    </div>';
                html += '  </div>';
                html += '</div>';

                $('body').append(html);

                $('#modal-shipping').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).on('submit', '#form-shipping-method', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=checkout/shipping_method.save&language={{ language }}',
        type: 'post',
        data: $('#form-shipping-method').serialize(),
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

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#modal-shipping').modal('hide');

                $('#input-shipping-method').val($('input[name=\'shipping_method\']:checked').parent().find('label').text());
                $('#input-shipping-code').val($('input[name=\'shipping_method\']:checked').val());

                $('#input-payment-method').val('');

                $('#cart').load('index.php?route=common/cart.info&language={{ language }}');
                $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Payment Method
$('#button-payment-methods').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=checkout/payment_method.getMethods&language={{ language }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('#input-payment-method').removeClass('is-invalid');
            $('#error-payment-method').removeClass('d-block');

            if (json['error']) {
                $('#input-payment-method').addClass('is-invalid');
                $('#error-payment-method').html(json['error']).addClass('d-block');
            }

            if (json['payment_methods']) {
                $('#modal-payment').remove();

                html = '<div id="modal-payment" class="modal">';
                html += '  <div class="modal-dialog modal-dialog-centered">';
                html += '    <div class="modal-content">';
                html += '      <div class="modal-header">';
                html += '        <h5 class="modal-title"><i class="fa fa-credit-card"></i> {{ text_payment_method|escape('js') }}</h5>';
                html += '        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
                html += '      </div>';
                html += '      <div class="modal-body">';
                html += '        <form id="form-payment-method">';
                html += '          <p>{{ text_payment|escape('js') }}</p>';

                var first = true;

                for (i in json['payment_methods']) {
                    html += '<p><strong>' + json['payment_methods'][i]['name'] + '</strong></p>';

                    if (!json['payment_methods'][i]['error']) {
                        for (j in json['payment_methods'][i]['option']) {
                            html += '<div class="form-check">';

                            var code = i + '-' + j.replaceAll('_', '-');

                            html += '<input type="radio" name="payment_method" value="' + json['payment_methods'][i]['option'][j]['code'] + '" id="input-payment-method-' + code + '"';

                            var method = $('#input-payment-code').val();

                            if ((json['payment_methods'][i]['option'][j]['code'] == method) || (!method && first)) {
                                html += ' checked';

                                first = false;
                            }

                            html += '/>';
                            html += '  <label for="input-payment-method-' + code + '">' + json['payment_methods'][i]['option'][j]['name'] + '</label>';
                            html += '</div>';
                        }
                    } else {
                        html += '<div class="alert alert-danger">' + json['payment_methods'][i]['error'] + '</div>';
                    }
                }

                html += '          <div class="text-end">';
                html += '            <button type="submit" id="button-payment-method" class="btn btn-primary">{{ button_continue|escape('js') }}</button>';
                html += '          </div>';
                html += '        </form>';
                html += '      </div>';
                html += '    </div>';
                html += '  </div>';
                html += '</div>';

                $('body').append(html);

                $('#modal-payment').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).on('submit', '#form-payment-method', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=checkout/payment_method.save&language={{ language }}',
        type: 'post',
        data: $('#form-payment-method').serialize(),
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

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#modal-payment').modal('hide');

                $('#input-payment-method').val($('input[name=\'payment_method\']:checked').parent().find('label').text());
                $('#input-payment-code').val($('input[name=\'payment_method\']:checked').val());

                $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Comment
var timer = '';

$('#input-comment').on('keydown', function() {
    $('#button-confirm').prop('disabled', true);

    // Request
    clearTimeout(timer);

    timer = setTimeout(function(object) {
        $.ajax({
            url: 'index.php?route=checkout/payment_method.comment&language={{ language }}',
            type: 'post',
            data: $('#input-comment').serialize(),
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded',
            success: function(json) {
                console.log(json);

                $('.alert-dismissible').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (json['error']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#button-confirm').prop('disabled', false);
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#button-confirm').prop('disabled', false);
                }

                window.setTimeout(function() {
                    $('.alert-dismissible').fadeTo(1000, 0, function() {
                        $(this).remove();
                    });
                }, 3000);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $('#button-confirm').prop('disabled', false);

                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }, 1000, this);
});

/* Agree to terms */
$(document).on('change', '#input-checkout-agree', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=checkout/payment_method.agree&language={{ language }}',
        type: 'post',
        data: $('#input-checkout-agree').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-confirm').button('loading');
        },
        complete: function() {
            $('#button-confirm').button('reset');
        },
        success: function(json) {
            $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});