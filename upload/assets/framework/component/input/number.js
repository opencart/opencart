import { InputText } from './text.js';

class InputNumber extends InputText {
    render() {
        return `
        	<label @ref="label" for="input-number">${this.label}</label>
        	<input type="number" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ``} id="input-number"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @input="onInput" @change="onChange" class="form-control"/>
        	<div @ref="error" class="invalid-feedback"></div>
        `;
    }
}

customElements.define('input-number', InputNumber);