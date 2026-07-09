import { Controller } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('account/account');

export default class extends Controller {
    render() {
        return loader.template('account/account', language);
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }
};