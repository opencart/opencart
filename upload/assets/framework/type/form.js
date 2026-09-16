import { binder } from '../index.js';

export class Form {
    element;

    constructor(element) {
        this.element = element;
        this.element.addEventListener('submit', this.onSubmit.bind(this));
    }

    onSubmit(e) {


        e.preventDefault();

        if (binder.has('button-submitter')) {
            //binder.get('button-submitter').button('loading');
        }
    }
}