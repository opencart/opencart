export class Controller {
    element = HTMLElement;
    data = new Map();

    constructor(element) {
        this.element = element;
    }

    bind(name) {
        return this.data.get(name);
    }

    async execute() {
        let template = document.createElement('template');

        template.innerHTML = await this.render();

        let clone = template.content.cloneNode(true);

        // Autoload any custom elements not already loaded
        clone.querySelectorAll('[data-bind], [data-on], [data-type]').forEach(element => {
            // Attach Events based on elements that have data-bind attributes
            if (element.hasAttribute('data-bind')) {
                this.data.set(element.getAttribute('data-bind'), element);

                element.removeAttribute('data-bind');
            }

            // Attach events based on elements that have data-on attributes
            if (element.hasAttribute('data-on')) {
                let [ event, method] = element.getAttribute('data-on').split(':');

                if (method in this) {
                    element.addEventListener(event, this[method].bind(this));
                }

                element.removeAttribute('data-on');
            }


        });

        return clone;
    }
}