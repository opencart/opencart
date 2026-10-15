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
            error: ''
        };
    }

    styles() {
        return ``;
    }

    render() {
        console.dir(this);

        let html = '<slot name="label"></slot>';

        html += '<input type="text" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '"';

        if (this.hasAttribute('placeholder')) {
            html += ' placeholder="' + this.getAttribute('placeholder') + '"';
        }

        let error = this.state.get('error');

        html += ' class="form-control ' + (error ? 'is-invalid' : '') + '" @ref="input" @input="handleInput" @change="handleChange"';

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
            html += '<div class="invalid-feedback">' + error + '</div>';
        }

        return html;
    }

    handleState(keys, state) {
        console.log('input-text handleState', keys, state);

        if (keys.includes('error')) {


            this.handleError();
        }

        if (keys.includes('success')) {
            this.handleSuccess();
        }

        if (keys.includes('input')) {
            this.handleInput();
        }
    }

    handleError(e) {
        this.update();
    }

    handleInput(e) {
        this.state.set('value', e.target.value);
    }

    // A native change event does not leave the shadow root, so send it on.
    handleChange(e) {
        this.state.set('value', e.target.value);
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
});