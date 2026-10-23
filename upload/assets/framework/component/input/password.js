import { InputText } from './text.js';

class InputPassword extends InputText {
    render() {
        return `
        <label @ref="label" for="input">${this.label}</label>
        <input type="password" name="${this.name}" value="${this.value}"${this.disabled ? ' disabled' : ''}${this.readonly ? ' readonly' : ''}${this.required ? ' required' : ''} class="form-control" @ref="input" @input="onInput" @change="onChange"/>
        <div @ref="error" class="invalid-feedback"></div>
        `;
    }
}

customElements.define('input-password', InputPassword);