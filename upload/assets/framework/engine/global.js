/**
 * Global
 * --------------
 * A small static registry shared across every ElementBinder instance
 * (and therefore every component). Holds:
 *
 *   - refs      bound anywhere with `:ref="name"`
 *   - listeners registered for use with `:event="name"`
 *
 * Used internally by ElementBinder, but kept as its own class so it can
 * be registered into, read from, or reset independently of any single
 * component or binder instance — e.g. registering app-wide listeners
 * at startup, before any component has even loaded.
 *
 * Usage:
 *
 *   GlobalBindings.registerListener('trackClick', (event) => {
 *     analytics.log('click', event.target.id);
 *   });
 *   // or register several at once:
 *   GlobalBindings.registerListener({ trackClick, openModal });
 *   GlobalBindings.unregisterListener('trackClick');
 *
 *   GlobalBindings.getRef('appHeader'); // → element bound with :ref="appHeader"
 */
class Global {
    constructor() {
        this.refs = new Map();
        this.listeners = new Map();
    }

    setRef(name, element) {
        this.refs.set(name, element);
    }

    /** Read a ref bound anywhere with `:ref="name"`. */
    getRef(name) {
        return this.refs.get(name);
    }

    /** Clears a ref only if it still points at `el` — avoids one binder's
     *  teardown clobbering a ref another binder has since re-registered. */
    clearRef(name, element) {
        if (this.refs.get(name) === element) this.refs.delete(name);
    }

    /**
     * Register one or more global listeners, callable from any component's
     * template via `:event="name"`.
     * @param {string|object} name - a listener name, or `{ name: fn, ... }`
     * @param {Function} [listener] - the function, when `name` is a string
     */
    registerListener(name, listener) {
        if (!name) return;

        if (typeof name === 'object') {
            Object.assign(this.listeners, name);
        } else {
            this.listeners.set(name, listener);
        }
    }

    unregisterListener(name) {
        this.listeners.delete(name);
    }

    getListener(name) {
        return this.listeners.get(name);
    }

    /** Clears all refs and listeners. Mainly useful for tests/hot-reload. */
    reset() {
        this.refs = new Map();
        this.listeners = new Map();
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Global();
        }

        return this.instance;
    }
}

const global = Global.getInstance();

export { global };