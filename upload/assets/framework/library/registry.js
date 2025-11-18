class Registry {
    static #instance = null;
    data = [];

    get(key) {
        return this.data[key];
    }

    set(key, value) {
        this.data[key] = value;
    }

    has(key) {
        return this.data[key] !== undefined;
    }

    remove(key) {
        delete this.data[key];
    }

    static getInstance() {
        if (!this.#instance) {
            this.#instance = new Registry();
        }

        return this.#instance;
    }
}

const registry = Registry.getInstance();

export { registry };