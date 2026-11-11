import { InputText } from './text.js';

class InputEmail extends InputText {
    render() {
        return `
			<label @ref="label" for="input-email">${this.label}</label>
			<input type="email" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ''} id="input-email"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @input="onInput" @change="onChange" class="form-control"/>
			<div @ref="error" class="invalid-feedback"></div>
        `;
    }
}

customElements.define('input-email', InputEmail);