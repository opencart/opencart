import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/transaction');

class extends Controller {
    render() {


        return loader.template('account/transaction', { ...language });
    }
}