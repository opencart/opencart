import { WebComponent } from '../../engine.js';

class InputTextarea extends WebComponent {
    static formAssociated = true;
    static observedAttributes = [
        'name',
        'value',
        'required',
        'disabled',
        'readonly'
    ];

    states() {
        return {
            value: this.getAttribute('value') ?? '',
            error: '',
            required: this.hasAttribute('required'),
            disabled: this.hasAttribute('disabled'),
            readonly: this.hasAttribute('readonly')
        };
    }

    render() {
        let html = '<textarea name="' + this.getAttribute('name') + '" class="form-control"';

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

        html += '>' + this.getAttribute('value') + '</textarea>';
        html += '<div @ref="error" class="invalid-feedback"></div>';

        return html;
    }
}

customElements.define('input-textarea', InputTextarea);