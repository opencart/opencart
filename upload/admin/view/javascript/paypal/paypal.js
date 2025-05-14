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
		
		if (paypal_data['components'].includes('googlepay')) {
			$('#googlepay_button_' + paypal_data['page_code'] + '_container').html('');
			$('#googlepay_button_' + paypal_data['page_code'] + '_container').addClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('applepay')) {
			$('#applepay_button_' + paypal_data['page_code'] + '_container').html('');
			$('#applepay_button_' + paypal_data['page_code'] + '_container').addClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('card-fields')) {
			$('#paypal_card_container').find('iframe').parent().remove();
			$('#paypal_card_container').addClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('fastlane')) {
			$('#fastlane_card_container').addClass('paypal-spinner');
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
			paypal_script[script_count].setAttribute('data-namespace', 'PayPalSDK');
			
			if (paypal_data['client_token']) {
				paypal_script[script_count].setAttribute('data-client-token', paypal_data['client_token']);
			} 
			
			if (paypal_data['sdk_client_token']) {
				paypal_script[script_count].setAttribute('data-sdk-client-token', paypal_data['sdk_client_token']);
			}
			
			if (paypal_data['client_metadata_id']) {
				paypal_script[script_count].setAttribute('data-client-metadata-id', paypal_data['client_metadata_id']);
			}		
			
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
						layout: 'vertical',
						size: paypal_data['button_size'],
						color: paypal_data['button_color'],
						shape: paypal_data['button_shape'],
						label: paypal_data['button_label'],
						tagline: 'false'
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
				
		if (paypal_data['components'].includes('googlepay') && $('#googlepay_button_' + paypal_data['page_code']).length && !$('#googlepay_button_' + paypal_data['page_code'] + '_container').html()) {
			if (google && PayPalSDK.Googlepay) {
				initGooglePaySDK().catch(console.log);
			}
			
			$('#googlepay_button_' + paypal_data['page_code'] + '_container').removeClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('applepay') && $('#applepay_button_' + paypal_data['page_code']).length && !$('#applepay_button_' + paypal_data['page_code'] + '_container').html()) {
			$('#applepay_button_' + paypal_data['page_code']).css('text-align', paypal_data['applepay_button_align']);
			
			var applepay_button_style = [];
			
			if (paypal_data['applepay_button_width']) {
				$('#applepay_button_' + paypal_data['page_code'] + '_container').css('display', 'inline-block');
				$('#applepay_button_' + paypal_data['page_code'] + '_container').css('width', paypal_data['applepay_button_width']);
			} else {
				$('#applepay_button_' + paypal_data['page_code'] + '_container').css('display', 'block');
				$('#applepay_button_' + paypal_data['page_code'] + '_container').css('width', 'auto');
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
															
			document.querySelector('#applepay_button_' + paypal_data['page_code'] + '_container').appendChild(applepay_button);			
									
			$('#applepay_button_' + paypal_data['page_code'] + '_container').removeClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('card-fields') && $('#paypal_card').length) {
			$('#paypal_card').css('text-align', paypal_data['card_align']);
						
			if (paypal_data['card_width']) {
				$('#paypal_card_container').css('display', 'inline-block');
				$('#paypal_card_container').css('width', paypal_data['card_width']);
			} else {
				$('#paypal_card_container').css('display', 'block');
				$('#paypal_card_container').css('width', 'auto');
			}
			
			try {
				var paypal_card = PayPalSDK.CardFields({
					style: {
						'input': {
							'font-size': '1rem',
							'color': '#282c37',
							'transition': 'color 0.1s',
							'padding': '0.75rem 0.75rem',
						}
					},
					createOrder: function() {
						paypal_order_id = false;
											
						return paypal_order_id;
					},
					inputEvents: {
						onChange: function(data) {
							console.log('CCF Event "change", state=' + paypal_card.getState() + ', event=' + data);
							
							$('#paypal_card_form').removeClass().addClass('well');
							$('#paypal_card_form').removeAttr();
														
							if (data.cards.length === 1) {
								$('#paypal_card_form').addClass(data.cards[0].type);
								$('#paypal_card_form').attr('card_type', data.cards[0].type);
								$('#paypal_card_form').attr('card_nice_type', data.cards[0].niceType);
							}

							if (data.isFormValid) {
								$('#paypal_card_button').addClass('show-button');
							} else {
								$('#paypal_card_button').removeClass('show-button');
							}
						},
						onFocus: function(data) {
							console.log('CCF Event "focus", state=' + paypal_card.getState() + ', event=' + data);
						},
						onBlur: function(data) {
							console.log('CCF Event "blur", state=' + paypal_card.getState() + ', event=' + data);
						},
						onInputSubmitRequest: function(data) {
							console.log('CCF Event "input submit request", state=' + paypal_card.getState() + ', event=' + data);
								
							if (data.isFormValid) {
								$('#paypal_card_button').addClass('show-button');
							} else {
								$('#paypal_card_button').removeClass('show-button');
							}
						}
					}
				});
				
				if (paypal_card.isEligible()) {
					paypal_card.NameField().render('#card_holder_name');
					paypal_card.NumberField().render('#card_number');
					paypal_card.ExpiryField().render('#expiration_date');
					paypal_card.CVVField().render('#cvv');
				} else {
					console.log('Not eligible for CCF');
				}
			} catch (error) {
				console.error('PayPal Card failed during startup', error);
			}
							
			$('#paypal_card_container').removeClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('fastlane') && $('#fastlane_card').length) {
			$('#fastlane_card').css('text-align', paypal_data['fastlane_card_align']);
			
			if (paypal_data['fastlane_card_width']) {
				$('#fastlane_card_container').css('display', 'inline-block');
				$('#fastlane_card_container').css('width', paypal_data['fastlane_card_width']);
			} else {
				$('#fastlane_card_container').css('display', 'block');
				$('#fastlane_card_container').css('width', 'auto');
			}
			
			$('#fastlane_card_container').removeClass('paypal-spinner');
			
			initFastlaneSDK().catch(console.log);
		}
											
		if (paypal_callback && typeof paypal_callback == 'function') {
			paypal_callback();
		}
	};
	
	var initFastlaneSDK = async function() {
		var fastlane = await PayPalSDK.Fastlane({});
		
		var {identity, profile, FastlanePaymentComponent, FastlaneCardComponent, FastlaneWatermarkComponent} = fastlane;

		window.localStorage.setItem('fastlaneEnv', 'sandbox');

		fastlane.setLocale(paypal_data['locale']);
		
		var shippingAddress = false;
		var billingAddress = false;
		var fastlaneCardComponent = false;
								
		if ($('#fastlane_card_form_container').length && !$('#fastlane_card_form_container').html()) {			
			var options = {
				styles: {
					root: {
						backgroundColor: '#FFFFFF'
					}
				}
			};
					
			fastlaneCardComponent = await FastlaneCardComponent({options});
			
			fastlaneCardComponent.render('#fastlane_card_form_container');
		}
	};
	
	var initGooglePaySDK = async function() {
		const {allowedPaymentMethods, merchantInfo, apiVersion, apiVersionMinor, countryCode} = await PayPalSDK.Googlepay().config();
		
		const paymentsClient = new google.payments.api.PaymentsClient({
			environment: 'TEST'
		});
		   		
		paymentsClient.isReadyToPay({allowedPaymentMethods, apiVersion, apiVersionMinor}).then(function(response) {
			if (response.result) {							
				$('#googlepay_button_' + paypal_data['page_code']).css('text-align', paypal_data['googlepay_button_align']);
				
				var googlepay_button_style = [];
				
				if ((paypal_data['googlepay_button_width'] == '200px') && (paypal_data['googlepay_button_type'] == 'buy')) {
					paypal_data['googlepay_button_width'] = '250px';
				}
					
				if (paypal_data['googlepay_button_width']) {
					googlepay_button_style.push('display: inline-block');
					googlepay_button_style.push('width: ' + paypal_data['googlepay_button_width']);
				} else {
					googlepay_button_style.push('display: block');
					googlepay_button_style.push('width: auto');
				}
						
				googlepay_button_style.push('aspect-ratio: 7.5');
									
				$('#googlepay_button_' + paypal_data['page_code'] + '_container').attr('style', googlepay_button_style.join('; '));
				
				if (paypal_data['googlepay_button_shape'] == 'pill') {
					$('#googlepay_button_' + paypal_data['page_code'] + '_container').removeClass('shape-rect');
					$('#googlepay_button_' + paypal_data['page_code'] + '_container').addClass('shape-pill');
				} else {
					$('#googlepay_button_' + paypal_data['page_code'] + '_container').removeClass('shape-pill');
					$('#googlepay_button_' + paypal_data['page_code'] + '_container').addClass('shape-rect');
				}
								
				const googlepay_button = paymentsClient.createButton({
					buttonColor: paypal_data['googlepay_button_color'],
					buttonType: paypal_data['googlepay_button_type'],
					buttonLocale: paypal_data['locale'].split('_')[0],
					buttonSizeMode: 'fill',
					onClick: function() {}
				});
				
				document.querySelector('#googlepay_button_' + paypal_data['page_code'] + '_container').appendChild(googlepay_button);
			}
		}).catch(function(error) {
			console.log(error);
		});
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