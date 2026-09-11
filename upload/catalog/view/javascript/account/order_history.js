import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Library
const customer = await loader.library('customer');

customElements.define('order-history', class extends WebComponent {
    render(){

    }
});