/**
 * Global
 * --------------
 * A small static registry shared across every Binder instance (and
 * therefore every component). Holds:
 *
 *   - refs      bound anywhere with `:ref="name"`
 *   - listeners registered for use with `:event="name"`
 *
 * Used internally by Binder, but kept as its own class so it can be
 * registered into, read from, or reset independently of any single
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
export class Global {
    static refs = new Map();
    static listeners = new Map();

    /**
     * Register one or more global listeners, callable from any component's
     * template via `:event="name"`.
     * @param {string|object} name_or_map - a listener name, or `{ name: fn, ... }`
     * @param {Function} [fn] - the function, when `name_or_map` is a string
     */
    static addListener(key, fn) {
        if (key == null) return;

        this.listeners.set(key, fn);
    }

    static deleteListener(key) {
        this.listeners.delete(key);
    }

    static getListener(key) {
        return this.listeners.get(key);
    }

    /** Read a ref bound anywhere with `:ref="name"`. */
    static get(key) {
        return this.refs.get(key);
    }

    static set(key, element) {
        this.refs.set(key, element);
    }

    /** Clears a ref only if it still points at `el` — avoids one binder's
     *  teardown clobbering a ref another binder has since re-registered. */
    static delete(key, element) {
        if (this.refs.get(key) === element) this.refs.delete(key);
    }

    /** Clears all refs and listeners. Mainly useful for tests/hot-reload. */
    static reset() {
        this.refs = new Map();
        this.listeners = {};
    }
}