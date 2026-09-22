import { WebComponent } from '../component.js';

export class ButtonSubmit extends WebComponent {
    html = '';
    width;

    onConnect() {
        this.html = this.element.innerHTML;
        this.width = this.element.offsetWidth;
    }

    handleState() {

    }

    button(state) {
        if (state === 'loading') {
            this.element.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-light"></i>';
            this.element.style.width = this.width;

            this.element.setAttribute('disabled', '');
        }

        if (state === 'reset') {
            this.element.innerHTML = this.html;
            this.element.style.width = '';

            this.element.removeAttribute('disabled');
        }
    }
}

customElements.define('button-submit', ButtonSubmit);