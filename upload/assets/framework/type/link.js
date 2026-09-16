export class Link {
    element;
    target = '';

    constructor(element) {
        element.addEventListener('click', this.onClick.bind(this));

        this.target = element.getAttribute('data-target');
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById(this.target);

        target.src = e.currentTarget.getAttribute('href');
    }
}