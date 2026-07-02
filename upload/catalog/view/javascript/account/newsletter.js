import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/newsletter');

// Library
const session = await loader.library('session');

export default class extends Controller {
    async render() {
        let data = {};

        //let customer = session.get('customer');

        //data.newsletter = customer.get('newsletter');

        return await loader.template('account/newsletter', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
});