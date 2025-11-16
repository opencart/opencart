export default class Loader {
    registry = null;
    data = [];

    constructor(registry) {
        this.registry = registry;
    }

    storage(path) {
        return this.registry.get('storage').fetch(path);
    }

    language(path) {
        return this.registry.get('language').fetch(path);
    }

    template(path, data) {
        return this.registry.get('template').render(path, data);
    }

    async library(key) {
        if (this.registry.has(key)) {
            return this.registry.get(key);
        }

        let response = await import('./' + key + '.js');

        if (response.getInstance !== undefined) {
            let object = await response.getInstance(this.registry);

            this.registry.set(key, object);

            return object;
        } else {
            console.log('Error: No factory method to return class');
        }

        return null;
    }
}