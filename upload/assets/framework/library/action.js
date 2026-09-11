import { loader } from '../index.js';

export let action = new Map(Object.entries({
    form: (element) => {
        return new Form(element);
    },
    button: (element) => {
        return new Button(element);
    },
    link: (element) => {
        return new Link(element);
    },
    template: (element) => {
        return new Template(element);
    }
}));

let test = {
    name: 'common-header',
    register: () => {
        customElements.define(name, this);
    },
    constructor: () => {

    },
    render: () => {

    },
    onClick: () => {

    }
}

