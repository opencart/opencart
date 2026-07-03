export class Controller {
    element = HTMLElement;

    constructor(element) {
        this.element = element;
    }

    async execute() {
        let output = await this.render();

        if (output) {
            this.element.innerHTML = output;

            // Autoload any custom elements not already loaded
            this.element.querySelectorAll('[data-bind], [data-on]').forEach(element => {
                // Attach Events based on elements that have data-bind attributes
                if (element.hasAttribute('data-bind')) {
                    this['$' + element.getAttribute('data-bind')] = element;

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
        }
    }
}