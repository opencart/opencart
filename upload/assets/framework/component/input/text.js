import { WebComponent } from '../../engine.js';
/*
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
export class InputText extends WebComponent {
    static formAssociated = true;
    static observedAttributes = [
        'name',
        'value',
        'required',
        'disabled',
        'readonly'
    ];

    static states() {
        return {
            value: '',
            error: '',
            required: false,
            disabled: false,
            readonly: false
        };
    }

    styles() {
        return `               
            :host { display: block; }
            :host([invalid]) .form-control, :host([error]:not([error=""])) .form-control { border-color: var(--bs-form-invalid-border-color, #dc3545); }
            :host([invalid]) .form-control:focus, :host([error]:not([error=""])) .form-control:focus { box-shadow: 0 0 0 .25rem rgb(220 53 69 / .25); }
            :host(:state(invalid)) .form-control { border-color: var(--bs-form-invalid-border-color, #dc3545); }
            :host(:state(invalid)) .form-control:focus { box-shadow: 0 0 0 .25rem rgb(220 53 69 / .25); }
            .invalid-feedback { display: none; margin-top: .25rem; font-size: .875em; color: var(--bs-form-invalid-color, #dc3545); }
            .invalid-feedback:not(:empty) { display: block; }
        `;
    }

    render() {
        let html = '<slot name="label"></slot>';

        html += '<input type="text" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '"';

        if (this.hasAttribute('placeholder')) {
            html += ' placeholder="' + this.getAttribute('placeholder') + '"';
        }

        let error = this.state.get('error');

        html += ' class="form-control' + (error ? ' is-invalid' : '') + '" @ref="input" @input="handleInput" @change="handleChange"';

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        if (this.hasAttribute('readonly')) {
            html += ' readonly';
        }

        if (this.hasAttribute('required')) {
            html += ' required';
        }

        html += '/>';

        if (error) {
            html += '<div @ref="feedback" class="invalid-feedback">' + error + '</div>';
        }

        return html;
    }

    handleConnect() {

    }

    handleInput(e) {
        this.state.set('value', e.target.value);
    }

    // A native change event does not leave the shadow root, so send it on.
    handleChange(e) {
        this.state.set('value', e.target.value);
    }

    handleError(state) {
        console.log('input-text handleError', state);

        this.update();
    }

    handleReset() {
        //this.input.value = this.getAttribute('value') ?? '';
    }

    handleRequired() {
        this.update();
    }

    handleDisabled() {
        this.update();
    }

    handleReadonly() {
        this.update();
    }
}

customElements.define('input-text', InputText);