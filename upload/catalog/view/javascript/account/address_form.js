import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/address');

// Library
const ajax = await loader.library('ajax');
const customer = await loader.library('customer');

// Name
export const name = 'address-form';

customElements.define('address-form', class extends WebComponent {
    render() {
        let data = {};

        data.addresses = customer.getAddresses();

        return loader.template('account/address', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

        let target = e.target;

        let form = new FormData(target);

        ajax.post('action.php?route=account/address.save', form, {
            beforeSend: () => {
                //ref.get('button-cart').button('loading');
            },
            onComplete: (json) => {
                //ref.get('button-cart').button('reset');
            },
            onSuccess: this.onSuccess.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    delete(e) {
        let dismissible = document.querySelectorAll('.alert-dismissible');

        dismissible.remove();
    }

    onSuccess(json){
        let alert = document.getElementById('alert');

        if (json['error']) {
            alert.append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }

        if (json['success']) {
            alert.append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }
    }
});

/*
const address = document.getElementById('address');

$('#address').on('click', '.btn-danger', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
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
                $('#alert').append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#address').load('action.php?route=account/address.list&language=' + language + '&customer_token={{ customer_token }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
*/