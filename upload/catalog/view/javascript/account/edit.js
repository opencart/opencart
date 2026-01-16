import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('catalog');

// Library
const session = loader.library('session');

// Language
const language = loader.language('account/edit');

class AccountEdit extends WebComponent {
    async connected() {
        let data = {};

        let customer = session.get('customer');

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

        let response = loader.template('account/register', { ...data, ...language, ...config });

        response.then(this.render.bind(this));
        response.then(this.addEvent.bind(this));
    }

    render(html) {
        this.innerHTML = html;
    }

    addEvent() {
        let form = this.querySelector('#form-customer');

        form.addEventListener('submit', this.onSubmit.bind(this));
    }

    onSubmit(e) {
        e.preventDefault();

    }
}

customElements.define('account-edit', AccountEdit);