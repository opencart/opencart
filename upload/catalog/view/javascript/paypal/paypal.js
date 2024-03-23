var PayPalAPI = (function () {
	var paypal_data = [];
	var paypal_script = [];
	var paypal_sdk = [];
	var paypal_callback;
	
	var showPayPalAlert = function(data) {
		$('.alert-dismissible').remove();
		
		if (data['error'] && data['error']['warning']) {
			if ($('#paypal_form').length) {
				$('#paypal_form').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + data['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} else {
				$('#content').parent().before('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + data['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('html, body').animate({scrollTop: 0}, 'slow');
			}
		}
	};
	
	var getQueryParams = function(url) {
		const param_arr = url.slice(url.indexOf('?') + 1).split('&');
		const params = {};
    
		param_arr.map(param => {
			const [key, val] = param.split('=');
			params[key] = decodeURIComponent(val);
		})
		
		return params;
	};
	
	var updatePayPalData = function() {
		var params = [];
		var script_file = document.getElementsByTagName('script');
		
		for (var i = 0; i < script_file.length; i++) {
			if (script_file[i].hasAttribute('src') && (script_file[i].getAttribute('src').indexOf('paypal.js') !== -1)) {
				params = getQueryParams(script_file[i].getAttribute('src'));
			
				break;
			}
		}
		
		paypal_data = params;
		
		if (paypal_data['page_code'] == 'product') {
			paypal_data['product'] = $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea').serialize();
		}
				
		$.ajax({
			method: 'post',
			url: 'index.php?route=extension/payment/paypal/getData',
			data: paypal_data,
			dataType: 'json',
			async: false,
			success: function(json) {						
				paypal_data = json;
				
				showPayPalAlert(json);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	};
	
	var readyPayPalSDK = function() {
		if (typeof PayPalSDK === 'undefined') {
			setTimeout(readyPayPalSDK, 100);
		} else {
			paypal_sdk[paypal_script.length - 1] = PayPalSDK;
			
			initPayPalSDK();
		}
	};
		
	var loadPayPalSDK = function() {				
		var html = '';
		
		if (paypal_data['components'].includes('buttons')) {
			if (!$('#paypal_button').length) {
				if ($(paypal_data['button_insert_tag']).length) {
					html = '<div id="paypal_button" class="paypal-button clearfix"><div id="paypal_button_container" class="paypal-button-container paypal-spinner"></div></div>';
			
					eval("$('" + paypal_data['button_insert_tag'] + "')." + paypal_data['button_insert_type'] + "(html)");
				}
			}
		}
		
		if (paypal_data['components'].includes('googlepay')) {
			if (!$('#googlepay_button').length) {
				if ($(paypal_data['googlepay_button_insert_tag']).length) {
					html = '<div id="googlepay_button" class="googlepay-button clearfix"><div id="googlepay_button_container" class="googlepay-button-container paypal-spinner"></div></div>';
			
					eval("$('" + paypal_data['googlepay_button_insert_tag'] + "')." + paypal_data['googlepay_button_insert_type'] + "(html)");
				}
			}
		}
		
		if (paypal_data['components'].includes('applepay')) {
			if (!$('#applepay_button').length) {
				if ($(paypal_data['applepay_button_insert_tag']).length) {
					html = '<div id="applepay_button" class="applepay-button clearfix"><div id="applepay_button_container" class="applepay-button-container paypal-spinner"></div></div>';
			
					eval("$('" + paypal_data['applepay_button_insert_tag'] + "')." + paypal_data['applepay_button_insert_type'] + "(html)");
				}
			}
		}
				
		if (paypal_data['components'].includes('messages')) {
			if (!$('#paypal_message').length) {
				if ($(paypal_data['message_insert_tag']).length) {
					html = '<div id="paypal_message" class="paypal-message"><div id="paypal_message_container" class="paypal-message-container paypal-spinner"></div></div>';
					
					eval("$('" + paypal_data['message_insert_tag'] + "')." + paypal_data['message_insert_type'] + "(html)");
				}
			}
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
			
			if (paypal_data['id_token']) {
				paypal_script[script_count].setAttribute('data-user-id-token', paypal_data['id_token']);
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
		if (paypal_data['components'].includes('buttons') && $('#paypal_button').length && !$('#paypal_button_container').html()) {
			$('#paypal_button').css('text-align', paypal_data['button_align']);
			
			if (paypal_data['button_width']) {
				$('#paypal_button_container').css('display', 'inline-block');
				$('#paypal_button_container').css('width', paypal_data['button_width']);
			} else {
				$('#paypal_button_container').css('display', 'block');
				$('#paypal_button_container').css('width', 'auto');
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
					},
					createOrder: function() {
						paypal_order_id = false;
						product_data = false;
						
						if (paypal_data['page_code'] == 'product') {
							product_data = $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea').serialize();
						}
				
						$.ajax({
							method: 'post',
							url: 'index.php?route=extension/payment/paypal/createOrder',
							data: {'page_code': paypal_data['page_code'], 'payment_type': 'button', 'product': product_data},
							dataType: 'json',
							async: false,
							success: function(json) {				
								showPayPalAlert(json);
								
								paypal_order_id = json['paypal_order_id'];
							},
							error: function(xhr, ajaxOptions, thrownError) {
								console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
											
						return paypal_order_id;	
					},
					onApprove: function(data) {				
						$.ajax({
							method: 'post',
							url: 'index.php?route=extension/payment/paypal/approveOrder',
							data: {'page_code': paypal_data['page_code'], 'payment_type': 'button', 'paypal_order_id': data.orderID},
							dataType: 'json',
							async: false,
							success: function(json) {					
								showPayPalAlert(json);
							
								if (json['url']) {
									location = json['url'];
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					}
				};
				
				if (paypal_data['button_funding_source']) {
					paypal_button_data['fundingSource'] = paypal_data['button_funding_source'];
				}
				
				var paypal_button = PayPalSDK.Buttons(paypal_button_data);
				
				if (paypal_button.isEligible()) {
					paypal_button.render('#paypal_button_container');
				}
			} catch (error) {
				console.error('PayPal Express failed during startup', error);
			}
						
			$('#paypal_button_container').removeClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('googlepay') && $('#googlepay_button').length && !$('#googlepay_button_container').html()) {
			if (google && PayPalSDK.Googlepay) {
				initGooglePaySDK().catch(console.log);
			} else {
				$('#googlepay_button').remove();
				
				console.log('This device does not support Google Pay');
			}
			
			$('#googlepay_button_container').removeClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('applepay') && $('#applepay_button').length && !$('#applepay_button_container').html()) {
			if (window.ApplePaySession && window.ApplePaySession?.supportsVersion(4) && ApplePaySession.canMakePayments()) {
				initApplePaySDK().catch(console.log);
			} else {
				$('#applepay_button').remove();
				
				console.log('This device does not support Apple Pay');
			}
		}
		
		if (paypal_data['card_customer_tokens'] && $('#paypal_card_tokens').length && !$('#paypal_card_tokens_container').html()) {
			$('#paypal_card_tokens').css('text-align', paypal_data['card_align']);
			
			if (paypal_data['card_width']) {
				$('#paypal_card_tokens_container').css('display', 'inline-block');
				$('#paypal_card_tokens_container').css('width', paypal_data['card_width']);
			} else {
				$('#paypal_card_tokens_container').css('display', 'block');
				$('#paypal_card_tokens_container').css('width', 'auto');
			}
			
			$.each(paypal_data['card_customer_tokens'], function(index, card_customer_token) {
				html = '<div class="paypal-card-token"><div type="button" class="btn card-token-button" index="' + index + '"><i class="card-icon card-icon-' + card_customer_token['card_type'] + '"></i><span class="card-number">' + card_customer_token['card_number'] + '</span></div><div type="button" class="btn card-token-delete-button" index="' + index + '"><i class="fa fal fa-close"></i></div></div></div>';
					
				$('#paypal_card_tokens_container').append(html);
			});
								
			$('#paypal_card_tokens_container').delegate('.card-token-button', 'click', function(event) {
				event.preventDefault();
	
				var paypal_card_token = $(this).parents('.paypal-card-token');
				var card_token_index = $(this).attr('index');
											
				paypal_order_id = false;
									
				$.ajax({
					method: 'post',
					url: 'index.php?route=extension/payment/paypal/createOrder',
					data: {'page_code': paypal_data['page_code'], 'payment_type': 'card', 'index': card_token_index},
					dataType: 'json',
					beforeSend: function() {
						paypal_card_token.addClass('paypal-spinner');
					},
					success: function(json) {							
						showPayPalAlert(json);
						
						if (json['url']) {
							location = json['url'];
						}
					},
					complete: function() {
						paypal_card_token.removeClass('paypal-spinner');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			});
			
			$('#paypal_card_tokens_container').delegate('.card-token-delete-button', 'click', function(event) {
				event.preventDefault();
						
				var paypal_card_token = $(this).parents('.paypal-card-token');
				var card_token_index = $(this).attr('index');
								
				$.ajax({
					method: 'post',
					url: 'index.php?route=extension/payment/paypal/deleteCustomerToken',
					data: {'index': card_token_index},
					dataType: 'json',
					beforeSend: function() {
						paypal_card_token.addClass('paypal-spinner');
					},
					success: function(json) {							
						showPayPalAlert(json);
									
						if (json['success']) {
							paypal_card_token.remove();
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					},
					complete: function() {
						paypal_card_token.removeClass('paypal-spinner');
					}
				});
			});	
		}
		
		if (paypal_data['components'].includes('card-fields') && $('#paypal_card').length && !$('#paypal_card_form').find('iframe').length) {
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
						
						var card_save = ($('#paypal_card_form #paypal_card_save:checked').length ? $('#paypal_card_form #paypal_card_save:checked').val() : 0);
					
						$.ajax({
							method: 'post',
							url: 'index.php?route=extension/payment/paypal/createOrder',
							data: {'page_code': paypal_data['page_code'], 'payment_type': 'card', 'card_save': card_save},
							dataType: 'json',
							async: false,
							success: function(json) {							
								showPayPalAlert(json);
									
								paypal_order_id = json['paypal_order_id'];
							},
							error: function(xhr, ajaxOptions, thrownError) {
								console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					
						return paypal_order_id;
					},
					onApprove: function(data) {				
						var card_save = ($('#paypal_card_form #paypal_card_save:checked').length ? $('#paypal_card_form #paypal_card_save:checked').val() : 0);
						var card_type = $('#paypal_card_form').attr('card_type');
						var card_nice_type = $('#paypal_card_form').attr('card_nice_type');
								
						$.ajax({
							method: 'post',
							url: 'index.php?route=extension/payment/paypal/approveOrder',
							data: {'page_code': paypal_data['page_code'], 'payment_type': 'card', 'card_save': card_save, 'card_type': card_type, 'card_nice_type': card_nice_type, 'paypal_order_id': data.orderID},
							dataType: 'json',
							async: false,
							success: function(json) {				
								showPayPalAlert(json);
								
								if (json['url']) {
									location = json['url'];
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							},
							complete: function() {
								$('#paypal_card_container').removeClass('paypal-spinner');
							}
						});
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
					
					var paypal_card_button = document.querySelector('#paypal_card_button');
 
					paypal_card_button.addEventListener('click', function(event) {
						event.preventDefault();
						
						if ($('#paypal_card_button').hasClass('show-button')) {
							console.log('CCF Event "click", state=' + paypal_card.getState() + ', event=' + event);

							$('#paypal_card_container').addClass('paypal-spinner');
							
							paypal_card.submit().then(function() {
								console.log('PayPal CCF submitted:', paypal_card);
							}).catch(function(error) {
								console.error('PayPal CCF submit erred:', error);
							});
						}
					}, false);
				} else {
					console.log('Not eligible for CCF');
				}
			} catch (error) {
				console.error('PayPal Card failed during startup', error);
			}
						
			$('#paypal_card_container').removeClass('paypal-spinner');
		}
		
		if (paypal_data['components'].includes('messages') && $('#paypal_message').length && !$('#paypal_message_container').html()) {						
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
			
			paypal_message.setAttribute('data-pp-amount', paypal_data['message_amount']);
			paypal_message.setAttribute('data-pp-style-layout', paypal_data['message_layout']);
			
			if (paypal_data['message_layout'] == 'text') {
				paypal_message.setAttribute('data-pp-style-logo-type', paypal_data['message_logo_type']);
				paypal_message.setAttribute('data-pp-style-logo-position', paypal_data['message_logo_position']);
				paypal_message.setAttribute('data-pp-style-text-color', paypal_data['message_text_color']);
				paypal_message.setAttribute('data-pp-style-text-size', paypal_data['message_text_size']);
			} else {
				paypal_message.setAttribute('data-pp-style-color', paypal_data['message_flex_color']);
				paypal_message.setAttribute('data-pp-style-ratio', paypal_data['message_flex_ratio']);
			}
			
			document.querySelector('#paypal_message_container').appendChild(paypal_message);
						
			$('#paypal_message_container').removeClass('paypal-spinner');
			
			if (paypal_data['page_code'] == 'product') {
				$('[name^="option"], [name="quantity"]').on('change', function(event) {
					setTimeout(function() {
						updatePayPalData();
		
						if (paypal_message) {
							paypal_message.setAttribute('data-pp-amount', paypal_data['message_amount']);
						}
					}, 10);
				});
			}
		}
			
		if (paypal_callback && typeof paypal_callback == 'function') {
			paypal_callback();
		}
	};
	
	var initGooglePaySDK = async function() {
		const {allowedPaymentMethods, merchantInfo, apiVersion, apiVersionMinor, countryCode} = await PayPalSDK.Googlepay().config();
		
		const paymentsClient = new google.payments.api.PaymentsClient({
			environment: paypal_data['googlepay_environment'],
			paymentDataCallbacks: {
				onPaymentAuthorized: function(paymentData) {
					return new Promise(async function(resolve, reject) {
						try {
							paypal_order_id = false;
							product_data = false;
						
							if (paypal_data['page_code'] == 'product') {
								product_data = $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea').serialize();
							}
							
							$.ajax({
								method: 'post',
								url: 'index.php?route=extension/payment/paypal/createOrder',
								data: {'page_code': paypal_data['page_code'], 'payment_type': 'googlepay_button', 'product': product_data},
								dataType: 'json',
								async: false,
								success: function(json) {				
									showPayPalAlert(json);
								
									paypal_order_id = json['paypal_order_id'];
								},
								error: function(xhr, ajaxOptions, thrownError) {
									console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
							
							const confirmOrderResponse = await PayPalSDK.Googlepay().confirmOrder({
								orderId: paypal_order_id,
								paymentMethodData: paymentData.paymentMethodData
							});
							
							if (confirmOrderResponse.status === 'PAYER_ACTION_REQUIRED') {
								console.log('Confirm Payment Completed Payer Action Required');
								
								PayPalSDK.Googlepay().initiatePayerAction({orderId: paypal_order_id}).then(async () => {
									$.ajax({
										method: 'post',
										url: 'index.php?route=extension/payment/paypal/approveOrder',
										data: {'page_code': paypal_data['page_code'], 'payment_type': 'googlepay_button', 'paypal_order_id': paypal_order_id},
										dataType: 'json',
										async: false,
										success: function(json) {					
											showPayPalAlert(json);
													
											if (json['url']) {
												location = json['url'];
											}
										},
										error: function(xhr, ajaxOptions, thrownError) {
											console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
										}
									});	
								});
								
								resolve({transactionState: 'SUCCESS'});	
							} else if (confirmOrderResponse.status === 'APPROVED') {
								console.log('Confirm Payment Approved');
								
								$.ajax({
									method: 'post',
									url: 'index.php?route=extension/payment/paypal/approveOrder',
									data: {'page_code': paypal_data['page_code'], 'payment_type': 'googlepay_button', 'paypal_order_id': paypal_order_id},
									dataType: 'json',
									async: false,
									success: function(json) {					
										showPayPalAlert(json);
													
										if (json['url']) {
											location = json['url'];
										}
									},
									error: function(xhr, ajaxOptions, thrownError) {
										console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								});	
								
								resolve({transactionState: 'SUCCESS'});	
							} else {
								resolve({
									transactionState: 'ERROR',
									error: {
										intent: 'PAYMENT_AUTHORIZATION',
										message: 'TRANSACTION FAILED',
									}
								});
							}
						} catch (error) {
							resolve({
								transactionState: 'ERROR',
								error: {
									intent: 'PAYMENT_AUTHORIZATION',
									message: error.message,
								}
							});
						}
					});
				}
			}
		});
		   		
		paymentsClient.isReadyToPay({allowedPaymentMethods, apiVersion, apiVersionMinor}).then(function(response) {
			if (response.result) {							
				$('#googlepay_button').css('text-align', paypal_data['googlepay_button_align']);
				
				var googlepay_button_style = [];
					
				if (paypal_data['googlepay_button_width']) {
					googlepay_button_style.push('display: inline-block');
					googlepay_button_style.push('width: ' + paypal_data['googlepay_button_width']);
				} else {
					googlepay_button_style.push('display: block');
					googlepay_button_style.push('width: auto');
				}
						
				googlepay_button_style.push('aspect-ratio: 7.5');
									
				$('#googlepay_button_container').attr('style', googlepay_button_style.join('; '));
				
				if (paypal_data['googlepay_button_shape'] == 'pill') {
					$('#googlepay_button_container').removeClass('shape-rect');
					$('#googlepay_button_container').addClass('shape-pill');
				} else {
					$('#googlepay_button_container').removeClass('shape-pill');
					$('#googlepay_button_container').addClass('shape-rect');
				}
							
				const googlepay_button = paymentsClient.createButton({
					buttonColor: paypal_data['googlepay_button_color'],
					buttonType: paypal_data['googlepay_button_type'],
					buttonLocale: paypal_data['locale'].split('_')[0],
					buttonSizeMode: 'fill',
					onClick: function() {
						const paymentDataRequest = Object.assign({}, {apiVersion, apiVersionMinor});
						
						paymentDataRequest.allowedPaymentMethods = allowedPaymentMethods;
						paymentDataRequest.transactionInfo = {
							countryCode: countryCode,
							currencyCode: paypal_data['currency_code'],
							totalPriceStatus: 'FINAL',
							totalPrice: paypal_data['googlepay_amount'],
							totalPriceLabel: 'Total'
						}
						
						paymentDataRequest.merchantInfo = merchantInfo;

						paymentDataRequest.callbackIntents = ['PAYMENT_AUTHORIZATION'];
					
						paymentsClient.loadPaymentData(paymentDataRequest);
					}
				});
				
				document.querySelector('#googlepay_button_container').appendChild(googlepay_button);
			}
		}).catch(function(error) {
			console.log(error);
		});
	};
	
	var initApplePaySDK = async function() {
		const ApplePaySDK = PayPalSDK.Applepay();
		const {
			isEligible,
			countryCode,
			currencyCode,
			merchantCapabilities,
			supportedNetworks,
		} = await ApplePaySDK.config();

		if (!isEligible) {
			$('#applepay_button').remove();
			
			throw 'Apple Pay is not eligible';
		}
		
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

		applepay_button.addEventListener('click', async function (event) {
			event.preventDefault();
			
			const paymentRequest = {
				countryCode,
				currencyCode: paypal_data['currency_code'],
				merchantCapabilities,
				supportedNetworks,
				requiredBillingContactFields: ['name', 'phone', 'email', 'postalAddress'],
				requiredShippingContactFields: [],
				total: {
					label: 'Total',
					amount: paypal_data['applepay_amount'],
					type: 'final',
				}
			};
					
			var session = new ApplePaySession(4, paymentRequest);
			
			session.onvalidatemerchant = (event) => {
				ApplePaySDK.validateMerchant({
					validationUrl: event.validationURL,
				}).then((payload) => {
					session.completeMerchantValidation(payload.merchantSession);
					console.log('Complete Merchant validation Done!', {payload});
				}).catch((error) => {
					console.error(error);
					session.abort();
				});
			};
			
			session.onpaymentmethodselected = (event) => {
				console.log('onpaymentmethodselected');
				console.log(event.paymentMethod); // {type: "credit"}

				session.completePaymentMethodSelection({
					newTotal: paymentRequest.total
				});
			};
			
			session.onshippingcontactselected = async (event) => {
				console.log('Your shipping contacts selected is: ' + event.shippingContact);
				
				const shippingContactUpdate = { 
					newTotal: paymentRequest.total,
					newLineItems: []
				};
				
				session.completeShippingContactSelection(shippingContactUpdate);
			};
			
			session.onshippingmethodselected = (event) => {
				console.log('onshippingmethodselected');
				console.log(JSON.stringify(event.shippingMethod, null, 4));
				console.log('Your shipping method selected is: ', event.shippingMethod);
      
				var shippingMethodUpdate = { 
					newTotal: paymentRequest.total,
					newLineItems: []
				}; 
				
				session.completeShippingMethodSelection(shippingMethodUpdate);
			};
			
			session.onpaymentauthorized = async (event) => {
				try {
					console.log('onpaymentauthorized');
					console.log(JSON.stringify(event, null, 4));
										
					paypal_order_id = false;
					product_data = false;
						
					if (paypal_data['page_code'] == 'product') {
						product_data = $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea').serialize();
					}
						
					$.ajax({
						method: 'post',
						url: 'index.php?route=extension/payment/paypal/createOrder',
						data: {'page_code': paypal_data['page_code'], 'payment_type': 'applepay_button', 'product': product_data},
						dataType: 'json',
						async: false,
						success: function(json) {				
							showPayPalAlert(json);
								
							paypal_order_id = json['paypal_order_id'];
						},
						error: function(xhr, ajaxOptions, thrownError) {
							console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
																	
					await ApplePaySDK.confirmOrder({
						orderId: paypal_order_id, 
						token: event.payment.token, 
						billingContact: event.payment.billingContact, 
						shippingContact: event.payment.shippingContact 
					});
				
					$.ajax({
						method: 'post',
						url: 'index.php?route=extension/payment/paypal/approveOrder',
						data: {'page_code': paypal_data['page_code'], 'payment_type': 'applepay_button', 'paypal_order_id': paypal_order_id},
						dataType: 'json',
						async: false,
						success: function(json) {					
							showPayPalAlert(json);
													
							if (json['url']) {
								location = json['url'];
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
						
					session.completePayment({
						status: window.ApplePaySession.STATUS_SUCCESS
					});
				} catch (error) {
					console.error(error);
					
					session.completePayment({
						status: window.ApplePaySession.STATUS_FAILURE
					});
				}
			};
			
			session.oncancel = (event) => {
				console.log(event);
				console.log('Apple Pay Cancelled!');
			}

			session.begin();
		});
									
		$('#applepay_button_container').removeClass('paypal-spinner');
	};
	
	var init = function(callback = '') {
		updatePayPalData();
		
		paypal_callback = callback;
			
		loadPayPalSDK();
	};
	
	return {
		init: init
	};
}());

window.addEventListener('load', function () {
	PayPalAPI.init();
});

