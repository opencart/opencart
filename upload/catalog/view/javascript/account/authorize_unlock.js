import { WebComponent } from '../index.js';
import { ajax, loader, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/authorize_unlock');

export default class AuthorizeUnlock extends WebComponent {
    states() {
        return {
            submitting: false,
            success: '',
            error: []
        };
	}
	
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
                this.state.set('submitting', true);
            },
            handleComplete: () => {
                this.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                if (json.has('redirect')) {
                    location = json.get('redirect');
                }

                if (json.has('error')) {
                    this.state.set('error', json.get('error'));
                }

                if (json.has('success')) {
                    this.state.set('success', json.get('success'));

                    this.update();
                }
            },
            handleError: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
	}
	
	handleSubmitting(value) {
        this.submitter.state.set('submitting', value);
	}
	
	handleError(error) {
        let errors = Object.entries(error);

        if (errors.length === 0) return;

		for (let [key, value] of errors) {
			if (key === 'warning') continue;

			let input = this.root.getElementById('input-' + key);

			if (input) {
				input.state.set('error', value);
			}
        }
    }
}

customElements.define('authorize-unlock', AuthorizeUnlock);