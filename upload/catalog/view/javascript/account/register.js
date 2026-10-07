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

        let data = new Map();

        data.set('token', this.token);

        return loader.template('account/register', [ data, language, config ]);
    }

    async onConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=account/register.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    async onSubmit(e) {
        e.preventDefault();

        if (customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/register.save&language=' + local.get('language') + '&register_token=' + this.token, form, {
            beforeSend: () => {
                this.submitter.toggleAttribute('loading', true);
            },
            onComplete: () => {
                this.submitter.toggleAttribute('loading', false);
            },
            onSuccess: (json) => {
                console.log(json);

                // Display error messages
                if (json.has('error')) {
                    for (let key in json.get('error')) {
                        let value = key.replaceAll('_', '-');

                        console.log('#input-' + value);

                        let input = this.form.querySelector('#input-' + value);

                        console.log(input);

                        if (input) {
                            console.log('works');

                            input.internal.validationMessage("Please fill out this field—it is required!");

                            input.internal.setCustomValidity("Please fill out this field—it is required!");
                        }
                    }
                }

                // Display success message
                if (json.has('success')) {
                    this.alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    onAgree(e) {
        console.log(e);

        //this.ref('agree');
    }
}

customElements.define('account-register', AccountRegister);