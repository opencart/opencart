import { InputText } from './text.js';

class InputEmail extends InputText {
    render() {
        return `
        <label @ref="label" for="input">${this.label}</label>
        <input type="email" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ''} id="input"${this.disabled ? ' disabled' : ''}${this.readonly ? ' readonly' : ''}${this.required ? ' required' : ''} class="form-control" @ref="input" @input="onInput" @change="onChange"/>
        <div @ref="error" class="invalid-feedback"></div>
        `;
    }
}

customElements.define('input-email', InputEmail);