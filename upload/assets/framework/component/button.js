import { WebComponent } from '../component.js';

customElements.define('button-submit', class extends WebComponent {
    static observed = [
        'loading',
        'disabled'
    ];

    html = '';
    width = 0;
    height = 0;

    get loading() {
        return this.hasAttribute('loading');
    }

    set loading(loading) {
        console.log(loading);

        let button = this.bind.get('button');

        if (loading) {
            ///this.width = button.offsetWidth;
            //this.height = button.offsetHeight;

            this.setAttribute('loading', '');
            this.setAttribute('disabled', '');
        } else {
            button.style.width = '';
            button.style.height = '';

            this.removeAttribute('loading');
            this.removeAttribute('disabled');
        }
    }


    get disabled() {
        return this.hasAttribute('disabled');
    }

    set disabled(disabled) {
        if (disabled) {
            this.setAttribute('disabled', '');
        } else {
            this.removeAttribute('disabled');
        }
    }

    connected() {
        this.html = this.innerHTML;
    }

    render() {
        let html = '<button type="submit" data-bind="button"';

        console.log('render');
        console.log(this.loading);

        if (this.disabled || this.loading) {
            html += ' disabled';
        }

        html += '>';

        if (!this.loading) {
            html += this.html;
        } else {
            html += '<i class="fa-solid fa-circle-notch fa-spin text-light"></i>';
        }

        html += '</button>';

        return html;
    }
});