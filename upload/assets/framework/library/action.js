export default class Action {
    data = new Map();

    register(key, value) {
        this.data.set(key, value);
    }

    load(path) {

    }

    get(key) {
        return this.data.get(key);
    }

    attach(key, element) {

    }

    detach(key, element) {

    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Action();
        }

        return this.instance;
    }
}

const action = new Action();

export { action };