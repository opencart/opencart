import { InputText } from './text.js';

class InputPassword extends InputText {
	render() {
        return `
			<label @ref="label" for="input-password">${this.label}</label>
			<input type="password" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ``} id="input-password"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @input="onInput" @change="onChange" class="form-control"/>
			<div @ref="error" class="invalid-feedback"></div>
        `;
    }
}

customElements.define('input-password', InputPassword);