import { WebComponent } from '../../engine.js';

class InputTextarea extends WebComponent {
    render() {
        let html = '<slot name="label"></slot>';

        html += '<textarea name="' + this.getAttribute('name') + '" class="form-control"';

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

        html += ' @ref="input" @input="handleInput" @change="handleChange">' + this.getAttribute('value') + '</textarea>';

        if (error) {
            html += '<div class="invalid-feedback">' + error + '</div>';
        }

        return html;
    }
}

customElements.define('input-textarea', InputTextarea);