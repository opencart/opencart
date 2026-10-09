import { WebComponent } from '../../engine.js';
/**
 * <cms-toast>
 *
 * A notification area. The shell binds it as a global ref (`:ref="toast"`),
 * so any component can show a message without knowing where it lives:
 *
 *   Global.get('toast').show('Page saved.');
 *   Global.get('toast').show('Something went wrong.', 'error');
 */
customElements.define('ui-toast', class extends WebComponent {
    async render() {
        return;
    }

    show(message, type = 'success') {
        if (!this.list) return;

        let toast = document.createElement('div');

        toast.className = 'toast toast-' + type;
        toast.textContent = message;

        this.list.append(toast);

        setTimeout(() => toast.remove(), 3500);
    }
});