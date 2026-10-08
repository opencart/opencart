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

        data.set('language', local.get('language'));
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

    onSuccess() {

    }

    onAgree(e) {
        console.log(e);

        this.bind.get('agree').checked;

        e.target.value

        this.bind.get('submitter').toggleAttribute('disabled', true);

    }
}

customElements.define('account-register', AccountRegister);