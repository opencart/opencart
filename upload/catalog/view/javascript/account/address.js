import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/address');

class AccountAddress extends WebComponent {
    async connected() {
        this.innerHTML = await this.load.template('account/address', { ...language });




        let buttons = this.querySelectorAll('.btn-danger');

        buttons.forEach(button => {
            button.addEventListener('click', this.onClick);
        });
    }

    onClick(e) {

    }
}

customElements.define('account-address', AccountAddress);

const address = document.getElementById('address');

$('#address').on('click', '.btn-danger', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#address').load('index.php?route=account/address.list&language=' + language + '&customer_token={{ customer_token }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});