import { WebComponent } from '../component.js';

customElements.define('form-submit', class extends WebComponent {
    render() {
        let icon = '';
        let type = this.getAttribute('type');

        return '<div class="alert ' + type + '" data-on="load:timeout"><i class="fa ' + icon + '"></i> ' + this.innerHTML + '</div>';
    }

    timeout(e) {
        // Get the alert element
        let alert = this.querySelector('.alert');

        if (alert.classList.contains('alert-dismissible')) {
            window.setTimeout(this.timeout, 3000);
        }
    }

    _timeout(e) {
        this.style.opacity = 1;

        const fade = () => {
            if (this.style.opacity > 0) {
                this.style.opacity -= 0.10;
            } else {
                window.clearInterval(this._timer);

                this.remove();
            }
        }

        this._timer = window.setInterval(fade.bind(this), 60);
    }
});