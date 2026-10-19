/**
 * State
 * --------------
 * A small reactive state container built on a Proxy. Reading/writing
 * properties works like a plain object, but writes are tracked, batched
 * into a single microtask, and broadcast to subscribers with the list of
 * keys that changed.
 *
 * Designed to be dropped into BaseComponent as `this.state` (see the
 * integration in base-component.js), but has no dependency on it and
 * can be used standalone.
 *
 * Usage:
 *
 *   const state = new ComponentState({ count: 0, label: 'hi' });
 *
 *   state.subscribe((changedKeys, data) => {
 *     console.log('changed:', changedKeys, data);
 *   });
 *
 *   state.count = 1;              // triggers one notification (async)
 *
 *   state.batch(() => {
 *     state.count = 2;
 *     state.label = 'bye';
 *   });                           // triggers exactly one notification
 *
 * Notifications are delivered via `queueMicrotask`, so multiple
 * synchronous writes in the same tick collapse into a single update.
 */
export class State {
    #data;
    #listeners = new Set();
    #onchange;
    #changed = new Set();
    #scheduled = false;
    #depth = 0;

    constructor(states, onchange) {
        this.#data = new Map(Object.entries(states));
        this.#onchange = onchange;

        Object.freeze(this);
    }

    get(key) {
        return this.#data.get(key);
    }

    /**
     * set('count', 1)                    writes one value
     * set({ count: 1, label: 'bye' })    writes several, as one change
     * (An object cannot be used as a key.)
     */
    set(key, value) {
        if (typeof key === 'object') {
            if (value !== undefined) {
                throw new TypeError('State: set(object) takes only the object');
            }

            this.#batch(() => {
                Object.entries(key).forEach(([k, v]) => this.set(k, v));
            });

            return;
        }

        // Check to see if the value is different. 
        if (this.#data.has(key) && Object.is(this.#data.get(key), value)) return;

        this.#data.set(key, value);

        // Mark as changed
        this.#mark(key);
    }

    all() {
        return Object.fromEntries(this.#data);
    }

    delete(key) {
        if (!this.#data.has(key)) return false;

        this.#data.delete(key);

        this.#mark(key);

        return true;
    }

    #mark(key) {
        this.#changed.add(key);

        if (this.#depth > 0) return;

        this.#schedule();
    }

    #schedule() {
        if (this.#scheduled) return;

        this.#scheduled = true;

        queueMicrotask(() => {
            this.#scheduled = false;

            if (!this.#changed.size) return;

            const changed = [...this.#changed];

            this.#changed.clear();

            const snapshot = this.all();

            this.#listeners.forEach(fn => fn(changed, snapshot));

            if (this.#onchange) this.#onchange(changed, snapshot);
        });
    }

    /** Group multiple writes into a single notification. */
    #batch(fn) {
        this.#depth++;

        try {
            fn(this);
        } finally {
            this.#depth--;

            if (this.#depth === 0 && this.#changed.size) {
                this.#schedule();
            }
        }
    }

    /** Subscribe to state changes. Returns an unsubscribe function. */
    subscribe(handle) {
        this.#listeners.add(handle);

        return () => this.#listeners.delete(handle);
    }

    destroy() {
        this.#listeners.clear();
        this.#changed.clear();
    }
}