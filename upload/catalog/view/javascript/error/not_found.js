import { WebComponent} from '../index.js';
import { loader } from '../index.js';

export default class ErrorNotFound extends WebComponent {
    async render(){



        return loader.template('information/contact', [ data, language, config ]);
    }
}

customElements.define('error-not-found', ErrorNotFound);