import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('common/search');

// URL
const url = new URLSearchParams(document.location.search);

export default class extends Controller {
    render() {
        let data = {};

        if (url.has('search')) {
            data.search = url.get('search');
        } else {
            data.search = '';
        }

        return loader.template('common/search', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
};