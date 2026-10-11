import { WebComponent } from '../../engine.js';

customElements.define('form-submit', class extends WebComponent {
    /* Will only manage states listed here */
    states() {
        return {
            submitting: false,
            success: '',
            error: []
        }
    }

    styles = `
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

    render() {
        return `<form @ref="form" @submit="handleSubmit"><slot></slot></form>`;
    }

    handleConnect() {
        console.log('handleConnect', this);
    }

    handleDisconnect() {
        console.log('handleDisconnect', this);
    }

    async handleSubmit(e) {
        e.preventDefault();

        //slot.assignedElements({ flatten: true })

        //console.log('this.form.elements', this.form.elements);

        this.submitter.state.set('loading', true);

        const form = new FormData(this.form);

        console.log(form);
    }

    handleError() {
        this.state.set('submitting', false);

        let errors = this.state.get('error');

        for (let [ key, value ] of errors) {
            if (key === 'warning') {

            }

            this.form.get(key).state.set('error', value);
        }
    }

    handleDisabled() {

    }

    handleReadOnly() {

    }
});