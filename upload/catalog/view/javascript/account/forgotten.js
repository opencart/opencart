import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/forgotten');

export default class AccountForgotten extends WebComponent {
    constructor() {
        super();

        this.token = '';
    }

    render() {
        //if (customer.isLogged()) return;

        return loader.template('account/forgotten', [ language ]);
    }

    async handleConnect() {
        let json = await ajax.get('action.php?route=account/forgotten.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        //if (customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/forgotten.confirm&language=' + local.get('language') + '&token=' + this.token, form, {
            handleSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                this.form.state.clear();

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('account-forgotten', AccountForgotten);