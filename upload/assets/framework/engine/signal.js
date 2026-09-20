export class Signal {
    constructor() {
        this.listeners = [];
    }

    // Add a function to listen for the signal
    connect(fn) {
        this.listeners.push(fn);
        // Return a disconnect function for easy cleanup
        this.disconnect(fn);
    }

    // Remove a listener function
    disconnect(fn) {
        this.listeners = this.listeners.filter(listener => listener !== fn);
    }

    // Trigger the signal with optional data
    emit(...args) {
        this.listeners.forEach(fn => fn(...args));
    }
}