import { loader } from '../index.js';
import {WebComponent} from "../../../../assets/framework/library/webcomponent";

// Library
const session = loader.library('session');

// Language
const language = await loader.language('account/password');

customElements.define('account-password', class extends WebComponent {
    render() {
        let data = {};

        return loader.template('account/password', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
});