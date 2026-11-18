import { InputText } from './text.js';

class InputTel extends InputText {
    render() {
        return `
			<label @ref="label" for="input-tel">${this.label}</label>
			<input type="tel" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ``} id="input-tel"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @input="onInput" @change="onChange" class="form-control"/>
			<div @ref="error" class="invalid-feedback"></div>
        `;
    }
}

customElements.define('input-tel', InputTel);