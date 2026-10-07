import { WebComponent } from '../../engine.js';

customElements.define('ui-alert', class extends WebComponent {
    static observed = ['type'];
    timer = null;

    render() {
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        this.timer = setInterval(this.timeout, 500);

        let type = (this.getAttribute('type') || 'primary').replace(/[^a-z-]/gi, '');

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

        return '<div class="alert alert-' + type + '">' + icon + ' ' + this.innerHTML + '</div>';
    }

    onConnect() {
        let timeout = parseInt(this.getAttribute('timeout'));

        if (timeout > 0) {
            this.timer = setTimeout(() => this.remove(), timeout);
        }
    }

    onDisconnect() {
        clearTimeout(this.timer);
    }
});