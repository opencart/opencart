import { WebComponent } from '../index.js';

class XAccount extends WebComponent {
    async connected() {

    }
}

customElements.define('x-account', XAccount);


$('input[name=\'action\']').on('change', function() {

    if (this.value == 'remove') {
        $('#collapse-remove').slideDown();
    } else {
        $('#collapse-remove').slideUp();
    }
});