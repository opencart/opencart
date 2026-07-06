import { WebComponent } from '../component.js';
import { loader } from '../index.js';

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

        // Config
        const config = await loader.config('default');

        console.log(config);

        let object = await import(config.config_path + this.src);

        let controller = new object.default(this);

        let output = await controller.execute();

        if (output) {
            return output;
        }
    }
});