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
		focus: function(event) {
			$(this.element).parent().addClass('open');
		},
		blur: function(event) {
			setTimeout(function(object) {
				$(object.element).parent().removeClass('open');
			}, 300, this);
		},
		keypress: function(event) {
			response = function() {
				alert($(this.element).val());
			}
			
			this.options.source($(this.element).val(), response);
			
			this.render();
		},
		click: function(e) {
			e.preventDefault();
				
			this.options.select();
		},					
		render: function() {
			/*
			html = '';
			
			if (json.length) {
				for (i in json) {
					if (json[i]['label']) {
						option = json[i]['option'];
						
						html += '<li class="disabled"><a href="#"><b>' + json[i]['label'] + '</b></a></li>';
						
						for (j = 0; j < option.length; j++) {
					
						}
					} else {
						html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['text'] + '</a></li>';
					}
				}
			}
			*/
			html = '<li data-value="0"><a href="#">test</a></li>';

			$(this.element).parent().find('ul.dropdown-menu').html(html);
			
			$(this.element).parent().find('ul.dropdown-menu a').on('click', $.proxy(this.click, this));
		}
	};

	$.fn.autocomplete = function(option) {
		return this.each(function() {
			var $this = $(this);
			var data = $this.data('autocomplete');
			var options = typeof option == 'object' && option;
			
			if (!data) {
				data = new Autocomplete(this, options);
				
				$this.data('autocomplete', data);
			}
			
			if (typeof option == 'string') {
				data.option();
			}
		});	
	}
})(window.jQuery);