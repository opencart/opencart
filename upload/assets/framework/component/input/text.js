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
        'required',
        'disabled',
        'readonly'
    ];
    static formAssociated = true;
    states = { error: '' };

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
      input:invalid {
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
      }`;
    }

    get value() {
        return this.input ? this.input.value : (this.getAttribute('value') ?? '');
    }

    set value(value) {

    }

    render() {
        const label = this.getAttribute('label') || '';
        const placeholder = this.getAttribute('placeholder') || '';

        let html = '<input type="text" name="' + + '" @ref="input" @input="handleInput" @change="handleChange" class="form-control"';

        //input.setAttribute('placeholder', this.hasAttribute('placeholder'));

        if (this.hasAttribute('required')) {
            html += ' required';
        }

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        if (this.hasAttribute('readonly')) {
            html += ' readonly';
        }

        // Remove past error classes from inputs
        // this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
        // this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));
        // this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

        html += '/>';

        if (this.state.has('error')) {
            html += '<div class="invalid-feedback">' + this.state.get('error') + '</div>';
        }

        return html;
    }

    handleError(e) {
        console.log(e);

    }

    handleInput(e) {
        console.log(e);

        //this.state.delete('error');
    }

    // A native change event does not leave the shadow root, so send it on.
    handleChange(e) {

    }

    handleReset() {
        this.input.value = this.getAttribute('value') ?? '';

        this.states.set('success', '');
        this.states.set('error', []);
    }

    handleDisabled() {
        this.update();
    }

    handleReadonly() {
        this.update();
    }
});