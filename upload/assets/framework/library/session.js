export default class Session {
    static instance;

    async get(key) {
        return await JSON.parse(sessionStorage.getItem(key));
    }

    set(key, value) {
        sessionStorage.setItem(key, JSON.stringify(value));
    }

    has(key) {
        return sessionStorage.getItem(key) !== null;
    }

    delete(key) {
        sessionStorage.removeItem(key);
    }

    clear() {
        sessionStorage.clear();
    }

    static getInstance() {
        if (!Session.instance) {
            Session.instance = new Session();
        }

        return Session.instance;
    }
}

const session = Session.getInstance();

export { session };