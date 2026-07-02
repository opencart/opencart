import { Controller } from '../component.js';
import { loader } from '../index.js';

// Library
const session = await loader.library('session');

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/contact');

// Storage
const locations = await loader.storage('information/location');

export default class extends Controller {
    async render() {
        let data = {};

        // Store Details
        data.store = config.config_name;
        data.image = config.config_image;
        data.address = config.config_address;
        data.telephone = config.config_telephone;

        // Location
        data.open = config.config_open;
        data.map = config.config_map;
        data.comment = config.config_comment;

        if (session.has('customer')) {
            let customer = session.get('customer');

            data.name = customer.get('firstname') + ' ' + customer.get('lastname');
            data.email = customer.get('email');
        } else {
            data.name = '';
            data.email = '';
        }

        data.locations = locations;

        return loader.template('information/contact', { ...data, ...language, ...config });
    }

    onSubmit(e) {
        e.preventDefault();

    }
};