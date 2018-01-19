$(document).on('change', 'input[name=\'account\']', function() {
	if ($('#collapse-payment-address').parent().find('.panel-heading .panel-title > *').is('a')) {
		if (this.value == 'register') {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_account }} <i class="fa fa-caret-down"></i></a>');
		} else {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_payment_address }} <i class="fa fa-caret-down"></i></a>');
		}
	} else {
		if (this.value == 'register') {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('{{ text_checkout_account }}');
		} else {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('{{ text_checkout_payment_address }}');
		}
	}
});

{% if not logged %}
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=checkout/login&language={{ language }}',
		dataType: 'html',
		success: function(html) {
			$('#collapse-checkout-option .panel-body').html(html);

			$('#collapse-checkout-option').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-option" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_option }} <i class="fa fa-caret-down"></i></a>');

			$('a[href=\'#collapse-checkout-option\']').trigger('click');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
{% else %}
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_address&language={{ language }}',
		dataType: 'html',
		success: function(html) {
			$('#collapse-payment-address .panel-body').html(html);

			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_payment_address }} <i class="fa fa-caret-down"></i></a>');

			$('a[href=\'#collapse-payment-address\']').trigger('click');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
{% endif %}

// Checkout
$(document).delegate('#button-account', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/' + $('input[name=\'account\']:checked').val() " '&language={{ language }}',
		dataType: 'html',
		beforeSend: function() {
		$('#button-account').button('loading');
	},
	complete: function() {
		$('#button-account').button('reset');
	},
	success: function(html) {
		$('.alert-dismissible, .text-danger').remove();
		$('.form-group').removeClass('has-error');

		$('#collapse-payment-address .panel-body').html(html);

		if ($('input[name=\'account\']:checked').val() == 'register') {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_account }} <i class="fa fa-caret-down"></i></a>');
		} else {
			$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_payment_address }} <i class="fa fa-caret-down"></i></a>');
		}

		$('a[href=\'#collapse-payment-address\']').trigger('click');
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});
});

// Login
$(document).delegate('#button-login', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/login/save&language={{ language }}',
		type: 'post',
		data: $('#collapse-checkout-option :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-login').button('loading');
		},
		complete: function() {
			$('#button-login').button('reset');
		},
		success: function(json) {
			$('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#collapse-checkout-option .panel-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Highlight any found errors
				$('input[name=\'email\']').parent().addClass('has-error');
				$('input[name=\'password\']').parent().addClass('has-error');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Register
$(document).delegate('#button-register', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/register/save&language={{ language }}',
		type: 'post',
		data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'password\'], #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address textarea, #collapse-payment-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-register').button('loading');
		},
		success: function(json) {
			$('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-register').button('reset');

				if (json['error']['warning']) {
					$('#collapse-payment-address .panel-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				for (i in json['error']) {
					var element = $('#input-payment-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			} else {
				{% if shipping_required %}
				var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').prop('value');

				if (shipping_address) {
					$.ajax({
						url: 'index.php?route=checkout/shipping_method&language={{ language }}',
						dataType: 'html',
						success: function(html) {
							// Add the shipping address
							$.ajax({
								url: 'index.php?route=checkout/shipping_address&language={{ language }}',
								dataType: 'html',
								success: function(html) {
									$('#collapse-shipping-address .panel-body').html(html);

									$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_shipping_address }} <i class="fa fa-caret-down"></i></a>');
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});

							$('#collapse-shipping-method .panel-body').html(html);

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_shipping_method }} <i class="fa fa-caret-down"></i></a>');

							$('a[href=\'#collapse-shipping-method\']').trigger('click');

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_shipping_method }}');
							$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_payment_method }}');
							$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				} else {
					$.ajax({
						url: 'index.php?route=checkout/shipping_address&language={{ language }}',
						dataType: 'html',
						success: function(html) {
							$('#collapse-shipping-address .panel-body').html(html);

							$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_shipping_address }} <i class="fa fa-caret-down"></i></a>');

							$('a[href=\'#collapse-shipping-address\']').trigger('click');

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_shipping_method }}');
							$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_payment_method }}');
							$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				{% else %}
				$.ajax({
					url: 'index.php?route=checkout/payment_method&language={{ language }}',
					dataType: 'html',
					success: function(html) {
						$('#collapse-payment-method .panel-body').html(html);

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_payment_method }} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-payment-method\']').trigger('click');

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				{% endif %}

				$.ajax({
					url: 'index.php?route=checkout/payment_address&language={{ language }}',
					dataType: 'html',
					complete: function() {
						$('#button-register').button('reset');
					},
					success: function(html) {
						$('#collapse-payment-address .panel-body').html(html);

						$('#collapse-payment-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_payment_address }} <i class="fa fa-caret-down"></i></a>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Payment Address
$(document).delegate('#button-payment-address', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_address/save&language={{ language }}',
		type: 'post',
		data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'password\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address textarea, #collapse-payment-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-payment-address').button('loading');
		},
		complete: function() {
			$('#button-payment-address').button('reset');
		},
		success: function(json) {
			$('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#collapse-payment-address .panel-body').prepend('<div class="alert alert-warning alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				for (i in json['error']) {
					var element = $('#input-payment-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().parent().addClass('has-error');
			} else {
				{% if shipping_required %}
				$.ajax({
					url: 'index.php?route=checkout/shipping_address&language={{ language }}',
					dataType: 'html',
					success: function(html) {
						$('#collapse-shipping-address .panel-body').html(html);

						$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_shipping_address }} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-shipping-address\']').trigger('click');

						$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_shipping_method }}');
						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_payment_method }}');
						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				}).done(function() {
					$.ajax({
						url: 'index.php?route=checkout/payment_address&language={{ language }}',
						dataType: 'html',
						success: function(html) {
							$('#collapse-payment-address .panel-body').html(html);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				});
				{% else %}
				$.ajax({
					url: 'index.php?route=checkout/payment_method&language={{ language }}',
					dataType: 'html',
					success: function(html) {
						$('#collapse-payment-method .panel-body').html(html);

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_payment_method }} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-payment-method\']').trigger('click');

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				}).done(function() {
					$.ajax({
						url: 'index.php?route=checkout/payment_address&language={{ language }}',
						dataType: 'html',
						success: function(html) {
							$('#collapse-payment-address .panel-body').html(html);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				});
				{% endif %}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Shipping Address
$(document).delegate('#button-shipping-address', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/shipping_address/save&language={{ language }}',
		type: 'post',
		data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping-address').button('loading');
		},
		success: function(json) {
			$('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-shipping-address').button('reset');

				if (json['error']['warning']) {
					$('#collapse-shipping-address .panel-body').prepend('<div class="alert alert-warning alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				for (i in json['error']) {
					var element = $('#input-shipping-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().parent().addClass('has-error');
			} else {
				$.ajax({
					url: 'index.php?route=checkout/shipping_method&language={{ language }}',
					dataType: 'html',
					complete: function() {
						$('#button-shipping-address').button('reset');
					},
					success: function(html) {
						$('#collapse-shipping-method .panel-body').html(html);

						$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_shipping_method }} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-shipping-method\']').trigger('click');

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_payment_method }}');
						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');

						$.ajax({
							url: 'index.php?route=checkout/shipping_address&language={{ language }}',
							dataType: 'html',
							success: function(html) {
								$('#collapse-shipping-address .panel-body').html(html);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				}).done(function() {
					$.ajax({
						url: 'index.php?route=checkout/payment_address&language={{ language }}',
						dataType: 'html',
						success: function(html) {
							$('#collapse-payment-address .panel-body').html(html);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Guest
$(document).delegate('#button-guest', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/guest/save&language={{ language }}',
		type: 'post',
		data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'date\'], #collapse-payment-address input[type=\'datetime-local\'], #collapse-payment-address input[type=\'time\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address textarea, #collapse-payment-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-guest').button('loading');
		},
		success: function(json) {
			$('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-guest').button('reset');

				if (json['error']['warning']) {
					$('#collapse-payment-address .panel-body').prepend('<div class="alert alert-warning alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				for (i in json['error']) {
					var element = $('#input-payment-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			} else {
				{% if shipping_required %}
				var shipping_address = $('#collapse-payment-address input[name=\'shipping_address\']:checked').prop('value');

				if (shipping_address) {
					$.ajax({
						url: 'index.php?route=checkout/shipping_method&language={{ language }}',
						dataType: 'html',
						complete: function() {
							$('#button-guest').button('reset');
						},
						success: function(html) {
							// Add the shipping address
							$.ajax({
								url: 'index.php?route=checkout/guest_shipping&language={{ language }}',
								dataType: 'html',
								success: function(html) {
									$('#collapse-shipping-address .panel-body').html(html);

									$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_shipping_address }} <i class="fa fa-caret-down"></i></a>');
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});

							$('#collapse-shipping-method .panel-body').html(html);

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_shipping_method }} <i class="fa fa-caret-down"></i></a>');

							$('a[href=\'#collapse-shipping-method\']').trigger('click');

							$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_payment_method }}');
							$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				} else {
					$.ajax({
						url: 'index.php?route=checkout/guest_shipping&language={{ language }}',
						dataType: 'html',
						complete: function() {
							$('#button-guest').button('reset');
						},
						success: function(html) {
							$('#collapse-shipping-address .panel-body').html(html);

							$('#collapse-shipping-address').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_shipping_address }} <i class="fa fa-caret-down"></i></a>');

							$('a[href=\'#collapse-shipping-address\']').trigger('click');

							$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_shipping_method }}');
							$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_payment_method }}');
							$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
				{% else %}
				$.ajax({
					url: 'index.php?route=checkout/payment_method&language={{ language }}',
					dataType: 'html',
					complete: function() {
						$('#button-guest').button('reset');
					},
					success: function(html) {
						$('#collapse-payment-method .panel-body').html(html);

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_payment_method }} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-payment-method\']').trigger('click');

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				{% endif %}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Guest Shipping
$(document).delegate('#button-guest-shipping', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/guest_shipping/save&language={{ language }}',
		type: 'post',
		data: $('#collapse-shipping-address input[type=\'text\'], #collapse-shipping-address input[type=\'date\'], #collapse-shipping-address input[type=\'datetime-local\'], #collapse-shipping-address input[type=\'time\'], #collapse-shipping-address input[type=\'password\'], #collapse-shipping-address input[type=\'checkbox\']:checked, #collapse-shipping-address input[type=\'radio\']:checked, #collapse-shipping-address textarea, #collapse-shipping-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-guest-shipping').button('loading');
		},
		success: function(json) {
			$('.alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-guest-shipping').button('reset');

				if (json['error']['warning']) {
					$('#collapse-shipping-address .panel-body').prepend('<div class="alert alert-danger alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				for (i in json['error']) {
					var element = $('#input-shipping-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			} else {
				$.ajax({
					url: 'index.php?route=checkout/shipping_method&language={{ language }}',
					dataType: 'html',
					complete: function() {
						$('#button-guest-shipping').button('reset');
					},
					success: function(html) {
						$('#collapse-shipping-method .panel-body').html(html);

						$('#collapse-shipping-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_shipping_method }} <i class="fa fa-caret-down"></i>');

						$('a[href=\'#collapse-shipping-method\']').trigger('click');

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('{{ text_checkout_payment_method }}');
						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-shipping-method', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/shipping_method/save&language={{ language }}',
		type: 'post',
		data: $('#collapse-shipping-method input[type=\'radio\']:checked, #collapse-shipping-method textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping-method').button('loading');
		},
		success: function(json) {
			$('.alert-dismissible, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-shipping-method').button('reset');

				if (json['error']['warning']) {
					$('#collapse-shipping-method .panel-body').prepend('<div class="alert alert-danger alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			} else {
				$.ajax({
					url: 'index.php?route=checkout/payment_method&language={{ language }}',
					dataType: 'html',
					complete: function() {
						$('#button-shipping-method').button('reset');
					},
					success: function(html) {
						$('#collapse-payment-method .panel-body').html(html);

						$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_payment_method }} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-payment-method\']').trigger('click');

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('{{ text_checkout_confirm }}');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-payment-method', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_method/save&language={{ language }}',
		type: 'post',
		data: $('#collapse-payment-method input[type=\'radio\']:checked, #collapse-payment-method input[type=\'checkbox\']:checked, #collapse-payment-method textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-payment-method').button('loading');
		},
		success: function(json) {
			$('.alert-dismissible, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').button('reset');

				if (json['error']['warning']) {
					$('#collapse-payment-method .panel-body').prepend('<div class="alert alert-danger alert-dismissible">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			} else {
				$.ajax({
					url: 'index.php?route=checkout/confirm&language={{ language }}',
					dataType: 'html',
					complete: function() {
						$('#button-payment-method').button('reset');
					},
					success: function(html) {
						$('#collapse-checkout-confirm .panel-body').html(html);

						$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-confirm" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{{ text_checkout_confirm }} <i class="fa fa-caret-down"></i></a>');

						$('a[href=\'#collapse-checkout-confirm\']').trigger('click');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});


/// payment address
$('input[name=\'payment_address\']').on('change', function() {
	if (this.value == 'new') {
		$('#payment-existing').hide();
		$('#payment-new').show();
	} else {
		$('#payment-existing').show();
		$('#payment-new').hide();
	}
});

// Sort the custom fields
$('#collapse-payment-address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#collapse-payment-address .form-group').length-2) {
		$('#collapse-payment-address .form-group').eq(parseInt($(this).attr('data-sort'))+2).before(this);
	}

	if ($(this).attr('data-sort') > $('#collapse-payment-address .form-group').length-2) {
		$('#collapse-payment-address .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') == $('#collapse-payment-address .form-group').length-2) {
		$('#collapse-payment-address .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#collapse-payment-address .form-group').length-2) {
		$('#collapse-payment-address .form-group:first').before(this);
	}
});

$('#collapse-payment-address button[id^=\'button-payment-custom-field\']').on('click', function() {
	var element = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
		clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload&language={{ language }}',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
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
					$(element).parent().find('.text-danger').remove();

					if (json['error']) {
						$(element).parent().find('input[name^=\'custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(element).parent().find('input[name^=\'custom_field\']').val(json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('.date').datetimepicker({
	language: '{{ datepicker }}',
	pickTime: false
});

$('.time').datetimepicker({
	language: '{{ datepicker }}',
	pickDate: false
});

$('.datetime').datetimepicker({
	language: '{{ datepicker }}',
	pickDate: true,
	pickTime: true
});

	$('#collapse-payment-address select[name=\'country_id\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=checkout/checkout/country&language={{ language }}&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('#collapse-payment-address select[name=\'country_id\']').prop('disabled', true);
			},
			complete: function() {
				$('#collapse-payment-address select[name=\'country_id\']').prop('disabled', false);
			},
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#collapse-payment-address input[name=\'postcode\']').parent().parent().addClass('required');
				} else {
					$('#collapse-payment-address input[name=\'postcode\']').parent().parent().removeClass('required');
				}

				html = '<option value="">{{ text_select }}</option>';

				if (json['zone'] && json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';

						if (json['zone'][i]['zone_id'] == '{{ zone_id }}') {
							html += ' selected="selected"';
						}

						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected">{{ text_none }}</option>';
				}

				$('#collapse-payment-address select[name=\'zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

$('#collapse-payment-address select[name=\'country_id\']').trigger('change');

