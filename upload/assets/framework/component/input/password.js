import { WebComponent } from '../../engine.js';

customElements.define('input-password', class extends WebComponent {
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

    get value() {
        return this.state.get('value');
    }

    set value(value) {
        this.state.set('value', value);
    }

    render() {
        let html = '<slot name="label"></slot>';

        html += '<input type="password" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '" class="form-control"';

        if (this.hasAttribute('placeholder')) {
            html += ' placeholder="' + this.getAttribute('placeholder') + '"';
        }

        let error = this.state.get('error');

        html += ' class="form-control ' + (error ? 'is-invalid' : '') + '"';

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        if (this.hasAttribute('readonly')) {
            html += ' readonly';
        }

        if (this.hasAttribute('required')) {
            html += ' required';
        }

        html += ' @ref="input" @input="handleInput" @change="handleChange"/>';

        if (error) {
            html += '<div class="invalid-feedback">' + error + '</div>';
        }

        return html;
    }

    handleState(keys, state) {
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

    handleInput(e) {
        console.log(e);

        this.state.set('value', e.target.value);
    }

    // A native change event does not leave the shadow root, so send it on.
    handleChange(e) {
        this.state.set('value', e.target.value);
    }

    handleError(e) {
        this.update();
    }

    handleAttribute() {

    }
});