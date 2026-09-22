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
        this._listeners = new Set();
        this._onChange = typeof options.onChange === 'function' ? options.onChange : null;
        this._dirty = new Set();
        this._scheduled = false;
        this._batchDepth = 0;

        this._data = new Proxy({ ...initial }, {
            set: (target, key, value) => {
                if (target[key] === value) return true;

                const oldValue = target[key];

                target[key] = value;

                this._markDirty(key, value, oldValue);

                return true;
            },
            deleteProperty: (target, key) => {
                if (!(key in target)) return true;

                const oldValue = target[key];

                delete target[key];

                this._markDirty(key, undefined, oldValue);

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
                return target._data[prop];
            },
            set: (target, prop, value) => {
                if (prop in target) {
                    target[prop] = value;
                    return true;
                }
                target._data[prop] = value; // routes through the proxy trap above
                return true;
            },
            has: (target, prop) => prop in target._data || prop in target,
            deleteProperty: (target, prop) => {
                if (prop in target._data) {
                    delete target._data[prop];
                    return true;
                }
                delete target[prop];
                return true;
            },
        });
    }

    _markDirty(key, newValue, oldValue) {
        this._dirty.add(key);
        if (this._batchDepth > 0) return;
        this._schedule();
    }

    _schedule() {
        if (this._scheduled) return;
        this._scheduled = true;
        queueMicrotask(() => {
            this._scheduled = false;
            if (!this._dirty.size) return;
            const changedKeys = [...this._dirty];
            this._dirty.clear();
            const snapshot = this.toObject();
            this._listeners.forEach((fn) => fn(changedKeys, snapshot));
            if (this._onChange) this._onChange(changedKeys, snapshot);
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
        this._listeners.add(fn);
        return () => this._listeners.delete(fn);
    }

    /** Explicit get/set, equivalent to `state.key` / `state.key = value`. */
    get(key) {
        return this._data[key];
    }

    set(key, value) {
        this._data[key] = value;
    }

    /** Merge multiple values in as a single batched update. */
    assign(partial) {
        this.batch((s) => {
            Object.entries(partial).forEach(([k, v]) => {
                s._data[k] = v;
            });
        });
    }

    /** Plain-object snapshot of current state. */
    toObject() {
        return { ...this._data };
    }

    /** Remove all subscribers (does not clear state values). */
    destroy() {
        this._listeners.clear();
        this._onChange = null;
    }
}