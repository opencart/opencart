import { WebComponent } from '../library/webcomponent.js';

class XDate extends WebComponent {
    async connected() {
        let icon = '';
        let type = this.getAttribute('type');

        this.innerHTML = '<div class="alert ' + type + '"><i class="fa"></i> ' + this.innerHTML + '</div>';

        // Get the alert element
        let alert = this.shadow.querySelector('.alert');

        if (alert.classList.contains('alert-dismissible')) {
            window.setTimeout(this.event.timeout, 3000);
        }
    }
}

customElements.define('x-date', XDate);