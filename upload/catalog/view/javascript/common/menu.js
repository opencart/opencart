import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('common/menu');

// Storage
const categories = await loader.storage('catalog/category');

export default class extends Controller {
    async render() {
        let data = {};

        data.categories = categories;

        console.log('menu');
        console.log(categories);

        return loader.template('common/menu', { ...data,  ...language });
    }
}