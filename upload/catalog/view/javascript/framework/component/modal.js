import { WebComponent } from './../webcomponent.js';

class XModal extends WebComponent {
    data = {
        heading_title: '',
        content: '',
    };

    event = {
        connected: async () => {
            // Add the data attributes to the data object
            //this.data.id = this.getAttribute('data-id');
            this.data.heading_title = this.getAttribute('data-title');

            this.data.content = this.innerHTML;

            this.addStylesheet('bootstrap.css');
            this.addStylesheet('fontawesome.css');

            this.shadow.innerHTML = await this.render('modal.html', this.data);

            document.body.classList.add('modal-open');
        }
    };
}

customElements.define('x-modal', XModal);