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
	shadow = false;
    // Settles after the first render, which is when the <dialog> exists.
    constructor() {
        super();
    }

    styles() { 
        return `
            :host {
                display: contents;
            }
            dialog {
                width: min(40rem, calc(100vw - 2rem));
                max-height: calc(100vh - 2rem);
                padding: 0;
                border: 0;
                border-radius: .5rem;
                overflow: auto;
                color: inherit;
                background: var(--bs-body-bg, Canvas);
                box-shadow: 0 1rem 3rem rgb(0 0 0 / .3);
            }
            dialog::backdrop {
                background: rgb(0 0 0 / .5);
             }
            .header {
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                padding: .5rem .75rem 0 1rem;
            }
            .title {
                flex: 1;
                margin: .5rem 0 0;
                font-size: 1.25rem;
            }
            .close {
                margin-left: auto;
                padding: .25rem .6rem;
                border: 0;
                background: none;
                color: inherit;
                font-size: 1.75rem;
                line-height: 1;
                cursor: pointer;
                opacity: .6;
            }
            .close:hover, .close:focus-visible {
                opacity: 1;
            }
            .body {
                padding: 0 1rem 1rem;
            }
        `;
    }

	render() {
        return `
            <dialog @click="onClick" @close="onClose" @keydown.escape="close" @keydown.enter.stop>
                <div class="header">
                    <h5 class="title">${this.heading || ''}</h5>
                    <button type="button" class="close" @click="close">&times;</button>
                </div>
                <div class="body">
                    <slot></slot>
                </div>
            </dialog>
        `;
	
    }

   
    async show() {
        if (!this.modal.open) this.modal.showModal();
    }

    close() {
        if (this.modal?.open) this.modal.close();
    }

    // The content fills the dialog box, so a click that lands on the <dialog> itself
    // can only be on the backdrop. Browsers that support closedby="any" do this already.
    onClick(e) {
        if (!this.modal.open) this.modal.showModal();
        
        if (e.target === this.modal) this.close();
    }

    // The native close event stays inside the shadow root, so send it on.
    async onClose() {
        this.dispatchEvent(new Event('close'));
    }
});