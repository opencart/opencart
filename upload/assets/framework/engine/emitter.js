export { Signal } from './signal.js';
import { Button } from '../type/button.js';
import { Form } from '../type/form.js';
import { Link } from '../type/link.js';

export default class Emitter {
    instance;

    constructor() {
        this.signals = new Map();
    }

    // Get or create a named signal
    signal(name) {
        if (!this.signals.has(name)) {
            this.signals.set(name, new Signal());
        }

        return this.signals.get(name);
    }

    // Convenience method to emit directly
    emit(name, ...args) {
        if (this.signals.has(name)) {
            this.signals.get(name).emit(...args);
        }
    }

    // Convenience method to subscribe directly
    on(name, fn) {
        return this.signal(name).connect(fn);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Emitter();
        }

        return this.instance;
    }
}

const emitter = Emitter.getInstance();

export { emitter };

/*
let button = emitter.signal('button-login');

button.connect((element) => {
     new Button(element);
});

button.emit(...args) {
    this.listeners.forEach(fn => fn(...args));
}
*/