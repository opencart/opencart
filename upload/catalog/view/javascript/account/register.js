import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('account/register');

// Customer Groups
const customer_groups = await loader.storage('customer/customer_group');

class AccountRegister extends WebComponent {
    async connected() {

    }

    render() {
        let data = {};

        data.customer_groups = customer_groups;

        // Custom Fields
        data.custom_fields = {};

        let customer_group = loader.storage('customer/customer_group-' + config.config_customer_group_id);

        if (customer_group.length) {
            data.custom_fields = customer_group.custom_fields;
        }

        return loader.template('account/register', { ...data, ...language, ...config });
    }

    async onChange(e) {
        let customer_group_info = await this.storage.fetch('customer/customer_group-' + this.value);

        if (customer_group_info) {
            data.custom_fields = customer_group_info.custom_field;
        } else {
            data.custom_fields = [];
        }

        $('.custom-field').addClass('d-none');
        $('.custom-field').removeClass('required');

        for (let i = 0; i < json.length; i++) {
            let custom_field = json[i];

            $('.custom-field-' + custom_field['custom_field_id']).removeClass('d-none');

            if (custom_field['required']) {
                $('.custom-field-' + custom_field['custom_field_id']).addClass('required');
            }
        }
    }

    register(e) {
        e.preventDefault();

        loader.request({
            url: '',
            method: 'POST',
            data: [],
            beforeSend: '',
            afterSend: '',
            onComplete: (json) => {
                let alert = document.getElementById('alert');

                if (json['error']) {
                    alert.append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (json['success']) {
                    alert.append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }
            }
        });
    }
}

customElements.define('account-register', AccountRegister);