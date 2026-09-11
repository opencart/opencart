import { WebComponent } from '../component.js';
import { loader, binded } from '../index.js';

// Name
export const name = 'information-gdpr';

customElements.define('information-gdpr', class extends WebComponent {
    async render() {

    }

    onChange() {
        if (this.value == 'remove') {
            $('#collapse-remove').slideDown();
        } else {
            $('#collapse-remove').slideUp();
        }
    }
});

$('input[name=\'action\']').on('change', function() {

    if (this.value == 'remove') {
        $('#collapse-remove').slideDown();
    } else {
        $('#collapse-remove').slideUp();
    }
});