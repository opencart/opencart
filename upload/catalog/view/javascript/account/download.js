import { Controller } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('account/download');

export default class extends Controller {
    render() {
        let data = {};

        data.downloads = {};

        return loader.template('account/download', { ...data, ...language });
    }
};