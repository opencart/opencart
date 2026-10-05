/**
 * ComponentState
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
    /**
     * @param {object} initial - initial state values
     * @param {object} [options]
     * @param {(changedKeys: string[], data: object) => void} [options.onChange]
     *        single callback fired on every batched update (e.g. a host's
     *        re-render trigger). Additional listeners can be added via
     *        `subscribe()`.
     */
    constructor(initial = {}, options = {}) {
        this.listeners = new Set();
        this.onChange = typeof options.onChange === 'function' ? options.onChange : null;
        this.dirty = new Set();
        this.scheduled = false;
        this.batchDepth = 0;

        this.data = new Proxy({ ...initial }, {
            set: (target, key, value) => {
                if (target[key] === value) return true;

                const value_old = target[key];

                target[key] = value;

                this.markDirty(key, value, value_old);

                return true;
            },
            deleteProperty: (target, key) => {
                if (!key in target) return true;

                const value_old = target[key];

                delete target[key];

                this.markDirty(key, undefined, value_old);

                return true;
            }
        });

        // Wrap `this` so `state.foo` / `state.foo = x` read/write `_data`
        // directly, while methods defined below (subscribe, batch, get, set,
        // toObject) still resolve to the class's own implementations.
        return new Proxy(this, {
            get: (target, prop, receiver) => {
                if (prop in target || typeof prop === 'symbol') {
                    return Reflect.get(target, prop, receiver);
                }

                return target.data[prop];
            },
            set: (target, prop, value) => {
                if (prop in target) {
                    target[prop] = value;

                    return true;
                }

                target.data[prop] = value; // routes through the proxy trap above

                return true;
            },
            has: (target, prop) => prop in target.data || prop in target,
            deleteProperty: (target, prop) => {
                if (prop in target.data) {
                    delete target.data[prop];

                    return true;
                }

                delete target[prop];

                return true;
            },
        });
    }

    markDirty(key, value_new, value_old) {
        this.dirty.add(key);

        if (this.batchDepth > 0) return;

        this.schedule();
    }

    schedule() {
        if (this.scheduled) return;

        this.scheduled = true;

        queueMicrotask(() => {
            this.scheduled = false;

            if (!this._dirty.size) return;

            const changedKeys = [...this.dirty];

            this.dirty.clear();

            const snapshot = this.toObject();

            this.listeners.forEach((fn) => fn(changedKeys, snapshot));

            if (this.onChange) this.onChange(changedKeys, snapshot);
        });
    }

    /** Group multiple writes into a single notification. */
    batch(fn) {
        this._batchDepth++;
        try {
            fn(this);
        } finally {
            this._batchDepth--;
            if (this._batchDepth === 0 && this._dirty.size) {
                this._schedule();
            }
        }
    }

    /** Subscribe to state changes. Returns an unsubscribe function. */
    subscribe(fn) {
        this.listeners.add(fn);

        return () => this.listeners.delete(fn);
    }

    /** Explicit get/set, equivalent to `state.key` / `state.key = value`. */
    get(key) {
        return this.data[key];
    }

    set(key, value) {
        this._data[key] = value;
    }

    /** Merge multiple values in as a single batched update. */
    assign(partial) {
        this.batch((s) => {
            Object.entries(partial).forEach(([key, value]) => {
                s.data[key] = value;
            });
        });
    }

    /** Plain-object snapshot of current state. */
    toObject() {
        return { ...this.data };
    }

    /** Remove all subscribers (does not clear state values). */
    destroy() {
        this.listeners.clear();
        this.onChange = null;
    }
}