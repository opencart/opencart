import { InputText } from './text.js';

class InputUrl extends InputText {
    render() {
        return `
			<label @ref="label" for="input-url">${this.label}</label>
			<input type="url" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ``} id="input-url"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} class="form-control" @ref="input" @input="onInput" @change="onChange"/>
			<div @ref="error" class="invalid-feedback"></div>
        `;
    }
}

customElements.define('input-url', InputUrl);