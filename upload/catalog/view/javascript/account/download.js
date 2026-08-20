import { Controller } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('account/download');

export default class extends Controller {
    render() {
        let data = {};

        data.downloads = {};

        return loader.template('account/download', { ...data, ...language });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }
};