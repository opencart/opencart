import { WebComponent } from '../../library.js';

customElements.define('input-switch', class extends WebComponent {
    static formAssociated = true;
    static observedAttributes = [
		'value',
		'checked',
        'required',
        'disabled',
        'readonly'
    ];
    
	get label() {
        return this.hasAttribute('label') ? this.getAttribute('label') : '';
    }

    get name() {
        return this.getAttribute('name');
    }

    get value() {
        return this.hasAttribute('checked') ? this.getAttribute('value') : '';
    }
	
    get checked() {
        return this.hasAttribute('checked');
    }
	
    get required() {
        return this.hasAttribute('required');
    }

    get disabled() {
        return this.hasAttribute('disabled');
    }

    get readonly() {
        return this.hasAttribute('readonly');
	}

    render() {
		return `
			<label @ref="label" for="input">${this.label}</label>
			<div class="form-switch form-switch-lg"><input type="hidden" name="${this.name}" value=""/><input type="checkbox" name="${this.name}" value="${this.value}" id="input"${this.checked ? ` checked` : ``}${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``} @ref="input" @change="handleChange" class="form-check-input"/></div>
			<div @ref="error" class="invalid-feedback"></div>
		`;
    }

    handleChange(e) {		
		this.toggleAttribute('checked', e.target.checked);
    }

    // Updates the field in place, so nothing the user typed (or focus) is lost.
    handleChecked(name, value) {
		this.input.toggleAttribute('checked', value !== null);
    }

    setError(error) {
        this.input.classList.toggle('is-invalid', !!error);

        this.error.textContent = error ?? '';

		if (error) {
			this.internal.states.add('invalid');
		} else {
			this.internal.states.delete('invalid');
		}
    }
});