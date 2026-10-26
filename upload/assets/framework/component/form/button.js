import { WebComponent } from '../../engine.js';

customElements.define('button-submit', class extends WebComponent {
	static shadow = false;
	static formAssociated = true;
    static observedAttributes = [
        'submitting',
        'disabled'
	];
	
    /* Will only manage states listed here */
    states() {
        return {
            submitting: false,
            disabled: false
        };
    }

	constructor() { 
		super();

        this.html = this.innerHTML;
        this.state.set('disabled', this.hasAttribute('disabled'));
	}

    render() {
        return '<button type="submit" @ref="button" class="btn btn-primary"' + (this.state.get('disabled') ? ' disabled' : '') + '>' + this.html + '</button>';
    }

	// Handle state changes for the 'submitting' attribute
	handleState(key, value) {
		switch (key) {
			case 'submitting':
				if (value) {
					this.button.style.width = this.offsetWidth + 'px';
					this.button.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
					this.button.toggleAttribute('disabled', true);
				} else {
					this.button.style.width = '';
					this.button.innerHTML = this.html;
					this.button.toggleAttribute('disabled', false);
				}
				break;
			case 'disabled':
				this.button.toggleAttribute('disabled', value);
				break;
		}
	}

	handleClick() { 
		this.internal.form.requestSubmit();
	}
}); 