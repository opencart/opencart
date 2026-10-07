import { WebComponent } from '../index.js';
import { ajax, loader, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/address');

export default class AuthorizeReset extends WebComponent {
    async render() {
        //if (customer.isLogged()) return;

        return loader.template('account/authorize_reset', [ language ]);
    }

    async onSubmit(e) {
        e.preventDefault();

        //if (customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/authorize.send&language=' + local.get('language'), form, {
            beforeSend: () => {
                this.submitter.state.add('loading');
            },
            onComplete: (json) => {
                this.submitter.button('reset');
            },
            onSuccess: (json) => {
                if (json.has('redirect')) {
                    location = json['redirect'];
                }

                if (json.has('error')) {
                    this.alert.prepend('<ui-alert type="danger">' + json.get('error') + '</ui-alert>');
                }

                if (json.has('success')) {
                    this.alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
                }
            },
            onError: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

customElements.define('authorize-reset', AuthorizeReset);