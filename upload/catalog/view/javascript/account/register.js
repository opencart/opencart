import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/register');

export default class AccountRegister extends WebComponent {
    states() {
        return {
            submitting: false,
            success: '',
            error: []
        };
    }

    constructor() {
        super();

        this.token = '';
    }

    template() {
        return loader.template.fetch('account/register');
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
        console.dir(this.form.elements);

        let form = new FormData(this.form);

        console.dir(form.values());

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
        console.log('handleSubmitting called with state:', submitting);
        //console.dir(this.submitter);

        this.submitter.state.set('submitting', submitting);
    }

    stateError(error) {
        console.log('stateError called with error:', error);

        let errors = Object.entries(error);

        if (errors.length === 0) return;

        for (let [key, value] of errors) {
            if (key === 'warning') continue;


            if (this[key]) {
                //this[key].state.set('label', value);
                this[key].state.set('error', value);
            }
        }
    }

    stateAgree(e) {
        this.submitter.state.set('disabled', !e.target.hasAttribute('checked'));
    }
}

customElements.define('account-register', AccountRegister);