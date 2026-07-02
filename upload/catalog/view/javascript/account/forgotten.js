import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/edit');

export default class extends Controller {
    render() {

        return loader.template('account/forgotten', { ...language });
    }

    confirm(e) {
        e.preventDefault();

    }
});