import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/address');

class AddressList extends WebComponent {
    render() {
        let data = new Map();

        data.set('addresses', customer.getAddresses());

        return loader.template('account/address', [ data, language, config ]);
    }

    onDelete(e) {
        e.preventDefault();

        ajax.get('action.php?route=account/address.delete&language=' + local.get('language') + '&customer_token=' + customer.getToken() + '&address_id=' + e.target.value, {
            beforeSend: () => {
                e.target.button.button('loading');
            },
            complete: () => {
                e.target.button('reset');
            },
            success: this.success.bind(this),
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    success(json) {
        if (json['error']) {
            this.alert.append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }

        if (json['success']) {
            this.alert.append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

            this.update();
        }
    }
}

customElements.define('address-list', AddressList);