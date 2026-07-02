import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

customElements.define('oc-include', class extends WebComponent {
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

        let object = await import(config.config_path + this.src);

        let controller = new object.default(this);

        let output = await controller.render();

        if (output) {
            return output;
        }
    }
});