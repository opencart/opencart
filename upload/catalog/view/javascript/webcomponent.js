jQuery.fn.extend({
    shadowRoot: function() {
        return $(this.get(0).shadowRoot);
    },
});

const $ = jQuery;

import './webcomponent/country.js';