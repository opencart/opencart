import { InputText } from './text.js';

class InputDate extends InputText {
    render() {
        return `
			<label @ref="label" for="input-date">${this.label}</label>
			<input type="date" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ``} id="input-date"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @input="onInput" @change="onChange" class="form-control"/>
			<div @ref="error" class="invalid-feedback"></div>
        `;
    }
}

customElements.define('input-date', InputDate);