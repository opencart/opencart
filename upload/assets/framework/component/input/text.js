import { WebComponent } from '../../engine.js';

function escapeAttribute(value) {
    return String(value).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

/**
 * <input-text type="email" name="email" id="input-email" placeholder="E-Mail" required></input-text>
 *
 * A text-like input (text, email, tel, password...) for forms.
 *
 * - The tag is form-associated, so its value is part of FormData and it takes
 *   part in the form's validation (required, email format, ...).
 * - It carries the custom state set as `states`. An invalid field is marked
 *   with `states.add('invalid')` and cleared with `states.delete('invalid')`;
 *   the `:state(invalid)` rule below does the styling. Typing in the field
 *   clears the state by itself.
 *
 * Attributes: name, type, value, placeholder, autocomplete, input-class,
 * required, disabled. Put `id` on this element (not an input-id), so that
 * <label for="..."> points at it.
 */
customElements.define('input-text', class extends WebComponent {
    static formAssociated = true;

    constructor() {
        super();

        // Clicking a <label for="..."> lands on this element, not on the input inside it.
        this.addEventListener('click', () => this.input?.focus());
    }

    get states() {
        return this.internal.states;
    }

    get value() {
        return this.input ? this.input.value : (this.getAttribute('value') ?? '');
    }

    set value(value) {
        if (this.input) {
            this.input.value = value;
        } else {
            this.setAttribute('value', value);
        }

        this.sync();
    }

    get validity() {
        return this.internal.validity;
    }

    checkValidity() {
        return this.internal.checkValidity();
    }

    reportValidity() {
        return this.internal.reportValidity();
    }

    render() {
        let label = [ ...(this.internal.labels ?? []) ].map(element => element.textContent.trim()).join(' ');

        let html = '<style>';
        html += ':host { display: block; }';
        html += ':host(:state(invalid)) .form-control { border-color: var(--bs-form-invalid-border-color, #dc3545); }';
        html += ':host(:state(invalid)) .form-control:focus { box-shadow: 0 0 0 .25rem rgb(220 53 69 / .25); }';
        html += '</style>';

        html += '<input type="' + escapeAttribute(this.getAttribute('type') || 'text') + '"';
        html += ' value="' + escapeAttribute(this.getAttribute('value') ?? '') + '"';
        html += ' class="form-control' + (this.hasAttribute('input-class') ? ' ' + escapeAttribute(this.getAttribute('input-class')) : '') + '"';
        html += ' @ref="input" @input="onInput" @change="onChange"';

        if (this.hasAttribute('placeholder')) html += ' placeholder="' + escapeAttribute(this.getAttribute('placeholder')) + '"';
        if (this.hasAttribute('autocomplete')) html += ' autocomplete="' + escapeAttribute(this.getAttribute('autocomplete')) + '"';
        if (label) html += ' aria-label="' + escapeAttribute(label) + '"';
        if (this.hasAttribute('required')) html += ' required';
        if (this.hasAttribute('disabled')) html += ' disabled';

        return html + '/>';
    }

    async update() {
        await super.update();

        this.sync();
    }

    // Hands the current value and validity to the form.
    sync() {
        this.internal.setFormValue(this.value);

        if (!this.input) return;

        if (this.input.validity.valid) {
            this.internal.setValidity({});
        } else {
            this.internal.setValidity(this.input.validity, this.input.validationMessage, this.input);
        }
    }

    onInput(e) {
        this.states.delete('invalid');

        this.sync();
    }

    // A native change event does not leave the shadow root, so send it on.
    onChange(e) {
        this.dispatchEvent(new Event('change', { bubbles: true }));
    }

    formResetCallback() {
        this.input.value = this.getAttribute('value') ?? '';

        this.states.delete('invalid');

        this.sync();
    }

    formDisabledCallback(disabled) {
        if (this.input) this.input.disabled = disabled;
    }

    formStateRestoreCallback(state) {
        this.value = state;
    }
});