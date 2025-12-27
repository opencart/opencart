import { WebComponent } from '../component.js';

class InformationGdpr extends WebComponent {
    async connected() {

    }

    onChange() {
        if (this.value == 'remove') {
            $('#collapse-remove').slideDown();
        } else {
            $('#collapse-remove').slideUp();
        }
    }
}

customElements.define('Information-gdpr', InformationGdpr);

$('input[name=\'action\']').on('change', function() {

    if (this.value == 'remove') {
        $('#collapse-remove').slideDown();
    } else {
        $('#collapse-remove').slideUp();
    }
});