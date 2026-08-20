import { Controller } from '../component.js';
import { loader } from '../index.js';

// Library
const session = loader.library('session');

// Language
const language = await loader.language('account/password');

export default class extends Controller {
    render() {
        let data = {};

        return loader.template('account/password', { ...data, ...language });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }

    onSubmit(e) {
        e.preventDefault();

    }
}