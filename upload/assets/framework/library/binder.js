export default class Binder {
    instance;
    data = new Map();

    get(key) {
        this.data.get(key);
    }

    set(key, value) {
        this.data.set(key, value);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Map();
        }

        return this.instance;
    }
}

const binder = new Binder();

export { binder };