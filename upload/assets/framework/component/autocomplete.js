import { WebComponent } from '../component.js';

class XAutocomplete extends WebComponent {
    async connected() {
        let type = this.getAttribute('value');

        this.innerHTML = '';
    }
}

customElements.define('x-autocomplete', XAutocomplete);