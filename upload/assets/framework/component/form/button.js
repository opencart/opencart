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
            submitting: false,
            disabled: false
        };
    }

    #html = '';
    #width;

    render() {
        let html = '<button type="submit" @ref="button" class="btn btn-primary"';

        if (this.state.get('submitting')) {
            html += ' style="width: ' + this.#width + 'px;"';
        }

        if (this.state.get('disabled') || this.state.get('submitting')) {
            html += ' disabled';
        }

        html += '>';

        if (this.state.get('submitting')) {
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

        this.state.set('disabled', this.hasAttribute('disabled'));
    }

    handleState(changed, state) {
        console.log('button-submit handleState', changed, state);

        this.update();
    }
});