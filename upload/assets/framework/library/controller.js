export class Controller {
    element = HTMLElement;
    bind = new Map();

    constructor(element) {
        this.element = element;
    }

    async execute() {
        let template = document.createElement('template');

        template.innerHTML = await this.render();

        let clone = template.content.cloneNode(true);

        // Autoload any custom elements not already loaded
        clone.querySelectorAll('[data-bind], [data-on]').forEach(element => {
            // Attach Events based on elements that have data-bind attributes
            if (element.hasAttribute('data-bind')) {
                this.bind.set(element.getAttribute('data-bind'), element);

                element.removeAttribute('data-bind');
            }

            // Attach events based on elements that have data-on attributes
            if (element.getAttribute('data-on')) {
                let [event, method] = element.getAttribute('data-on').split(':');

                if (method in this) {
                    element.addEventListener(event, this[method].bind(this));
                }

                element.removeAttribute('data-on');
            }
        });

        return clone;
    }
}