import { WebComponent } from '../index.js';
import { ajax, loader, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/authorize_unlock');

export default class AuthorizeUnlock extends WebComponent {
    async render() {
        //if (customer.isLogged()) return;

        return loader.template('account/authorize_unlock', [ language ]);
    }

    async handleSubmit(e) {
        e.preventDefault();

        //if (customer.isLogged()) return;

        let form = this.form.getData();

        await ajax.post('action.php?route=account/authorize.send&language=' + local.get('language'), form, {
            handleSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                if (json.has('redirect')) {
                    location = json.get('redirect');
                }

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                if (json.has('success')) {
                    this.form.state.set('success', json.get('success'));

                    this.update();
                }
            },
            handleError: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
	}
}

customElements.define('authorize-unlock', AuthorizeUnlock);