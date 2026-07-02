import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('catalog/special');

class extends Controller {
    async connected() {

    }

    render() {
        return loader.template('catalog/special', { ...language });
    }
}