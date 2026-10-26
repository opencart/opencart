import { WebComponent } from '../index.js';
import { loader } from '../index.js';
import '../component.js';
import './header.js';
import './footer.js';

export default class CommonLayout extends WebComponent {
    async render() {
        return loader.template('common/layout');
	}
	
	handleDialogOpen() {
		const dialog = document.getElementById('myDialog');

		const observer = new MutationObserver((mutations) => {
			mutations.forEach((mutation) => {
				if (mutation.attributeName === 'open') {
					if (dialog.open) {
						console.log('Dialog opened via attribute change!');
					} else {
						console.log('Dialog closed!');
					}
				}
			});
		});

		observer.observe(dialog, {
			attributes: true
		});
	}
}

customElements.define('common-layout', CommonLayout);