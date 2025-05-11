export default class Observer {
    constructor(path) {
        this.path = path;
    }

    observe(element, callback, config) {
        let observer = new MutationObserver(callback);

         observer.observe(element, config);
     }
}