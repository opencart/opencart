import { WebComponent } from '../../engine.js';

function escapeHtml(value) {
    return String(value).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

/**
 * <ui-modal heading="Sign in"> ...anything... </ui-modal>
 *
 * A modal dialog built on the native <dialog> element, so the browser provides
 * the backdrop, the focus trap, the Escape key and making the page behind it
 * inert. Whatever is inside the tag is shown in the body.
 *
 *   modal.show()    opens it (it can be called straight after the modal is on the page)
 *   modal.close()   closes it
 *   modal.open      true while it is open
 *   'close' event   fired on the element when it closes, however it was closed
 *
 * Attributes: heading (title bar text), label (accessible name when there is
 * no heading, default "Dialog"), close-label (default "Close").
 *
 * To show a page component in a modal, the way the content area does:
 *
 *   customElements.get('ui-modal').open('account/register.js');
 *
 * That makes a modal around a <nav-include>, opens it, and throws it away when
 * it closes, so it starts fresh every time.
 */
customElements.define('ui-modal', class extends WebComponent {
    static open(src, heading = '') {
        let modal = document.createElement('ui-modal');

        if (heading) modal.setAttribute('heading', heading);

        let include = document.createElement('nav-include');

        include.setAttribute('src', src);

        modal.append(include);

        // Made for a single use: gone once it is closed.
        modal.addEventListener('close', () => modal.remove());

        document.body.append(modal);

        modal.show();

        return modal;
    }

    constructor() {
        super();

        // Settles after the first render, which is when the <dialog> exists.
        this.ready = new Promise(resolve => {
            this.resolve_ready = resolve;
        });
    }

    get open() {
        return this.dialog ? this.dialog.open : false;
    }

    render() {
        let heading = this.getAttribute('heading');

        // aria-labelledby points at the title; without one the dialog needs a name of its own.
        let name = heading ? 'aria-labelledby="heading"' : `aria-label="${escapeHtml(this.getAttribute('label') ?? 'Dialog')}"`;

        return `
            <style>
                :host { display: contents; }
                dialog { width: min(40rem, calc(100vw - 2rem)); max-height: calc(100vh - 2rem); padding: 0; border: 0; border-radius: .5rem; overflow: auto; color: inherit; background: var(--bs-body-bg, Canvas); box-shadow: 0 1rem 3rem rgb(0 0 0 / .3); }
                dialog::backdrop { background: rgb(0 0 0 / .5); }
                .header { display: flex; align-items: flex-start; gap: 1rem; padding: .5rem .75rem 0 1rem; }
                .title { flex: 1; margin: .5rem 0 0; font-size: 1.25rem; }
                .close { margin-left: auto; padding: .25rem .6rem; border: 0; background: none; color: inherit; font-size: 1.75rem; line-height: 1; cursor: pointer; opacity: .6; }
                .close:hover, .close:focus-visible { opacity: 1; }
                .body { padding: 0 1rem 1rem; }
            </style>
            <dialog @ref="dialog" @click="onClick" @close="onClose" closedby="any" ${name}>
                <div class="header">
                    ${heading ? `<h2 class="title" id="heading">${escapeHtml(heading)}</h2>` : ''}
                    <button type="button" class="close" aria-label="${escapeHtml(this.getAttribute('close-label') ?? 'Close')}" @click="close">&times;</button>
                </div>
                <div class="body"><slot></slot></div>
            </dialog>
        `;
    }

    async update() {
        await super.update();

        this.resolve_ready();
    }

    async show() {
        await this.ready;

        if (!this.dialog.open) this.dialog.showModal();
    }

    close() {
        if (this.dialog?.open) this.dialog.close();
    }

    // The content fills the dialog box, so a click that lands on the <dialog> itself
    // can only be on the backdrop. Browsers that support closedby="any" do this already.
    handleClick(e) {
        if (e.target === this.dialog) this.close();
    }

    // The native close event stays inside the shadow root, so send it on.
    handleClose() {
        this.dispatchEvent(new Event('close'));
    }
});