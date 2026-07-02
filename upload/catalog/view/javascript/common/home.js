import { Controller } from '../component.js';
import { loader } from '../index.js';

export default class extends Controller {
    render() {
        return loader.template('common/home');
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }
};