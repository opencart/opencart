class Session {
    get(key) {
        return JSON.parse(sessionStorage.getItem(key));
    }

    set(key, value) {
        sessionStorage.setItem(key, JSON.stringify(value));
    }

    has(key) {
        return sessionStorage.getItem(key) !== null;
    }

    remove(key) {
        sessionStorage.removeItem(key);
    }

    clear() {
        sessionStorage.clear();
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Session();
        }

        return this.instance;
    }
}

const session = Session.getInstance();

export { session };