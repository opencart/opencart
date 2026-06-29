import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

customElements.define('x-include', class extends WebComponent {
    static observed = ['src'];

    get src() {
        return this.getAttribute('src');
    }

    set src(src) {
        this.setAttribute('src', src);
    }

    async render() {
        // Get the source HTML to load
        if (!this.src) return;

        console.log(this.src);

        let object = await import(config.config_path + this.src);

        console.log(object);

        let controller = new object.default(this);

        //controller.set(attributes);


        //controller.setAttribute


        let output = await controller.render();

        if (output) {
            return output;
        }
    }
});