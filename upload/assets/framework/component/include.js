import { WebComponent } from '../component.js';

class XInclude extends WebComponent {
    static observed = ['src'];

    get src() {
        return this.getAttribute('src');
    }

    set src(src) {
        this.setAttribute('src', src);
    }

    async render() {
        let test = await import('./' + this.src + '.js');

        console.log(test);

        return test;
    }
}

customElements.define('x-include', XInclude);