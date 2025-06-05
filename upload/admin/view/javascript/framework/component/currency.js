import { WebComponent } from './../webcomponent.js';

class XCurrency extends WebComponent {
    data = {
        title: '',
        content: ''
    };

    event = {
        connected: async () => {
            // Add the data attributes to the data object
            //this.data.id = this.getAttribute('data-id');
            this.data.heading_title = this.getAttribute('data-title');

            this.data.content = this.innerHTML;

            this.shadow.innerHTML = await this.render('modal.html', this.data);

            document.body.classList.add('modal-open');
        }
    };
}

customElements.define('x-currency', XCurrency);

