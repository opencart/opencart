import { WebComponent } from '../../engine.js';

/**
 * <form-group label="First name" help="As it appears on your passport" inline>
 *     <input-text name="firstname" required></input-text>
 * </form-group>
 *
 * One field of a form: label, the field itself, a help line, and the error message.
 *
 * Attributes
 *   label        label text; the label is created next to the field and wired to it
 *   help         a line of help text under the field
 *   horizontal   label beside the field instead of above it
 *   label-width  width of that label column (default 16.6667%, like Bootstrap's col-2)
 *   inline       show the browser's own validation message here instead of in its bubble
 *
 * Error
 *   group.error = 'E-Mail Address does not appear to be valid!';   // show
 *   group.error = '';                                              // clear
 *
 *   A field that displays its own message (input-text) is given the text; anything
 *   else (a plain <input>, <select>, a switch...) is marked invalid - with its custom
 *   state if it has one, with Bootstrap's is-invalid class if it does not - and the
 *   group shows the text under it. Editing the field clears the error.
 *
 * The field stays a child of the group, in the same tree as the surrounding <form>,
 * so it is still part of the form's data and validation. For the same reason the
 * <label> is created in the light DOM too: a <label for> inside this component's
 * shadow root could not reach the field. A page that wants its own label can supply
 * one with slot="label" and the group leaves it alone.
 */
customElements.define('form-group', class extends WebComponent {
    get required() {

    }

    set required(value) {

    }

    get error() {
        return this.message;
    }

    set error(message) {
        this.message = String(message ?? '');

        this.show();
    }

    get validity() {
        return this.internal.validity;
    }

    checkValidity() {
        return this.internal.checkValidity();
    }

    reportValidity() {
        return this.internal.reportValidity();
    }

    render() {
        //let label = this.slotted('label');
        //let input = this.slotted('input');
        //let help = this.slotted('help');
        //let error = this.slotted('error');

        let html = '<div class="form-group">';

        html += '<slot name="label"></slot>';
        html += '<slot name="input"></slot>';
        html += '<div class="text-help"></div>';

        return '<div class="form-group">' + this.innerHTML + '</div>';
    }

    onConnected() {

    }

    // would have given it.
    onInvalid(e) {
        if (!this.hasAttribute('inline')) return;

        e.preventDefault();

        let control = e.target;

        this.error = control.validationMessage ?? control.internal?.validationMessage ?? '';

        let form = control.form ?? control.internal?.form ?? control.closest('form');

        if (form && !this.constructor.focused.has(form)) {
            this.constructor.focused.add(form);

            queueMicrotask(() => this.constructor.focused.delete(form));

            control.focus?.();
        }
    }
});