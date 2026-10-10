import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/address');

export default class AddressList extends WebComponent {
    render() {
        //if (!customer.isLogged()) return;

        let data = new Map();

        data.set('addresses', customer.getAddresses());

        return loader.template('account/address_list', [ data, language, config ]);
    }

    handleDelete(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

        ajax.get('action.php?route=account/address.delete&language=' + local.get('language') + '&customer_token=' + customer.getToken() + '&address_id=' + e.target.value, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            success: (json) => {
                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                if (json.has('success')) {
                    this.alert.append('<ui-alert type="success">' + json.get('success') + '</ui-alert>');

                    this.update();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

customElements.define('address-list', AddressList);