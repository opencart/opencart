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
	constructor(states, fn) {
        console.log(states);

		this.fn = fn;
		this.data = new Map(Object.entries(states));
        this.listeners = new Map();
	}

    /**
     * Register one or more global listeners, callable from any component's
     * template via `:event="name"`.
     * @param {string|object} name_or_map - a listener name, or `{ name: fn, ... }`
     * @param {Function} [fn] - the function, when `name_or_map` is a string
     */
    addListener(key, fn) {
        if (key == null) return;

        if (!this.listeners.has(key)) {
            this.listeners.set(key, []);
        }

        this.listeners.get(key).push(fn);
    }

    deleteListener(key) {
        this.listeners.delete(key);
    }

    getListener(key) {
        return this.listeners.get(key);
    }

    hasListener(key) {

    }

	get(key) {
		this.data.get(key);
	}

	set(key, value) {
		this.data.set(key, value);

		this.fn(key, value);
	}

	has(key) {
		this.data.has(key);
	}

	delete(key) {
		this.data.delete(key);

		this.callback([key]);
	}

    destroy() {
        this.listeners = [];
        this.onChange = null;
    }
}

/*
export class State {

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


    batch(fn) {
        this.batchDepth++;
        try {
            fn(this);
        } finally {
            this.batchDepth--;

            if (this.batchDepth === 0 && this.dirty.size) {
                this.schedule();
            }
        }
    }


    subscribe(fn) {
        this.listeners.add(fn);

        return () => this.listeners.delete(fn);
    }


    get(key) {
        return this.data[key];
    }

    set(key, value) {
        this.data[key] = value;
    }


    assign(partial) {
        this.batch((s) => {
            Object.entries(partial).forEach(([key, value]) => {
                s.data[key] = value;
            });
        });
    }


    toObject() {
        return { ...this.data };
    }

    destroy() {
        this.listeners.clear();
        this.onChange = null;
    }
}
*/
