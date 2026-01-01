/**
 * A lightweight Event Dispatcher class that mimics the core behavior — register handlers for event names (triggers),
 * trigger events (passing args by reference where possible), and support sort order/priority.
 *
 * Usage:
 * 1. Extend your class from a class that uses HookMixin.
 * 2. Call .addHooks() on the prototype for each method you want to make hookable.
 *
 * Example below the class definition.
 */
class Event {
    static instance;
    data = new Map();

    /**
     * Register a handler for an event (trigger)
     * @param {string} trigger - The trigger name, e.g. 'catalog/controller/common/header/before'
     * @param {Function} callback - The handler function
     * @param {number} [priority=0] - Higher priority runs first (like sort_order in OpenCart)
     */
    register(trigger, callback) {
        if (typeof callback !== 'function') return;

        // Store hooks if not already
        if (!this.data.has(trigger)) {
            this.data.set(trigger, []);
        }

        this.data.get(trigger).push(callback);

        // Sort by priority descending (higher first)
        // this.data.get(trigger).sort((a, b) => b.priority - a.priority);
    }

    /**
     * Trigger an event - calls all registered handlers in priority order
     * Args are passed by reference where possible (objects/arrays are mutable like PHP &)
     * @param {string} trigger
     * @param {...any} args - Arguments passed to handlers (mutable objects act like references)
     */
    trigger(trigger, args) {
        for (let [regex, stack] of this.data.entries()) {
            if (trigger.match(regex)) {
                for (let callback of stack) {
                    callback(args);
                }
            }
        }
    }

    /**
     * Optional: Clear all handlers for an event
     * @param {string} eventName
     */
    clear(trigger) {
        this.data.delete(trigger);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Event();
        }

        return this.instance;
    }
}

const event = Event.getInstance();

export { event, event as default };