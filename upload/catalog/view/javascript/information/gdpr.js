import { WebComponent } from '../index.js';
import { loader, ajax, local, customer } from '../index.js';

// Language
const language = await loader.language('information/gdpr');

export default class InformationGdpr extends WebComponent {
	static formAssociated = true;
	#token = '';

    async render() {
        return loader.template('information/gdpr', [ language ]);
	}
	
	async handleConnect() {
		let json = await ajax.get('action.php?route=information/contact.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token');
		}
	}

    handleChange() {
        if (this.value == 'remove') {
            $('#collapse-remove').slideDown();
        } else {
            $('#collapse-remove').slideUp();
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

		await ajax.post({
			url: 'action.php?route=information/gdpr.action&language=' + local.get('language') + '&customer_token=' + customer.getToken(),
			data: this.form.getData(),
            handleSend: () => {
                this.form.toggleAttribute('submitting', true);
            },
            handleComplete: () => {
                this.form.toggleAttribute('submitting', false);
            },
            handleSuccess: (json) => {
                if (json.has('error')) {
                    this.form.setError(json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.form.setSuccess(json.get('success'));
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('information-gdpr', InformationGdpr);