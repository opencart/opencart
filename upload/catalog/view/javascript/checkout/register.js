import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const ajax = await loader.library('ajax');
const cart = await loader.library('cart');
const customer = await loader.library('customer');

customElements.define('checkout-register', class extends WebComponent {
    token = '';

    async connected() {
        this.token = ajax.get('action.php?route=checkout/register.token');
    }

    async render() {
        let data = {};


        data.token = this.token;

        return loader.template('checkout/register', { ...data,  ...language });
    }

    onSubmit(e) {
        e.preventDefault();

        console.log('onSubmit');

        let target = e.target;

        let form = new FormData(target);

        ajax.post('action.php?route=checkout/register.save&language={{ language }}', form, {
            beforeSend: (request) => {
                //this.bind('button-cart').setAttribute('loading', '');
            },
            onComplete: (json) => {
                //console.log(this.bind('button-cart'));

                //this.bind('button-cart').loading = false;
            },
            onSuccess: (json) => {
                console.log('onSuccess', json);

                // Remove past error classes from inputs
                target.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                target.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

                // Display error messages
                if (json['error'] !== undefined) {
                    for (let key in json['error']) {
                        let value = key.replaceAll('_', '-');

                        let input = target.querySelector('#input-' + value);

                        if (input) {
                            input.classList.add('is-invalid');

                            // If the element has inputs inside.
                            input.querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                        }

                        let error = target.querySelector('#error-' + value);

                        if (error) {
                            error.classList.add('d-block');
                        }
                    }
                }

                // Display success message
                if (json['success'] !== undefined) {
                    let alert = target.querySelector('#alert');

                    if (alert) {
                        alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    }
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });

    }

    onChange() {

    }
});


// Register
$('#form-register').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'action.php?route=checkout/register.save&language={{ language }}',
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

                $('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

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
        url: 'action.php?route=account/custom_field&language={{ language }}&customer_group_id=' + $(element).val(),
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

