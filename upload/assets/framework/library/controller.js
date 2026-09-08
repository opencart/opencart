import { loader } from '../index.js';
import { binder } from '../index.js';

export class Controller {
    element = HTMLElement;
    data = new Map();

    constructor(element) {
        this.element = element;
    }

    async execute() {
        let template = document.createElement('template');

        template.innerHTML = await this.render();

        let clone = template.content.cloneNode(true);

        // Autoload any custom elements not already loaded
        clone.querySelectorAll('[data-bind], [data-on], [data-action]').forEach(element => {
            // Attach Events based on elements that have data-bind attributes
            if (element.hasAttribute('data-bind')) {
                $binded.set(element.getAttribute('data-bind'), element);

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

            // Attach
            if (element.hasAttribute('data-apply')) {



                if (!'$data' in element) {

                }



                let name = element.getAttribute('data-action');

                let object = action.get(name);

                element[name] = object(element);

                console.log(element);

                element.removeAttribute('data-action');
            }
        });

        return clone;
    }
}