import { WebComponent } from '../index.js';
import { loader, ajax, local, customer } from '../index.js';

// Language
const language = await loader.language('information/gdpr');

export default class InformationGdpr extends WebComponent {
    constructor() {
        super();

        this.token = '';
    }

    async render() {
        return loader.template('information/gdpr', [ language ]);
	}
	
	handleConnect() {
		this.form.addListener(this.handleSubmit.bind(this));
	}

    handleChange() {
        if (this.value == 'remove') {
            $('#collapse-remove').slideDown();
        } else {
            $('#collapse-remove').slideUp();
        }
    }

    handleSubmit() {
        e.preventDefault();

        let form = this.form.getData();

        ajax.post('action.php?route=information/gdpr.action&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            handleSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.form.state.set('success', json.get('success'));
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('information-gdpr', InformationGdpr);