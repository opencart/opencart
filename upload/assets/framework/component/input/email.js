import { WebComponent } from '../../engine.js';

customElements.define('input-email', class extends WebComponent {
    static observedAttributes = [
        'invalid',
        'error',
        'disabled',
        'readonly',
        'required'
    ];
    static formAssociated = true;

    render() {
        let label = [ ...(this.internal.labels ?? []) ].map(element => element.textContent.trim()).join(' ');

       // html += '<input type="email" value="' + escapeAttribute(this.getAttribute('value') ?? '') + '" class="form-control" @ref="input" @input="onInput" @change="onChange"';

        if (this.hasAttribute('placeholder')) html += ' placeholder="' + escapeAttribute(this.getAttribute('placeholder')) + '"';

        if (this.hasAttribute('autocomplete')) html += ' autocomplete="' + escapeAttribute(this.getAttribute('autocomplete')) + '"';

        if (label) html += ' aria-label="' + escapeAttribute(label) + '"';

        if (this.hasAttribute('required')) {
        //    html += ' required';
        }

        if (this.hasAttribute('disabled')) html += ' disabled';

        //return html + '/>';
    }
});