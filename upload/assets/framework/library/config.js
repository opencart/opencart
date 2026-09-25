export default class Config {
    static instance = null;

    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();
    }

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path.set(namespace, path);
        }
    }

    async fetch(path) {
        if (this.cache.has(path)) {
            return this.cache.get(path);
        }

        let file = this.directory + path + '.json';
        let namespace = '';
        let parts = path.split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path.has(namespace)) {
                file = this.path.get(namespace) + path.substr(namespace.length) + '.json';
            }
        }

        let response = await fetch(file);

        if (response.status == 200) {
            let data = await response.json();

            this.cache.set(path, new Map(Object.entries(data)));

            return this.cache.get(path);
        } else {
            console.log('Could not load config file ' + path);
        }

        return undefined;
    }

    static getInstance() {
        if (!Config.instance) {
            Config.instance = new Config();
        }

        return Config.instance;
    }
}

const config = Config.getInstance();

export { config };

// Base
const base = new URL(document.querySelector('base').href);

// Testing Code
config.cache.set('default', new Map(Object.entries({
    config_path: base + 'catalog/view/javascript/',
    config_logo: 'catalog/opencart-logo.png',
    config_url: 'http://localhost/opencart-master/upload/',

    config_name: 'OpenCart Store',
    config_owner: '',
    config_address: '44 Abc Road,' + "\n" + 'TX',
    config_email: 'test@test.com',
    config_telephone: '01234 567890',

    config_image: '',
    config_open: '',
    config_comment: '',
    config_location_list: [],

    config_country_id: 222,
    config_zone_id: 3563,
    config_timezone: 'UTC',
    config_language: 'en-gb',
    config_currency: 'EUR',

    config_length_class_id: 1,
    config_weight_class_id: 1,

    config_product_description_length: 100,

    config_customer_group_id: 1,

    config_product_count: true,
    config_review_status: true,
    config_tax: true,
    config_account_id: 1,
    config_checkout_guest: true,
    config_checkout_payment_address: true,
    config_gdpr_id: 0,
    config_stock_status_id: 4,
    config_affiliate_status: 1,
    config_file_max_size: 3000
})));