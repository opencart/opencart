import { InputText } from './text.js';

class InputDateTime extends InputText {
    render() {
        let html = '<input type="datetime-local" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '"';

        if (this.hasAttribute('placeholder')) {
            html += ' placeholder="' + this.getAttribute('placeholder') + '"';
        }

        html += ' class="form-control" @ref="input" @input="handleInput" @change="handleChange"';

        if (this.state.get('disabled')) {
            html += ' disabled';
        }

        if (this.state.get('readonly')) {
            html += ' readonly';
        }

        if (this.state.get('required')) {
            html += ' required';
        }

        html += '/>';
        html += '<div @ref="error" class="invalid-feedback"></div>';

        return html;
    }
}

customElements.define('input-datetime', InputDateTime);