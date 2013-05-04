function getURLVar(key) {
	var value = [];
	
	var query = String(document.location).split('?');
	
	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');
			
			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}
		
		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
} 

$(document).ready(function() {
	route = getURLVar('route');
	
	if (!route) {
		$('#dashboard').addClass('active');
	} else {
		part = route.split('/');
		
		url = part[0];
		
		if (part[1]) {
			url += '/' + part[1];
		}
		
		$('a[href*=\'' + url + '\']').parents('li[id]').addClass('selected');
	}
	
	$('[data-toggle=\'tooltip\']').tooltip({
		'placement': 'top',
		'animation': false,
		'html': true
	});
});

// Added my own autocomplete method for jquery since bootstraps is pretty much useless	
(function($) {
	function Autocomplete(element, options) {
		this.element = element;
		this.options = options;
		this.timer = null;
		this.items = [];
		
		if (!$(element).parent().has('.dropdown').length) {
			$(element).attr('autocomplete', 'off');
			
			$(element).wrap('<div class="dropdown" style="border: 1px solid #000000;"></div>');
			$(element).after('<ul class="dropdown-menu"></ul>');

			$(element).on('focus', $.proxy(this.focus, this));
			$(element).on('blur', $.proxy(this.blur, this));
			$(element).on('keypress', $.proxy(this.keypress, this));
		}
	}
	
	Autocomplete.prototype = {
		focus: function() {
			this.request();
		},
		blur: function() {
			setTimeout(function(object) {
				$(object.element).parent().removeClass('open');
			}, 500, this);
		},
		click: function(e) {
			e.preventDefault();
			
			value = $(e.target).parent().attr('data-value');
			
			if (this.items[value]) {
				this.options.select(this.items[value]);
			}
		},	
		keypress: function() {
			this.request();
		},
		request: function() {
			clearTimeout(this.timer);
			
			this.timer = setTimeout(function(object) {
				object.options.source($(object.element).val(), $.proxy(object.response, object));
			}, 300, this);	
		},		
		response: function(json) {
			html = '';
			
			if (json.length) {
				for (i = 0; i < json.length; i++) {
					console.log(json[i]['label']);
					
					this.items[json[i]['value']] = json[i];
					
					html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
				}
			}

			$(this.element).parent().find('ul.dropdown-menu').html(html);
			
			if ($(this.element).parent().find('ul.dropdown-menu').has('li').length) {
				$(this.element).parent().addClass('open');
			} else {
				$(this.element).parent().removeClass('open');
			}
					
			$(this.element).parent().find('ul.dropdown-menu a').on('click', $.proxy(this.click, this));			
			$(this.element).parent().find('ul.dropdown-menu a').on('mouseup', $.proxy(this.mouseup, this));		
		}
	};

	$.fn.autocomplete = function(option) {
		return this.each(function() {
			var $this = $(this);
			var data = $this.data('autocomplete');
			
			if (!data) {
				data = new Autocomplete(this, option);
				
				$this.data('autocomplete', data);
			}
		});	
	}
})(window.jQuery);