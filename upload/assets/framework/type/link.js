import { action } from '../index.js';

action.register('link', class {
    element;
    target = '';

    constructor(element) {
        element.addEventListener('click', this.onClick.bind(this));

        this.target = element.getAttribute('data-target');
    }

    onClick(e) {
        e.preventDefault();

        console.log(this.target);

        let target = document.getElementById(this.target);

        target.src = e.currentTarget.getAttribute('href');
    }
});