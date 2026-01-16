import { WebComponent } from '../component.js';
import { loader } from '../index.js';

class XModal extends WebComponent {
    async connected(){
        let data = {};
        // Add the data attributes to the data object
        //this.data.id = this.getAttribute('data-id');
        data.heading_title = this.getAttribute('data-title');

        this.data.content = this.innerHTML;

        this.shadow.innerHTML = await this.render('modal.html', this.data);

        document.body.classList.add('modal-open');
    }
}

customElements.define('x-modal', XModal);