import { WebComponent } from '../../engine.js';

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
    static observedAttributes = [
        'name',
        'value',
        'label',
        'placeholder',
        'required',
        'disabled',
        'invalid',
        'error',
        'disabled',
        'readonly',
        'required'
    ];
    static formAssociated = true;

    constructor() {
        super();

        // Clicking a <label for="..."> lands on this element, not on the input inside it.
        this.addEventListener('click', () => this.input?.focus());
    }

    styles() {
        return `
      :host {
        display: block;
        font-family: system-ui, sans-serif;
        margin-bottom: 1rem;
      }
      label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
      }
      input {
        width: 100%;
        box-sizing: border-box;
        padding: 0.5rem 0.625rem;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 6px;
      }
      input:focus {
        outline: 2px solid #4a90d9;
        outline-offset: 1px;
      }
      :host([data-invalid]) input {
        border-color: #d33;
      }
      .error {
        display: block;
        color: #d33;
        font-size: 0.8rem;
        margin-top: 0.25rem;
      }
      .error[hidden] {
        display: none;
      }
    `;
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

    handleError(message) {
        this.internals.setValidity({
            tooShort: true
        }, 'Minimum 3 characters');
    }

    validity() {
        return this.internal.validity;
    }

    checkValidity() {
        return this.internal.checkValidity();
    }

    reportValidity() {
        return this.internal.reportValidity();
    }

    render() {
        const label = this.getAttribute('label') || '';
        const placeholder = this.getAttribute('placeholder') || '';
        const type = this.getAttribute('type') || 'text';


        let input = document.createElement('input');

        Object.assign(input, {
            name: 'name',
            type: 'text',
            value: this.value,
            className: 'form-control'
        });

        console.log(input);

        //input.setAttribute('placeholder', this.hasAttribute('placeholder'));

        //html += '<@ref="input" @input="onInput" @change="onChange"';

        if (this.hasAttribute('required')) input.toggleAttribute('required', true);
        if (this.hasAttribute('disabled')) input.toggleAttribute('disabled', true);
        if (this.hasAttribute('readonly')) input.toggleAttribute('readonly', true);

        return input;
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

    handleInput(e) {
        console.log(e);

        this.states.delete('invalid');

        this.sync();
    }

    // A native change event does not leave the shadow root, so send it on.
    handleChange(e) {
        this.dispatchEvent(new Event('change', {
            bubbles: true
        }));
    }

    handleReset() {
        this.input.value = this.getAttribute('value') ?? '';

        this.states.delete('invalid');

        this.sync();
    }

    handleDisabled(disabled) {
        if (this.input) this.input.disabled = disabled;
    }
});