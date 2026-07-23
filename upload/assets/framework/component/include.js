import {WebComponent} from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

customElements.define('x-include', class extends WebComponent {
    static observed = ['src'];

    get src() {
        return this.getAttribute('src');    }

    set src(src) {
        this.setAttribute('src', src);
    }

    async render() {
        this.innerHTML = '';

        // Get the source HTML to load
        if (!this.src) return;

        console.log('x-include', this.src);

        let object = await import(config.config_path + this.src);

        let controller = new object.default(this);

        // this.innerHTML = await controller.execute();
        this.append(await controller.execute());
    }
});