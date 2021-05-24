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
	$('form').trigger('reset');

	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().find(':input');

		if (element.hasClass('form-control')) {
			element.addClass('is-invalid');
		}
	});

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});

	// Currency
	$('#form-currency .dropdown-item').on('click', function(e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').val($(this).attr('href'));

		$('#form-currency').submit();
	});

	// Search
	$('#search input[name=\'search\']').parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'index.php?route=product/search&language=' + $(this).attr('data-lang');

		var value = $('header #search input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header #search input[name=\'search\']').parent().find('button').trigger('click');
		}
	});

	// Menu
	$('#menu .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 10) + 'px');
		}
	});



	// Product List
	$('#list-view').click(function() {
		$('#content .product-grid > .clearfix').remove();

		$('#content .row > .product-grid').attr('class', 'product-layout product-list col-12');

		$('#grid-view').removeClass('active');
		$('#list-view').addClass('active');

		localStorage.setItem('display', 'list');
	});

	var cols = $('#column-left, #column-right').length;

	// Product Grid
	$('#grid-view').click(function() {
		// What a shame bootstrap does not take into account dynamically loaded columns
		if (cols == 2) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-12');
		} else if (cols == 1) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-12');
		} else {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-12');
		}

		$('#list-view').removeClass('active');
		$('#grid-view').addClass('active');

		localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
		$('#list-view').addClass('active');
	} else {
		$('#grid-view').trigger('click');
		$('#grid-view').addClass('active');
	}

	// Column Left / Right Module
	$('#column-left .product-module, #column-right .product-module').attr('class', 'product-layout product-module col-12');

	// Product Module on Pages
	if (cols == 2) {
		$('#content.col').attr('class', 'col-md-6 col-12');
		$('#content .product-module').attr('class', 'product-layout product-module col-lg-6 col-md-6 col-sm-6 col-12');
	} else if (cols == 1) {
		$('#content.col').attr('class', 'col-md-9 col-12');
		$('#content .product-module').attr('class', 'product-layout product-module col-lg-4 col-md-4 col-sm-6 col-12');
	} else {
		$('#content.col').attr('class', 'col-12');
		$('#content .product-module').attr('class', 'product-layout product-module col-lg-3 col-md-3 col-sm-6 col-12');
	}





	/* Agree to Terms */
	$('body').on('click', '.modal-link', function(e) {
		e.preventDefault();

		var element = this;

		$('#modal-information').remove();

		$.ajax({
			url: $(element).attr('href'),
			dataType: 'html',
			success: function(html) {
				$('body').append(html);

				$('#modal-information').modal('show');
			}
		});
	});

	// Cookie Policy
	$('#cookie button').on('click', function() {
		var element = this;

		$.ajax({
			url: $(this).val(),
			type: 'get',
			dataType: 'json',
			beforeSend: function() {
				$(element).button('loading');
			},
			complete: function() {
				$(element).button('reset');
			},
			success: function(json) {
				if (json['success']) {
					$('#cookie').fadeOut(400, function() {
						$('#cookie').remove();
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});

// Cart add remove functions
var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart|add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				$('.text-danger, .toast').remove();
				$('.form-control').removeClass('is-invalid');

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					html  = '<div class="toast">';
					html += '  <div class="toast-body"><button type="button" class="ml-2 mb-1 close float-right" data-dismiss="toast">&times;</button> ' + json['success'] + '</div>';
					html += '</div>';

					$('#toast').prepend(html);

					$('#toast .toast:first-child').toast({'delay': 3000});
					$('#toast .toast:first-child').toast('show');

					// Need to set timeout otherwise it wont update the total
					$('#cart').parent().load('index.php?route=common/cart|info');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart|edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart').parent().load('index.php?route=common/cart|info');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart|remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart').parent().load('index.php?route=common/cart|info');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
};

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart|remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart').parent().load('index.php?route=common/cart|info');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
};

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist|add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.toast').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					html  = '<div class="toast">';
					html += '  <div class="toast-body"><button type="button" class="ml-2 mb-1 close float-right" data-dismiss="toast">&times;</button> ' + json['success'] + '</div>';
					html += '</div>';

					$('#toast').prepend(html);

					$('#toast .toast:first-child').toast({'delay': 3000});
					$('#toast .toast:first-child').toast('show');
				}

				$('#wishlist-total span').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
};

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=product/compare|add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.toast').remove();

				if (json['success']) {
					html  = '<div class="toast">';
					html += '  <div class="toast-body"><button type="button" class="ml-2 mb-1 close float-right" data-dismiss="toast">&times;</button> ' + json['success'] + '</div>';
					html += '</div>';

					$('#toast').prepend(html);

					$('#toast .toast:first-child').toast({'delay': 3000});
					$('#toast .toast:first-child').toast('show');

					$('#compare-total').html(json['total']);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
};

// Forms
$(document).on('click', '[data-oc-action]', function() {
	var element = this;

	var form = $(element).attr('data-oc-form');

	$.ajax({
		url: $(element).attr('data-oc-action'),
		type: 'post',
		dataType: 'json',
		data: new FormData($(form)[0]),
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},
		success: function(json) {
			$('.invalid-tooltip, .alert-dismissible').remove();

			console.log(json);

			console.log(Array.isArray(json['error']));

			if (json['redirect']) {
				location = json['redirect'];

				// Not sure this part works
				delete json['redirect'];
			}


			if (typeof json['error'] == 'object') {
				if (json['error']['warning']) {
					$('.breadcrumb').after('<div class="alert alert-danger alert-dismissible"><i class="fas fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
				}

				for (key in json['error']) {
					var element = $(form).find('#input-' + key.replaceAll('_', '-'));

					// Highlight any found errors
					$(element).addClass('is-invalid');

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="invalid-tooltip d-inline">' + json['error'][key] + '</div>');
					} else {
						$(element).after('<div class="invalid-tooltip d-inline">' + json['error'][key] + '</div>');
					}
				}

				delete json['error'];
			}

			if (typeof json['error'] == 'string') {
				$('.breadcrumb').after('<div class="alert alert-danger alert-dismissible"><i class="fas fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

				delete json['error'];
			}

			if (json['success']) {
				$('.breadcrumb').after('<div class="alert alert-success alert-dismissible"><i class="fas fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

				// Refresh
				var url = $(form).attr('data-oc-load');
				var target = $(form).attr('data-oc-target');

				if (typeof url !== typeof undefined && typeof target !== typeof undefined) {
					$(target).load(url);
				}

				delete json['success'];
			}

			for (key in json) {
				$(form).find('[name=\'' + key + '\']').val(json[key]);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Chain ajax calls.
class Chain {
	constructor() {
		this.start = false;
		this.data = [];
	}

	attach(call) {
		this.data.push(call);

		if (!this.start) {
			this.execute();
		}
	}

	execute() {
		if (this.data.length) {
			this.start = true;

			(this.data.shift())().done(function() {
				chain.execute();
			});
		} else {
			this.start = false;
		}
	}
}

var chain = new Chain();

// Autocomplete
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			var $this = $(this);
			var $dropdown = $('<div class="dropdown-menu"/>');

			this.timer = null;
			this.items = [];

			$.extend(this, option);

			if (!$(this).parent().hasClass('input-group')) {
				$(this).wrap('<div class="dropdown">');
			} else {
				$(this).parent().wrap('<div class="dropdown">');
			}

			$this.attr('autocomplete', 'off');
			$this.active = false;

			// Focus
			$this.on('focus', function() {
				this.request();
			});

			// Blur
			$this.on('blur', function(e) {
				if (!$this.active) {
					this.hide();
				}
			});

			$this.parent().on('mouseover', function(e) {
				$this.active = true;
			});

			$this.parent().on('mouseout', function(e) {
				$this.active = false;
			});

			// Keydown
			$this.on('keydown', function(event) {
				switch (event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				var value = $(event.target).attr('href');

				if (value && this.items[value]) {
					this.select(this.items[value]);

					this.hide();
				}
			}

			// Show
			this.show = function() {
				$dropdown.addClass('show');
			}

			// Hide
			this.hide = function() {
				$dropdown.removeClass('show');
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 50, this);
			}

			// Response
			this.response = function(json) {
				var html = '';
				var category = {};
				var name;
				var i = 0, j = 0;

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						// update element items
						this.items[json[i]['value']] = json[i];

						if (!json[i]['category']) {
							// ungrouped items
							html += '<a href="' + json[i]['value'] + '" class="dropdown-item">' + json[i]['label'] + '</a>';
						} else {
							// grouped items
							name = json[i]['category'];

							if (!category[name]) {
								category[name] = [];
							}

							category[name].push(json[i]);
						}
					}

					for (name in category) {
						html += '<h6 class="dropdown-header">' + name + '</h6>';

						for (j = 0; j < category[name].length; j++) {
							html += '<a href="' + category[name][j]['value'] + '" class="dropdown-item">&nbsp;&nbsp;&nbsp;' + category[name][j]['label'] + '</a>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$dropdown.html(html);
			}

			$dropdown.on('click', '> a', $.proxy(this.click, this));

			$this.after($dropdown);
		});
	}
})(window.jQuery);

+function($) {
	'use strict';

	// BUTTON PUBLIC CLASS DEFINITION
	// ==============================

	var Button = function(element, options) {
		this.$element = $(element)
		this.options = $.extend({}, Button.DEFAULTS, options)
		this.isLoading = false
	}

	Button.VERSION = '3.3.5'

	Button.DEFAULTS = {
		loadingText: 'loading...'
	}

	Button.prototype.setState = function(state) {
		var d = 'disabled'
		var $el = this.$element
		var val = $el.is('input') ? 'val' : 'html'
		var data = $el.data()

		state += 'Text'

		if (data.resetText == null) $el.data('resetText', $el[val]())

		// push to event loop to allow forms to submit
		setTimeout($.proxy(function() {
			$el[val](data[state] == null ? this.options[state] : data[state])

			if (state == 'loadingText') {
				this.isLoading = true
				$el.addClass(d).attr(d, d)
			} else if (this.isLoading) {
				this.isLoading = false
				$el.removeClass(d).removeAttr(d)
			}
		}, this), 0)
	}

	Button.prototype.toggle = function() {
		var changed = true
		var $parent = this.$element.closest('[data-toggle="buttons"]')

		if ($parent.length) {
			var $input = this.$element.find('input')
			if ($input.prop('type') == 'radio') {
				if ($input.prop('checked')) changed = false
				$parent.find('.active').removeClass('active')
				this.$element.addClass('active')
			} else if ($input.prop('type') == 'checkbox') {
				if (($input.prop('checked')) !== this.$element.hasClass('active')) changed = false
				this.$element.toggleClass('active')
			}
			$input.prop('checked', this.$element.hasClass('active'))
			if (changed) $input.trigger('change')
		} else {
			this.$element.attr('aria-pressed', !this.$element.hasClass('active'))
			this.$element.toggleClass('active')
		}
	}

	// BUTTON PLUGIN DEFINITION
	// ========================

	function Plugin(option) {
		return this.each(function() {
			var $this = $(this)
			var data = $this.data('bs.button')
			var options = typeof option == 'object' && option

			if (!data) $this.data('bs.button', (data = new Button(this, options)))

			if (option == 'toggle') data.toggle()
			else if (option) data.setState(option)
		})
	}

	var old = $.fn.button

	$.fn.button = Plugin
	$.fn.button.Constructor = Button


	// BUTTON NO CONFLICT
	// ==================

	$.fn.button.noConflict = function() {
		$.fn.button = old
		return this
	}


	// BUTTON DATA-API
	// ===============

	$(document).on('click.bs.button.data-api', '[data-toggle^="button"]', function(e) {
		var $btn = $(e.target);

		if (!$btn.hasClass('btn')) $btn = $btn.closest('.btn');

		Plugin.call($btn, 'toggle');

		if (!($(e.target).is('input[type="radio"]') || $(e.target).is('input[type="checkbox"]'))) e.preventDefault();
	}).on('focus.bs.button.data-api blur.bs.button.data-api', '[data-toggle^="button"]', function(e) {
		$(e.target).closest('.btn').toggleClass('focus', /^focus(in)?$/.test(e.type));
	});
}(jQuery);