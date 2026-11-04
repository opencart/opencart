import { WebComponent } from '../../engine.js';

export class InputTextarea extends WebComponent {
    static formAssociated = true;
    static observedAttributes = [
        'value',
        'required',
        'disabled',
        'readonly'
    ];
	
    styles() {
        return `
            :host {
                display: block;
            }
            :host(:state(invalid)) .form-control {
                border-color: var(--bs-form-invalid-border-color, #dc3545);
            }
            :host(:state(invalid)) .form-control:focus {
                box-shadow: 0 0 0 .25rem rgb(220 53 69 / .25);
            }
            .invalid-feedback {
                display: none;
                margin-top: .25rem;
                font-size: .875em;
                color: var(--bs-form-invalid-color, #dc3545);
            }
            .invalid-feedback:not(:empty) {
                display: block;
            }
        `;
	}

	get label() {
        return this.hasAttribute('label') ? this.getAttribute('label') : '';
    }

    get name() {
        return this.getAttribute('name');
    }

	get value() {
        return this.innerHTML;
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
			<textarea name="${this.name}"${this.label ? ` placeholder="${this.label}"` : ``}  id="input"${this.disabled ? ' disabled' : ''}${this.readonly ? ' readonly' : ''}${this.required ? ' required' : ''} @ref="input" @input="onInput" @change="onChange" class="form-control">${this.value}</textarea>
			<div @ref="error" class="invalid-feedback"></div>
		`;
	}
	
    // Handles the input event and updates the component state with the new value.
	onInput(e) {
        this.innerHTML = e.target.value;
    }

    // A native change event does not leave the shadow root, so send it on.
    onChange(e) {
        this.innerHTML = e.target.value;
    }
		
    handleValue(value) {
        this.input.value = value;

        if (this.input.validity.valid) {
            this.internal.setValidity({});
        } else {
            this.internal.setValidity(this.input.validity, this.input.validationMessage, this.input);
        }
    }

	handleRequired(name, value) {
		this.update();
	}

	handleDisabled(name, value) {
		this.update();
	}

	handleReadonly(name, value) {
		this.update();
	}

    // Updates the field in place, so nothing the user typed (or focus) is lost.
    setError(error) {
        this.input.classList.toggle('is-invalid', !!error);

        this.error.textContent = error ?? '';

		if (error) {
			this.internal.states.add('invalid');
		} else {
			this.internal.states.delete('invalid');
		}
    }
}

customElements.define('input-textarea', InputTextarea);