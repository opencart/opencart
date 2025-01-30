jQuery.fn.extend({
    shadowRoot: function() {
        return $(this.get(0).shadowRoot);
    },
});

const $ = jQuery;

import './webcomponent/alert.js';
//import './webcomponent/country.js';
//import './webcomponent/currency.js';
//import './webcomponent/form.js';
//import './webcomponent/language.js';
//import './webcomponent/modal.js';