import { WebComponent } from '../../engine.js';

customElements.define('button-submit', class extends WebComponent {
    observed = [
        'loading',
        'disabled'
    ];
    html = '';
    width;

    HandleDisabled() {

    }

    handleLoading() {

    }

    render() {
        return '<button type="submit" @ref="button"></button>';
    }

    onConnect() {
        this.html = this.innerHTML;
        this.width = this.offsetWidth;
    }

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
});