import { WebComponent } from '../index.js';

customElements.define('ui-alert', class extends WebComponent {
    static observed = ['type'];

    render() {
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        let timer = setInterval(this.timeout, 500);

        let type = 'primary';

        if (this.hasAttribute('type')) {
            type = this.getAttribute('type');
        }

        let icon = '';

        switch(type) {
            case 'primary':
                icon = '<i class="fa-solid fa-circle-check"></i>';
                break;
            case 'secondary':
                icon = '<i class="fa-solid fa-circle-check"></i>';
                break;
            case 'success':
                icon = '<i class="fa-solid fa-circle-check"></i>';
                break;
            case 'danger':
                icon = '<i class="fa-solid fa-triangle-exclamation"></i>';
                break;
            case 'warning':
                icon = '<i class="fa-solid fa-triangle-exclamation"></i>';
                break;
            case 'info':
                icon = '<i class="fa-solid fa-circle-info"></i>';
                break;
        }

        return '<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + this.innerHTML + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }

    timeout() {
        clearInterval(timer);
    }
});