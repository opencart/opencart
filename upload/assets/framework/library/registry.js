class Registry {
    static instance = null;

    static getInstance() {
        if (!this.instance) {
            this.instance = new Registry();
        }

        return this.instance;
    }
}

const registry = Registry.getInstance();

export { registry };