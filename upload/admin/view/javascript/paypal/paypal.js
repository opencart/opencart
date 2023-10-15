var PayPalAPI = (function () {
	var paypal_data = [];
	var paypal_script = [];
	var paypal_sdk = [];
	var paypal_callback;
		
	var readyPayPalSDK = function() {
		if (typeof PayPalSDK === 'undefined') {
			setTimeout(readyPayPalSDK, 100);
		} else {
			paypal_sdk[paypal_script.length - 1] = PayPalSDK;
			
			initPayPalSDK();
		}
	};
		
	var loadPayPalSDK = function() {
		if (paypal_data['components'].includes('buttons')) {
			$('#paypal_button_' + paypal_data['page_code'] + '_container').html('');
			$('#paypal_button_' + paypal_data['page_code'] + '_container').addClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('applepay')) {
			$('#applepay_button_container').html('');
			$('#applepay_button_container').addClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('hosted-fields')) {
			$('#paypal_card_container').find('iframe').remove();
			$('#paypal_card_container').addClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('messages')) {
			$('#paypal_message_' + paypal_data['page_code'] + '_container').html('');
			$('#paypal_message_' + paypal_data['page_code'] + '_container').addClass('paypal-spinner');
		}
		
		var src_data = {};
		
		src_data['components'] = paypal_data['components'].join(',');
		src_data['client-id'] = paypal_data['client_id'];
		src_data['merchant-id'] = paypal_data['merchant_id'];
		src_data['currency'] = paypal_data['currency_code'];
		src_data['intent'] = paypal_data['transaction_method'];
				
		if (paypal_data['button_enable_funding'] && paypal_data['button_enable_funding'].length) {
			src_data['enable-funding'] = paypal_data['button_enable_funding'].join(',');
		}
		
		if (paypal_data['button_disable_funding'] && paypal_data['button_disable_funding'].length) {
			src_data['disable-funding'] = paypal_data['button_disable_funding'].join(',');
		}
		
		var src = 'https://www.paypal.com/sdk/js?' + $.param(src_data);
		var script_count = paypal_script.length;
		var script_load_status = 0;
		
		for (var i = 0; i < script_count; i++) {
			if (paypal_script[i].src == src) {
				script_load_status = 1;
				
				break;
			}
		}
		
		if (!script_load_status) {
			if (typeof PayPalSDK !== 'undefined') {
				delete PayPalSDK;
			}
			
			paypal_script[script_count] = document.createElement('script');
			paypal_script[script_count].type = 'text/javascript';
			paypal_script[script_count].src = src;
			paypal_script[script_count].setAttribute('data-partner-attribution-id', paypal_data['partner_attribution_id']);
			paypal_script[script_count].setAttribute('data-client-token', paypal_data['client_token']);
			paypal_script[script_count].setAttribute('data-namespace', 'PayPalSDK');			
			
			paypal_script[script_count].async = false;
			paypal_script[script_count].onload = readyPayPalSDK();
			
			document.querySelector('body').appendChild(paypal_script[script_count]);
		} else if (paypal_sdk[i]) {
			PayPalSDK = paypal_sdk[i];
			
			initPayPalSDK();
		}
	};
	
	var initPayPalSDK = function() {
		if (paypal_data['components'].includes('buttons') && $('#paypal_button_' + paypal_data['page_code']).length) {
			$('#paypal_button_' + paypal_data['page_code']).css('text-align', paypal_data['button_align']);
						
			if (paypal_data['button_width']) {
				$('#paypal_button_' + paypal_data['page_code'] + '_container').css('display', 'inline-block');
				$('#paypal_button_' + paypal_data['page_code'] + '_container').css('width', paypal_data['button_width']);
			} else {
				$('#paypal_button_' + paypal_data['page_code'] + '_container').css('display', 'block');
				$('#paypal_button_' + paypal_data['page_code'] + '_container').css('width', 'auto');
			}
			
			try {				
				var paypal_button_data = {
					env: paypal_data['environment'],
					locale: paypal_data['locale'],
					style: {
						layout: ((paypal_data['page_code'] != 'checkout') ? 'horizontal' : 'vertical'),
						size: paypal_data['button_size'],
						color: paypal_data['button_color'],
						shape: paypal_data['button_shape'],
						label: paypal_data['button_label'],
						tagline: ((paypal_data['page_code'] != 'checkout') ? paypal_data['button_tagline'] : 'false')
					}
				};
				
				var paypal_button = PayPalSDK.Buttons(paypal_button_data);
				
				if (paypal_button.isEligible()) {
					paypal_button.render('#paypal_button_' + paypal_data['page_code'] + '_container');
				}
			} catch (error) {
				console.error('PayPal Button failed during startup', error);
			}
			
			$('#paypal_button_' + paypal_data['page_code'] + '_container').removeClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('applepay') && $('#applepay_button').length && !$('#applepay_button_container').html()) {
			$('#applepay_button').css('text-align', paypal_data['applepay_button_align']);
			
			var applepay_button_style = [];
			
			if (paypal_data['applepay_button_width']) {
				$('#applepay_button_container').css('display', 'inline-block');
				$('#applepay_button_container').css('width', paypal_data['applepay_button_width']);
			} else {
				$('#applepay_button_container').css('display', 'block');
				$('#applepay_button_container').css('width', 'auto');
			}
						
			var applepay_button = document.createElement('apple-pay-button');
			
			applepay_button.setAttribute('id', 'apple-pay-button');
			applepay_button.setAttribute('buttonstyle', paypal_data['applepay_button_color']);
			applepay_button.setAttribute('type', paypal_data['applepay_button_type']);
			applepay_button.setAttribute('locale', paypal_data['locale']);
						
			var applepay_button_style = [];
			
			applepay_button_style.push('display: inline-block');
						
			if (paypal_data['applepay_button_width']) {
				applepay_button_style.push('--apple-pay-button-width: ' + paypal_data['applepay_button_width']);
			} else {
				applepay_button_style.push('--apple-pay-button-width: 100%');
			}
						
			applepay_button_style.push('--apple-pay-button-height: calc(var(--apple-pay-button-width) / 7.5)');
						
			if (paypal_data['applepay_button_shape'] == 'pill') {
				applepay_button_style.push('--apple-pay-button-border-radius: 1000px');
			} else {
				applepay_button_style.push('--apple-pay-button-border-radius: 4px');
			}
																		
			applepay_button.setAttribute('style', applepay_button_style.join('; '));
															
			document.querySelector('#applepay_button_container').appendChild(applepay_button);			
									
			$('#applepay_button_container').removeClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('hosted-fields') && $('#paypal_card').length) {
			$('#paypal_card').css('text-align', paypal_data['card_align']);
						
			if (paypal_data['card_width']) {
				$('#paypal_card_container').css('display', 'inline-block');
				$('#paypal_card_container').css('width', paypal_data['card_width']);
			} else {
				$('#paypal_card_container').css('display', 'block');
				$('#paypal_card_container').css('width', 'auto');
			}
			
			try {
				// Check if card fields are eligible to render for the buyer's country. The card fields are not eligible in all countries where buyers are located.
				if (PayPalSDK.HostedFields.isEligible() === true) {			
					var paypal_card_form = document.querySelector('#paypal_card_form');
					var paypal_button_submit = document.querySelector('#paypal_button_submit');
			
					PayPalSDK.HostedFields.render({
						styles: {
							'input': {
								'color': '#282c37',
								'transition': 'color 0.1s',
								'line-height': '3'
							},
							'input.invalid': {
								'color': '#E53A40'
							},
							':-ms-input-placeholder': {
								'color': 'rgba(0,0,0,0.6)'
							},
							':-moz-placeholder': {
								'color': 'rgba(0,0,0,0.6)'
							}
						},
						fields: {
							number: {
								selector: '#card_number',
								placeholder: '#### #### #### ####'
							},
							cvv: {
								selector: '#cvv',
								placeholder: '###'
							},
							expirationDate: {
								selector: '#expiration_date',
								placeholder: 'MM / YYYY'
							}
						},
						createOrder: function(data, actions) {
							paypal_order_id = false;
					
							return paypal_order_id;
						}
					}).then(function(hostedFieldsInstance) {
						hostedFieldsInstance.on('blur', function (event) {
							console.log('CCF Event "blur", state=' + hostedFieldsInstance.getState() + ', event=' + event);
						});
				
						hostedFieldsInstance.on('focus', function (event) {
							console.log('CCF Event "focus", state=' + hostedFieldsInstance.getState() + ', event=' + event);
						});

						hostedFieldsInstance.on('validityChange', function (event) {
							console.log('CCF Event "validityChange", state=' + hostedFieldsInstance.getState() + ',event=' + event);
					
							// Check if all fields are valid, then show submit button
							var formValid = Object.keys(event.fields).every(function (key) {
								return event.fields[key].isValid;
							});

							if (formValid) {
								$('#paypal_button_submit').addClass('show-button');
							} else {
								$('#paypal_button_submit').removeClass('show-button');
							}
						});

						hostedFieldsInstance.on('notEmpty', function (event) {
							console.log('CCF Event "notEmpty", state=' + hostedFieldsInstance.getState() + ', event=' + event);
						});
       
						hostedFieldsInstance.on('empty', function (event) {
							console.log('CCF Event "empty", state=' + hostedFieldsInstance.getState() + ',event=' + event);
            
							$(paypal_card_form).removeClass().addClass('well');
							$('#card_image').removeClass();
						});

						hostedFieldsInstance.on('cardTypeChange', function (event) {
							console.log('CCF Event "cardTypeChange", state=' + hostedFieldsInstance.getState() + ',event=' + event);
					
							$(paypal_card_form).removeClass().addClass('well');
							$('#card_image').removeClass();
					
							// Change card bg depending on card type
							if (event.cards.length === 1) {
								$(paypal_card_form).addClass(event.cards[0].type);
								$('#card_image').addClass(event.cards[0].type);
						
								// Change the CVV length for AmericanExpress cards
								if (event.cards[0].code.size === 4) {
									hostedFieldsInstance.setAttribute({
										field: 'cvv',
										attribute: 'placeholder',
										value: '####'
									});
								} else {
									hostedFieldsInstance.setAttribute({
										field: 'cvv',
										attribute: 'placeholder',
										value: '###'
									});
								}
							} else {												
								hostedFieldsInstance.setAttribute({
									field: 'cvv',
									attribute: 'placeholder',
									value: '###'
								});
							}
						});
					});
				} else {
					console.log('Not eligible for CCF');
				}
			} catch (error) {
				console.error('PayPal Card failed during startup', error);
			}
			
			$('#paypal_card_container').removeClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('messages') && $('#paypal_message_' + paypal_data['page_code']).length) {
			$('#paypal_message_' + paypal_data['page_code']).css('text-align', paypal_data['message_align']);
			
			if (paypal_data['message_width']) {
				$('#paypal_message_' + paypal_data['page_code'] + '_container').css('display', 'inline-block');
				$('#paypal_message_' + paypal_data['page_code'] + '_container').css('width', paypal_data['message_width']);
			} else {
				$('#paypal_message_' + paypal_data['page_code'] + '_container').css('display', 'block');
				$('#paypal_message_' + paypal_data['page_code'] + '_container').css('width', 'auto');
			}
			
			var paypal_message = document.createElement('div');
			
			paypal_message.setAttribute('data-pp-message', '');
						
			if (paypal_data['page_code'] == 'home') {
				paypal_message.setAttribute('data-pp-placement', 'home');
			}
			
			if (paypal_data['page_code'] == 'product') {
				paypal_message.setAttribute('data-pp-placement', 'product');
			}
			
			if (paypal_data['page_code'] == 'cart') {
				paypal_message.setAttribute('data-pp-placement', 'cart');
			}
			
			if (paypal_data['page_code'] == 'checkout') {
				paypal_message.setAttribute('data-pp-placement', 'payment');
			}
			
			paypal_message.setAttribute('data-pp-amount', '33.00');
			paypal_message.setAttribute('data-pp-style-layout', paypal_data['message_layout']);
			
			if (paypal_data['message_layout'] == 'text') {
				paypal_message.setAttribute('data-pp-style-text-color', paypal_data['message_text_color']);
				paypal_message.setAttribute('data-pp-style-text-size', paypal_data['message_text_size']);
			} else {
				paypal_message.setAttribute('data-pp-style-color', paypal_data['message_flex_color']);
				paypal_message.setAttribute('data-pp-style-ratio', paypal_data['message_flex_ratio']);
			}
			
			document.querySelector('#paypal_message_' + paypal_data['page_code'] + '_container').appendChild(paypal_message);
			
			$('#paypal_message_' + paypal_data['page_code'] + '_container').removeClass('paypal-spinner');
		}
			
		if (paypal_callback && typeof paypal_callback == 'function') {
			paypal_callback();
		}
	};
		
	var init = function(data, callback = '') {
		paypal_data = data;
		paypal_callback = callback;
			
		loadPayPalSDK();
	};
	
	return {
		init: init
	};
}());