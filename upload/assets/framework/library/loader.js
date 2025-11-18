export class Loader {
    static #instance = null;
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
        console.log(key);
        console.log(this.registry);


        //if (this.registry.has(key)) {
         //   return this.registry.get(key);
       // }

        let response = await import('./' + key + '.js');

        console.log(response);



        if (response.getInstance !== undefined) {
            let object = await response.getInstance(this.registry);


            this.registry.set(key, object);

            return object;
        } else {
            console.log('Error: No factory method to return class');
        }

        return null;
    }

    static getInstance() {
        if (!this.#instance) {
            this.#instance = new Loader('dfdf');
        }

        return this.#instance;
    }
}

const loader = Loader.getInstance();

export { loader };