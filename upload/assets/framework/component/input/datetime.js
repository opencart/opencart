import { InputText } from './text.js';

class InputDateTime extends InputText {
    render() {
        return `
			<label @ref="label" for="input-datetime">${this.label}</label>
			<input type="datetime-local" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ''} id="input-datetime"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @input="onInput" @change="onChange" class="form-control"/>
			<div @ref="error" class="invalid-feedback"></div>
        `;
    }
}

customElements.define('input-datetime', InputDateTime);