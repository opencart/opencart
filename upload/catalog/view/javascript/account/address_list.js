import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/address');

// Library
const ajax = await loader.library('ajax');
const customer = await loader.library('customer');

// Name
export const name = 'address-list';

customElements.define('address-list', class extends WebComponent {
    render() {
        let data = {};

        data.addresses = customer.getAddresses();

        return loader.template('account/address', { ...data, ...language, ...config });
    }

    onDelete(e) {
        e.preventDefault();

        ajax.post({
            url: '',
            beforeSend: function() {
                $(element).button('loading');
            },
            complete: function() {
                $(element).button('reset');
            },
            success: function(json) {
                let dismissible = document.querySelectorAll('.alert-dismissible');

                dismissible.remove();

                if (json['error']) {
                    $('#alert').append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (json['success']) {
                    $('#alert').append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    //$('#address').load('action.php?route=account/address.list&language=' + language + '&customer_token={{ customer_token }}');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    }
});