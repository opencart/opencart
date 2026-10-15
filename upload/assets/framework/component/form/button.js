import { WebComponent } from '../../engine.js';

customElements.define('button-submit', class extends WebComponent {
    static shadow = false;
    static formAssociated = true;
    static observedAttributes = [
        'loading',
        'disabled'
    ];
    /* Will only manage states listed here */
    static states() {
        return {
            clicked: false,
            loading: false,
            disabled: false
        };
    }

    #html = '';
    #width;

    render() {
        let html = '<button type="submit" @ref="button" @click="handleClick" class="btn btn-primary"';

        if (this.hasAttribute('loading')) {
            html += ' style="width: ' + this.#width + 'px;"';
        }

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        html += '>';

        if (this.hasAttribute('loading')) {
            html += '<i class="fa-solid fa-circle-notch fa-spin"></i>';
        } else {
            html += this.#html;
        }

        html += '</button>';

        return html;
    }

    handleConnect() {
        this.#html = this.innerHTML;
        this.#width = this.offsetWidth;
    }

    test() {
        //console.log('form-submit');
        //console.dir(this.form);
        //console.log(this.form.elements);
    }

    handleState(changed, state) {
        console.log('button-submit handleState', changed, state);
    }

    handleClick(e) {
        if (this.hasAttribute('disabled') || this.hasAttribute('loading')) return;

        //console.log('handleClick');

        //this.state.set({
        //   clicked: true,
        //   loading: true,
        //    disabled: true
        //});
    }
    /*
    handleSubmitting(e) {
        if (this.hasAttribute('disabled') || this.hasAttribute('loading')) return;

        this.setAttribute('loading', '');

        console.log('handleClick');
        console.dir(this);
        console.log(this.internal);

        //this.internal.form?.requestSubmit();
    }
    */

    handleLoading() {
        this.update();
    }

    handleDisabled() {
        this.update();
    }
});