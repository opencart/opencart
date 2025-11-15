export default class Cart {
    path = '';
    data = [];

    constructor(registry) {
        this.storage = registry.get('storage');
        this.language = registry.get('language');
        this.tax = registry.get('tax');

        //$this->config = $registry->get('config');
        //$this->customer = $registry->get('customer');
        //$this->session = $registry->get('session');
        //$this->tax = $registry->get('tax');
        //$this->weight = $registry->get('weight');

        let response = this.storage.fetch('localisation/currency');

        response.then(this.onloaded);
    }

    add = () => {

    }

    remove = () => {

    }

}