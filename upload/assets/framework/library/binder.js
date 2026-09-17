export default class Binder {
    instance;

    constructor() {
        this.data = new Map();
    }

    get(key) {
        return this.data.get(key);
    }

    set(key, value) {
        this.data.set(key, value);
    }

    has(key) {
        return this.data.has(key);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Binder();
        }

        return this.instance;
    }
}

const binder = new Binder();

export { binder };