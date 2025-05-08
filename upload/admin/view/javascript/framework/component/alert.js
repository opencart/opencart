import { WebComponent } from './../webcomponent.js';

class XAlert extends WebComponent {
    event = {
        connected: async () => {
            this.data.type = this.dataset.type;
            this.data.message = this.innerHTML;

            switch (this.dataset.type) {
                case 'success':
                    this.data.icon = 'fa-circle-check';
                    break;
                case 'danger':
                    this.data.icon = 'fa-circle-exclamation';
                    break;
                case 'warning':
                    this.data.icon = 'fa-circle-info';
                    break;
                case 'info':
                    this.data.icon = 'fa-circle-info';
                    break;
            }

            this.shadow.innerHTML = await this.template.render('alert.html', this.data);

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