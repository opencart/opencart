class Session {
    static #instance = null;

    get(key) {
        return sessionStorage.getItem(key);
    }

    set(key, value) {
        sessionStorage.setItem(key, value);
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
        if (!Session.#instance) {
            Session.#instance = new Session();
        }

        return Session.#instance;
    }
}

const session = Session.getInstance();

export { session };