import { action, binder } from '../index.js';

action.register('form', class Form {
    element;

    constructor(element) {
        this.element = element;
        this.element.addEventListener('submit', this.onSubmit);
    }

    onSubmit(e) {
        e.preventDefault();

        binder.get('button').button('loading');
    }
});