import { InputText } from './text.js';

class InputTel extends InputText {
    render() {
        let html = '<slot name="label"></slot>';

        html += '<input type="tel" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '"';

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
}

customElements.define('input-tel', InputTel);