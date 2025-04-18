export default class Loader {
    registry = {};

    /**
     * Constructor
     */
    constructor(registry) {
       this.registry = registry;
    }

    async data(path) {
        let response = await fetch('./catalog/view/data/' + path + '.json');

        if (response.status == 200) {
            this.registry.data[path] = response.json();

            return this.registry.data[path];
        } else {
            return [];
        }
    }

    template(path, data) {
        return this.registry.get('template').render(path, data);
    }

    language(path) {
        this.registry.get('language').load(path);
    }

    config(path) {
        this.registry.get('config').load(path);
    }
}
