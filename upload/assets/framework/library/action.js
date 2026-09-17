export { loader } from '../library/loader.js';
import { Button } from '../type/button.js';
import { Form } from '../type/form.js';
import { Link } from '../type/link.js';

export default class Action {
    instance;

    constructor() {
        this.data = new Map();
    }

    register(key, value) {
        this.data.set(key, value);
    }

    has(key) {
        return this.data.has(key);
    }

    get(key) {
        return this.data.get(key);
    }

    attach(key, element) {
        let name = this.data.get(key);

        let object = new name(element);
    }

    detach(key, element) {

    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Action();
        }

        return this.instance;
    }
}

const action = Action.getInstance();

// Load defaults
action.register('button', Button);
action.register('form', Form);
action.register('link', Link);

export { action };

