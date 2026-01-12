import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const session = await loader.library('session');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('account/register');

// Template
const template = await loader.template('account/register');

let customer = {};

if (session.has('customer')) {
    customer = session.get('customer');
}

class AccountRegister extends WebComponent {
    async connected() {
        let data = { ...language };

        data.customer_groups = config.get('config_customer_group_list');
        data.customer_group_id = config.get('config_customer_group_id');

        data.config_file_max_size = config.get('config_file_max_size');

        data.config_telephone_status = config.get('config_telephone_status');
        data.config_telephone_required = config.get('config_telephone_required');

        data.customer_group_id = config.get('config_customer_group_id');

        if (customer) {
            data.customer_group_id = customer.get('customer_group_id');
        }

        data.custom_fields = {};

        // Custom Fields
        let customer_group = await loader.storage('customer/customer_group-' + data.customer_group_id);

        if (customer_group) {
            data.custom_fields = customer_group.get('custom_field');
        }

        // If required to agree to terms
        let account_id = config.get('config_account_id');

        // Information
        let information_info = {};

        if (account_id) {
           await loader.storage('catalog/information-' + account_id);
        }



        if (information_info) {
            data.text_agree = language.get('text_agree').replace('%s', 'information/information.info&information_id=' + config.config_account_id).replace('%s', information_info.title);
        } else {
            data.text_agree = '';
        }

        let response = loader.template('account/register', data);

        response.then(this.render);
        response.then(this.addEvent);

    }

    render(html) {
        this.innerHTML = html;
    }

    addEvent() {
        // Attach event to form
        let form = document.getElementById('form-register');

        form.addEventListener('submit', this.onSubmit);

        // Set up the customer group
        let customer_group = document.getElementById('input-customer-group');

        console.log(customer_group);

        customer_group.addEventListener('change', this.onChange);

        // Set up the agree button enabled / disabled
        let agree = document.getElementById('input-agree');

        agree.addEventListener('change', this.onAgree);
    }

    onAgree(e) {
        let button = document.getElementById('button-continue');

        if (e.value == 1) {
            button.addAttribute('disabled');
        } else {
            button.disabled = true;
        }
    }

    async onChange() {
        //fetch();

        ///$this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'], true)

        let customer_group_info = await this.storage.fetch('customer/customer_group-' + data['customer_group_id']);

        if (customer_group_info) {
            data['custom_fields'] = customer_group_info['custom_field'];
        } else {
            data['custom_fields'] = [];
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

    onSubmit(e) {
        e.preventDefault();

        this.request.fetch({

        });
    }

    onComplete(json) {
        let alert = document.getElementById('alert');

        if (json['error']) {
            alert.append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }

        if (json['success']) {
            alert.append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }
    }
}

customElements.define('account-register', AccountRegister);

/*
$('#input-customer-group').on('change', function() {
    $.ajax({
        url: 'index.php?route=account/custom_field&customer_group_id=' + this.value + '&language={{ language }}',
        dataType: 'json',
        success: function(json) {
            $('.custom-field').hide();
            $('.custom-field').removeClass('required');

            for (i = 0; i < json.length; i++) {
                custom_field = json[i];

                $('.custom-field-' + custom_field['custom_field_id']).show();

                if (custom_field['required']) {
                    $('.custom-field-' + custom_field['custom_field_id']).addClass('required');
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-customer-group').trigger('change');
*/