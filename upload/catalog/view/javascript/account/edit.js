import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/edit');

// Library
const session = await loader.library('session');

class AccountEdit extends WebComponent {
    async render() {
        let data = {};

        //let customer = session.get('customer');
        let customer = new Map();

        data.firstname = customer.get('firstname');
        data.lastname = customer.get('lastname');
        data.email = customer.get('email');
        data.telephone = customer.get('telephone');

        // Custom Fields
        data.custom_fields = {};

        let customer_group = await loader.storage('customer/customer_group-' + customer.get('customer_group_id'));

        if (customer_group.length) {
            data.custom_fields = customer_group.custom_fields;
        }

        return loader.template('account/edit', { ...data, ...language });
    }

    submit(e) {
        e.preventDefault();

        this.button.state = 'loading';
    }
}

customElements.define('account-edit', AccountEdit);