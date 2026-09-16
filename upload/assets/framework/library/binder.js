export default class Binder {
    instance;

    constructor() {
        this.data = new Map();
    }

    get(key) {
        this.data.get(key);
    }

    set(key, value) {
        this.data.set(key, value);
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