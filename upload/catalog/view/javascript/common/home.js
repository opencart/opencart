import { WebComponent } from '../component.js';

class XHome extends WebComponent {
    async connected() {



        this.innerHTML = this.load.template('common/Home', this.language.all());
    }
}

customElements.define('x-home', XHome);