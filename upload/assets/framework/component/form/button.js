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

    render() {
        return '<button type="submit" @ref="button" class="btn btn-primary"' + (this.state.get('disabled') ? ' disabled' : '') + '>' + this.html + '</button>';
    }

    handleConnect() {
        this.html = this.innerHTML;
        this.state.set('disabled', this.hasAttribute('disabled'));
    }

    stateSubmitting(submitting) {
        if (submitting) {
            this.button.style.width = this.offsetWidth + 'px';
            this.button.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
            this.button.toggleAttribute('disabled', true);
        } else {
            this.button.style.width = '';
            this.button.innerHTML = this.html;
            this.button.toggleAttribute('disabled', false);
        }
    }

    stateDisabled(disabled) {
        this.button.toggleAttribute('disabled', disabled);
    }
}); 