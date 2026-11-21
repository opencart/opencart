import { WebComponent } from '../../framework.js';

customElements.define('input-checkbox', class extends WebComponent {
    static formAssociated = true;

    render() {
        return `<input type="checkbox" class="form-check-input" @change="handleChange"/>`;
    }

    handleChange(e) {
        let stack = [];

        let elements = document.querySelectorAll(e.target.getAttribute('data-target'));

        for (let element of elements)  {
            if (element.matches('input[type=\'checkbox\']')) {
                stack.push(element);
           } else {
               let checkboxes = element.querySelectorAll('input[type=\'checkbox\']');

               for (let checkbox of checkboxes) {
                   stack.push(checkbox);
               }
           }
        }

        for (let element of stack) {
            if (!element.parentElement.matches('checkbox-all')) {
                element.toggleAttribute('checked', !e.target.checked);
            }
        }
	}
	
	reset() { 
		this.input.classList.remove('is-invalid');

		this.internal.states.delete('invalid');
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
});