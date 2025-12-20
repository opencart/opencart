import { WebComponent } from '../index.js';

class XAccount extends WebComponent {
    async connected() {

    }
}

customElements.define('x-account', XAccount);

$('#review').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#review').load(this.href);
});
