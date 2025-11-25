export class Local {
    get(key) {
        return localStorage.getItem(key);
    }

    set(key, value) {
        localStorage.setItem(key, value);
    }

    has(key) {
        return localStorage.getItem(key) !== null;
    }

    remove(key) {
        localStorage.removeItem(key);
    }

    clear() {
        localStorage.clear();
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Local();
        }

        return this.instance;
    }
}

const local = Local.getInstance();

export { local };