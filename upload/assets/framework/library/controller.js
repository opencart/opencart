export class Controller {
    element = HTMLElement;

    constructor(element) {
        this.element = element;

        let methods = Object.getOwnPropertyNames(Object.getPrototypeOf(this));

        for (let method of methods) {
            if (element[method] == undefined && typeof this[method] === 'function') {
                element[method] = this[method].bind(this);
            }
        }
    }
}