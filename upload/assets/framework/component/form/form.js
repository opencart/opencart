import { WebComponent } from '../../engine.js';

customElements.define('form-input', class extends WebComponent {
    styles() {
        return `
        :host {
            display: block;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        ::slotted(button[type='submit']) {
            align-self: flex-start;
            margin-top: 0.5rem;
        }
        
        :host([data-submitting]) ::slotted(button[type='submit']) {
            opacity: 0.6;
            pointer-events: none;
        }`;
    }

    initialState() {
        return { submitting: false };
    }

    render() {
        return `<form @ref="form" @submit="onSubmit"><slot></slot></form>`;
    }

    async onSubmit(e) {
        e.preventDefault();

        const skip = this.hasAttribute('novalidate');

        if (!skip && !this.form.reportValidity()) return; // browser already focused/flagged the first invalid field

        this.state.set('submitting', true);

        const form = new FormData(this.form);

        const values = Object.fromEntries(form.entries());

        //this.emit('my-form-submit', { values, form_data });

        this.state.set('submitting', false);
    }

    handleState(keys) {
        if (keys.includes('submitting')) {
            this.toggleAttribute('submitting', this.state.get('submitting'));
        }

        if (keys.includes('error')) {
            this.handleError();
        }
    }

    handleError() {
        let errors = this.state.get('error');

        for (let [ key, error ] of errors) {
            this.form.get(key).state.set('error', error);
        }
    }

    reset() {
        this.form.reset();
    }

    checkValidity() {
        return this.form.checkValidity();
    }

    reportValidity() {
        return this.form.reportValidity();
    }

    get elements() {
        return this.form.elements;
    }
});