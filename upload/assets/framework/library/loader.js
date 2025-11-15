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

        let response = import('./' + key + '.js');

        let object = await response.then((object) => {
            return object.default;
        });

        if (object.onload !== undefined) {
            await object.onload(this.registry);
        }

        if (object.factory !== undefined) {
            let test = object.factory(this.registry);

            this.registry.set(key, test);

            return test;
        }

        return null;
    }
}