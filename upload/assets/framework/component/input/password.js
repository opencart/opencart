import { WebComponent } from '../../engine.js';

class InputPassword extends WebComponent {
    render() {
        let html = '<slot name="label"></slot>';

        html += '<input type="password" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '"';

        if (this.hasAttribute('placeholder')) {
            html += ' placeholder="' + this.getAttribute('placeholder') + '"';
        }

        let error = this.state.get('error');

        html += ' class="form-control' + (error ? ' is-invalid' : '') + '"';

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
}

customElements.define('input-password', InputPassword);