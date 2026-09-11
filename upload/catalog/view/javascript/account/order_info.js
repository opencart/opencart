import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Library
const customer = await loader.library('customer');

// Name
export const name = 'order-info';

customElements.define('order-info', class extends WebComponent {
    async connect() {

    }
});