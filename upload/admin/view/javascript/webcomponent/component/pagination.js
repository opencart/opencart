import { WebComponent } from './../webcomponent.js';

class XPagination extends WebComponent {
    data = {
        first: '',
        prev: '',
        links: [],
        next: '',
        last: '',
    };

    event = {
        connected: async () => {
            this.data.content = this.innerHTML;

            this.shadow.innerHTML = await this.render('pagination.html', this.data);
        }
    };
}

customElements.define('x-pagination', XPagination);

