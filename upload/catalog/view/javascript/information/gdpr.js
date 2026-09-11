import { WebComponent } from '../component.js';

customElements.define('information-gdpr', class extends WebComponent {
    async connect() {

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