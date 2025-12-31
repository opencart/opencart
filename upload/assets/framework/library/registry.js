class Registry {
    static instance = null;
    data = new Map();

    get(key) {
        return this.data.has(key) ? this.data.get(key) : null;
    }

    set(key, value) {
        this.data.set(key, value);
    }

    has(key) {
        return this.data.has(key);
    }

    remove(key) {
        if (this.data.has(key)) this.data.delete(key);
    }

    all() {
        return this.data;
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Registry();
        }

        return this.instance;
    }
}

const registry = Registry.getInstance();

export { registry };