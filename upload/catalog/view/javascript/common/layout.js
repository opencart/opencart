import { WebComponent } from '../index.js';
import { loader, currency, tax, template, weight, length } from '../index.js';
import '../component.js';
import './header.js';
import './footer.js';

const config = await loader.config('default');



export default class CommonLayout extends WebComponent {
    async render() {
        return loader.template('common/layout');
    }
}