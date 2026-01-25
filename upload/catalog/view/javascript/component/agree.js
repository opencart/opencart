import { WebComponent } from '../component.js';
import { loader } from '../index.js';

class ComponentAgree extends WebComponent {
    async render() {
        let html = '';

        // Information
        let information_info = await loader.storage('catalog/information-' + this.getAttribute('value'));

        if (information_info) {
            html += '<label class="form-check-label">' + this.getAttribute('text').replace('%s', 'information/information.info-' + config.config_account_id + '.html').replace('%s', information_info.title) + '</label>';
            html += '<x-switch name="' + this.getAttribute('name') + '" value="1" input-class="form-switch form-switch-lg form-check-reverse form-check-inline align-top"></x-switch>';
        }

        return html;
    }

    onChange(e) {
        let button = document.getElementById('button-continue');

        if (e.value == 1) {
            button.setAttribute('disabled', '');
        } else {
            button.removeAttribute('disabled');
        }
    }
}

customElements.define('account-register', AccountRegister);