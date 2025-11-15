import { WebComponent } from './../library/webcomponent.js';

class XAlert extends WebComponent {
    event = {
        connected: async () => {
            let icon = '';
            let type = this.getAttribute('type');

            switch (type) {
                case 'success':
                    icon = 'fa-circle-check';
                    break;
                case 'danger':
                    icon = 'fa-circle-exclamation';
                    break;
                case 'warning':
                    icon = 'fa-circle-info';
                    break;
                case 'info':
                    icon = 'fa-circle-info';
                    break;
            }

            this.innerHTML = '<div class="alert ' + type + '"><i class="fa ' + icon + '"></i> ' + this.innerHTML + '</div>';

            // Get the alert element
            let alert = this.shadow.querySelector('.alert');

            if (alert.classList.contains('alert-dismissible')) {
                window.setTimeout(this.event.timeout, 3000);
            }
        },
        timeout: (e) => {
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
    }
}

customElements.define('x-alert', XAlert);