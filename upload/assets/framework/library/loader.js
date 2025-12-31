import { registry } from './registry.js';
import { event } from './event.js';

export class Loader {
    static instance;

    constructor(registry) {
        this.registry = registry;
        this.factory = this.registry.get('factory');
    }

    async config(path) {
        return await this.registry.get('config').fetch(path);
    }

    async storage(path) {
        return await this.registry.get('storage').fetch(path);
    }

    async language(path) {
        return await this.registry.get('language').fetch(path);
    }

    async template(path, data = {}) {
        return await this.registry.get('template').render(path, data);
    }

    async library(path, config = {}) {
        if (this.registry.has(path)) {
            return this.registry.get(path);
        }

        if (this.factory.has(path)) {
            this.registry.set(path, this.factory.get(path).bind({ registry: this.registry }).apply(config));
        }

        return await this.registry.get(path);
    }

    static getInstance(registry) {
        if (!this.instance) {
            this.instance = new Loader(registry);
        }

        return this.instance;
    }
}

const loader = Loader.getInstance(registry);

let override = (path, args = {}) => {
    event.trigger(property + '/' + path + '/before', args);


    event.trigger(property + '/' + path + '/after', args);
};

const proxy = new Proxy(loader, {
    get(target, property, receiver) {


        let object = Reflect.get(target, property);

        //console.log(target);
        //console.log(property);
       // console.log(receiver);


        return object;



        override.bind(this);


        return (path, args = {}) => {
            console.log(path);

            console.log(this);

            event.trigger(property + '/' + path + '/before', args);


            event.trigger(property + '/' + path + '/after', args);
        };
    },
    has() {

    }
});

console.log(proxy);

// Add loader to the registry
registry.set('loader', proxy);

export { proxy as loader };