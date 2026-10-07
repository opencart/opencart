import { WebComponent } from '../../engine.js';

customElements.define('button-submit', class extends WebComponent {
    static observed = [
        'loading',
        'disabled'
    ];
    static formAssociated = true;
    html = '';
    width;

    get states() {
        return this.internal.states;
    }

    HandleDisabled() {

    }

    handleLoading() {

    }

    render() {
        let disabled = (this.hasAttribute('disabled') || this.matches(':disabled')) ? ' disabled' : '';

        return `
            <style>
                :host { display: inline-block; }
                button { position: relative; }
                .spinner { display: none; position: absolute; top: 50%; left: 50%; margin: -0.5em 0 0 -0.5em; }
                :host(:state(loading)) button { pointer-events: none; }
                :host(:state(loading)) .label { visibility: hidden; }
                :host(:state(loading)) .spinner { display: inline-block; }
            </style>
            <button type="button" @ref="button" @click="onClick" class="btn btn-primary"${disabled}>
                <span class="label"><slot></slot></span>
                <i class="spinner fa-solid fa-circle-notch fa-spin text-light"></i>
            </button>
        `;
    }

    onConnect() {
        this.html = this.innerHTML;
        this.width = this.offsetWidth;
    }

    onClick(event) {
        this.handleState(['loading'])

        this.internal.form.requestSubmit();
    }

    // Called by the browser when the disabled attribute or a disabled
    // <fieldset> around this button changes.
    formDisabledCallback(disabled) {
        if (this.button) this.button.disabled = disabled;
    }

    /*
    handleState(state) {
        if (state === 'loading') {
            this.button.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-light"></i>';
            this.button.style.width = this.width;

            this.button.setAttribute('disabled', '');
        } else {
            this.button.innerHTML = this.html;
            this.button.style.width = '';

            this.button.removeAttribute('disabled');
        }
    }
     */
});