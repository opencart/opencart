import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('account/register');
const config = await loader.config('catalog');

console.log(language);

class AccountRegister extends WebComponent {
    async connected() {

        let data = [];

        this.data.text_account_already = language.text_account_already.replace('%s', 'route=account/login');

        /*
        data['customer_groups'] = this.config.get('config_customer_group_list');
        data['customer_group_id'] = this.config.get('config_customer_group_id');

        data['error_upload_size'] = this.language.get('error_upload_size').replace('%s', this.config.get('config_file_max_size'));

        data['config_telephone_status'] = this.config.get('config_telephone_status');
        data['config_telephone_required'] = this.config.get('config_telephone_required');

        // Custom Fields
        data['custom_fields'] = [];

        let customer_group_info = await this.storage.fetch('customer/customer_group-' + data['customer_group_id']);

        if (customer_group_info) {
            data['custom_fields'] = customer_group_info['custom_field'];
        } else {
            data['custom_fields'] = [];
        }

        // Information
        let information_info = await this.storage.fetch('catalog/information-' + this.config.get('config_account_id'));

        if (information_info) {
            data['text_agree'] = this.language.get('text_agree').replaceAll('%s', this.url.link('information/information.info', 'language=' . this.config.get('config_language') +  '&information_id=' . this.config.get('config_account_id')), information_info['title']);
        } else {
            data['text_agree'] = '';
        }
*/
        console.log(data);

        this.render('account/register');
    }

    render(html) {




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