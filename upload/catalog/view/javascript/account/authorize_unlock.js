import { WebComponent } from '../index.js';
import { ajax, loader, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/authorize_unlock');

export default class AuthorizeUnlock extends WebComponent {
	static formAssociated = true;
	#token = '';

    async render() {
        //if (customer.isLogged()) return;

        return loader.template('account/authorize_unlock', [ language ]);
    }

    async handleConnect() {
        let json = await ajax.get('action.php?route=information/contact.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token');
		}
    }

    async handleSubmit(e) {
        e.preventDefault();

        //if (customer.isLogged()) return;

		await ajax.post({
			url: 'action.php?route=account/authorize.send&language=' + local.get('language') + '&authorize_token=' + this.#token,
			data: this.form.getData(),
            handleSend: () => {
                this.form.toggleAttribute('submitting', true);
            },
            handleComplete: () => {
                this.form.toggleAttribute('submitting', false);
            },
            handleSuccess: (json) => {
                if (json.has('redirect')) {
                    location = json.get('redirect');
                }

                if (json.has('error')) {
                    this.form.setError(json.get('error'));
                }

                if (json.has('success')) {
                    this.form.setSuccess(json.get('success'));
                }
            },
            handleError: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
	}
}

customElements.define('authorize-unlock', AuthorizeUnlock);