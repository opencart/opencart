import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/register');

export default class AccountRegister extends WebComponent {
    constructor() {
        super();

        this.token = '';
    }

    states() {
        return {
            submitting: false,
            success: '',
            error: []
        };
    }

    async render() {
        if (this.state.get('success')) {
            return `<ui-toast>${this.state.get('success')}</ui-toast>`;
        }

        return loader.template('account/register', [language, config]);
    }

    async handleConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=account/register.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        //if (customer.isLogged()) return;

        console.log('handleSubmit called');

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/register.save&language=' + local.get('language') + '&register_token=' + this.token, form, {
            handleSend: () => {
                this.state.set('submitting', true);
            },
            handleComplete: () => {
                this.state.set('submitting', false);
            },
            handleSuccess: async (json) => {
                // Display error messages
                if (json.has('error')) {
                    this.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));

                    this.update();
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
    }

    stateSubmitting(submitting) {
        this.submitter.state.set('submitting', submitting);
    }

    stateError(error) {
        let errors = Object.entries(error);

        if (errors.length === 0) return;

        for (let [key, value] of errors) {
            if (key === 'warning') continue;

            let label = this.root.querySelector('[for="input-' + key + '"]');

            if (label) {
                label.state.set('error', true);
            }

            let input = this.root.querySelector('#input-' + key);

            if (input) {
                input.state.set('error', value);
            }
        }
    }

    stateAgree(e) {
        this.submitter.state.set('disabled', !e.target.hasAttribute('checked'));
    }
}

customElements.define('account-register', AccountRegister);