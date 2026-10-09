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

    async render() {
        //if (!customer.isLogged()) return;

        if (this.state.has('success')) {
            return `<div class="success">${this.state.get('success')}</div>`;
        }

        let data = new Map();

        return loader.template('account/register', [ data, language, config ]);
    }

    async onConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=account/register.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
        }

        this.state.addListener('error', );
    }

    async onSubmit(e) {
        e.preventDefault();

        //if (customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/register.save&language=' + local.get('language') + '&register_token=' + this.token, form, {
            beforeSend: () => {
                this.submitter.toggleAttribute('loading', true);
            },
            onComplete: () => {
                this.submitter.toggleAttribute('loading', false);
            },
            onSuccess: (json) => {
                this.form.state.clear();

                // Display error messages
                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));

                    this.update();
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    onAgree(e) {
        this.submitter.toggleAttribute('disabled', !e.target.hasAttribute('checked'));
    }
}

customElements.define('account-register', AccountRegister);