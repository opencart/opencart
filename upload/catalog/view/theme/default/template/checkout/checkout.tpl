<?php echo $header; ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<div class="row"><?php echo $column_left; ?>
  <div id="content" class="span12"><?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?></h1>
    <div class="accordion" id="accordion">
      <div class="accordion-group">
        <div class="accordion-heading"><a href="#collapse-checkout-option" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_option; ?> <i class="icon-caret-down"></i></a></div>
        <div id="collapse-checkout-option" class="accordion-body collapse">
          <div class="accordion-inner"></div>
        </div>
      </div>
      <?php if (!$logged) { ?>
      <div class="accordion-group">
        <div class="accordion-heading"><a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_account; ?> <i class="icon-caret-down"></i></a></div>
        <div id="collapse-payment-address" class="accordion-body collapse">
          <div class="accordion-inner"></div>
        </div>
      </div>
      <?php } else { ?>
      <div class="accordion-group">
        <div class="accordion-heading"><a href="#collapse-payment-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_address; ?> <i class="icon-caret-down"></i></a></div>
        <div id="collapse-payment-address" class="accordion-body collapse">
          <div class="accordion-inner"></div>
        </div>
      </div>
      <?php } ?>
      <?php if ($shipping_required) { ?>
      <div class="accordion-group">
        <div class="accordion-heading"><a href="#collapse-shipping-address" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_address; ?> <i class="icon-caret-down"></i></a></div>
        <div id="collapse-shipping-address" class="accordion-body collapse">
          <div class="accordion-inner"></div>
        </div>
      </div>
      <div class="accordion-group">
        <div class="accordion-heading"><a href="#collapse-shipping-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_shipping_method; ?> <i class="icon-caret-down"></i></a></div>
        <div id="collapse-shipping-method" class="accordion-body collapse">
          <div class="accordion-inner"></div>
        </div>
      </div>
      <?php } ?>
      <div class="accordion-group">
        <div class="accordion-heading"><a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="icon-caret-down"></i></a></div>
        <div id="collapse-payment-method" class="accordion-body collapse">
          <div class="accordion-inner"></div>
        </div>
      </div>
      <div class="accordion-group">
        <div class="accordion-heading"><a href="#collapse-checkout-confirm" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_confirm; ?> <i class="icon-caret-down"></i></a></div>
        <div id="collapse-checkout-confirm" class="accordion-body collapse">
          <div class="accordion-inner"></div>
        </div>
      </div>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $column_right; ?>
</div>
<script type="text/javascript"><!--
$(document).on('change', 'input[name=\'account\']', function() {
    if (this.value == 'register') {
        $('#collapse-payment-address').parent().find('.accordion-heading a').html('<?php echo $text_checkout_account; ?> <i class="icon-caret-down"></i>');
    } else {
        $('#collapse-payment-address').parent().find('.accordion-heading a').html('<?php echo $text_checkout_payment_address; ?> <i class="icon-caret-down"></i>');
    }
});

<?php if (!$logged) { ?> 
$(document).ready(function() {
    $.ajax({
        url: 'index.php?route=checkout/login',
        dataType: 'html',
        success: function(html) {
            $('#collapse-checkout-option .accordion-inner').html(html);
                
            $('#collapse-checkout-option').collapse('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }); 
});     
<?php } else { ?>
$(document).ready(function() {
    $.ajax({
        url: 'index.php?route=checkout/payment_address',
        dataType: 'html',
        success: function(html) {
            $('#collapse-payment-address .accordion-inner').html(html);
                
            $('#collapse-payment-address').collapse('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }); 
});
<?php } ?>

// Checkout
$(document).on('click', '#button-account', function() {
    $.ajax({
        url: 'index.php?route=checkout/' + $('input[name=\'account\']:checked').val(),
        dataType: 'html',
        beforeSend: function() {
        	$('#button-account').button('loading');
		},      
        complete: function() {
			$('#button-account').button('reset');
        },          
        success: function(html) {
            $('.alert').remove();
            
            $('#collapse-payment-address .accordion-inner').html(html);
			
			$('#collapse-payment-address').collapse('show');

			$('#collapse-checkout-option').collapse('hide');
           // $('.checkout-heading a').remove();
                
           // $('#checkout .checkout-heading').append('');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Login
$(document).on('click', '#button-login', function() {
    $.ajax({
        url: 'index.php?route=checkout/login/save',
        type: 'post',
        data: $('#collapse-checkout-option #login :input'),
        dataType: 'json',
        beforeSend: function() {
        	$('#button-login').button('loading');
		},  
        complete: function() {
            $('#button-login').button('reset');
        },              
        success: function(json) {
            $('.alert').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                $('#collapse-checkout-option .accordion-inner').prepend('<div class="alert alert-error"><i class="icon-exclamation-sign"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
           }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }); 
});

// Register
$(document).on('click', '#button-register', function() {
    $.ajax({
        url: 'index.php?route=checkout/register/save',
        type: 'post',
        data: $('#collapse-payment-address input[type=\'text\'], #collapse-payment-address input[type=\'password\'], #collapse-payment-address input[type=\'checkbox\']:checked, #collapse-payment-address input[type=\'radio\']:checked, #collapse-payment-address input[type=\'hidden\'], #collapse-payment-address select'),
        dataType: 'json',
        beforeSend: function() {
			$('#button-register').button('loading');
		},  
        complete: function() {
            $('#button-register').button('reset');
        },          
        success: function(json) {
            $('.alert').remove();
                        
            if (json['redirect']) {
                location = json['redirect'];                
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#collapse-payment-address .accordion-inner').prepend('<div class="alert alert-error"><i class="icon-exclamation-sign"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
                
				for (i in json['error']) {
                    $('#input-payment-' + i.replace('_', '-')).after('<span class="alert alert-error">' + json['error'][i] + '</span>');
				}
            } else {
                <?php if ($shipping_required) { ?>              
                var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').prop('value');
                
                if (shipping_address) {
                    $.ajax({
                        url: 'index.php?route=checkout/shipping_method',
                        dataType: 'html',
                        success: function(html) {
                            $('#shipping-method .checkout-content').html(html);
                            
                            $('#payment-address .checkout-content').slideUp('slow');
                            
                            $('#shipping-method .checkout-content').slideDown('slow');
                            
                            $('#checkout .checkout-heading a').remove();
                            $('#payment-address .checkout-heading a').remove();
                            $('#shipping-address .checkout-heading a').remove();
                            $('#shipping-method .checkout-heading a').remove();
                            $('#payment-method .checkout-heading a').remove();                                          
                            
                            $('#shipping-address .checkout-heading').append('');                                  
                            $('#payment-address .checkout-heading').append('');   

                            $.ajax({
                                url: 'index.php?route=checkout/shipping_address',
                                dataType: 'html',
                                success: function(html) {
                                    $('#shipping-address .checkout-content').html(html);
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            }); 
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    }); 
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/shipping_address',
                        dataType: 'html',
                        success: function(html) {
                            $('#shipping-address .checkout-content').html(html);
                            
                            $('#payment-address .checkout-content').slideUp('slow');
                            
                            $('#shipping-address .checkout-content').slideDown('slow');
                            
                            $('#checkout .checkout-heading a').remove();
                            $('#payment-address .checkout-heading a').remove();
                            $('#shipping-address .checkout-heading a').remove();
                            $('#shipping-method .checkout-heading a').remove();
                            $('#payment-method .checkout-heading a').remove();                          

                            $('#payment-address .checkout-heading').append('');   
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });         
                }
                <?php } else { ?>
                $.ajax({
                    url: 'index.php?route=checkout/payment_method',
                    dataType: 'html',
                    success: function(html) {
                        $('#payment-method .checkout-content').html(html);
                        
                        $('#payment-address .checkout-content').slideUp('slow');
                        
                        $('#payment-method .checkout-content').slideDown('slow');
                        
                        $('#checkout .checkout-heading a').remove();
                        $('#payment-address .checkout-heading a').remove();
                        $('#payment-method .checkout-heading a').remove();                              
                        
                        $('#payment-address .checkout-heading').append('');   
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });                 
                <?php } ?>

                $.ajax({
                    url: 'index.php?route=checkout/payment_address',
                    dataType: 'html',
                    success: function(html) {
                        $('#payment-address .checkout-content').html(html);
                            
                        $('#payment-address .checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
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
$(document).on('click', '#button-payment-address', function() {
    $.ajax({
        url: 'index.php?route=checkout/payment_address/save',
        type: 'post',
        data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
        dataType: 'json',
        beforeSend: function() {
        	$('#button-payment-address').button('loading');
		},  
        complete: function() {
			$('#button-payment-address').button('reset');
        },          
        success: function(json) {
            $('.alert').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#payment-address .checkout-content').prepend('<div class="alert alert-warning" style="display: none;"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error']['warning'] + '</div>');
                    
                    $('.alert-warning').fadeIn('slow');
                }
                                
                if (json['error']['firstname']) {
                    $('#payment-address input[name=\'firstname\']').after('<span class="alert alert-error">' + json['error']['firstname'] + '</span>');
                }
                
                if (json['error']['lastname']) {
                    $('#payment-address input[name=\'lastname\']').after('<span class="alert alert-error">' + json['error']['lastname'] + '</span>');
                }   
                
                if (json['error']['telephone']) {
                    $('#payment-address input[name=\'telephone\']').after('<span class="alert alert-error">' + json['error']['telephone'] + '</span>');
                }       
                
                if (json['error']['company_id']) {
                    $('#payment-address input[name=\'company_id\']').after('<span class="alert alert-error">' + json['error']['company_id'] + '</span>');
                }   
                
                if (json['error']['tax_id']) {
                    $('#payment-address input[name=\'tax_id\']').after('<span class="alert alert-error">' + json['error']['tax_id'] + '</span>');
                }   
                                                        
                if (json['error']['address_1']) {
                    $('#payment-address input[name=\'address_1\']').after('<span class="alert alert-error">' + json['error']['address_1'] + '</span>');
                }   
                
                if (json['error']['city']) {
                    $('#payment-address input[name=\'city\']').after('<span class="alert alert-error">' + json['error']['city'] + '</span>');
                }   
                
                if (json['error']['postcode']) {
                    $('#payment-address input[name=\'postcode\']').after('<span class="alert alert-error">' + json['error']['postcode'] + '</span>');
                }   
                
                if (json['error']['country']) {
                    $('#payment-address select[name=\'country_id\']').after('<span class="alert alert-error">' + json['error']['country'] + '</span>');
                }   
                
                if (json['error']['zone']) {
                    $('#payment-address select[name=\'zone_id\']').after('<span class="alert alert-error">' + json['error']['zone'] + '</span>');
                }
            } else {
                <?php if ($shipping_required) { ?>
                $.ajax({
                    url: 'index.php?route=checkout/shipping_address',
                    dataType: 'html',
                    success: function(html) {
                        $('#shipping-address .checkout-content').html(html);
                    
                        $('#payment-address .checkout-content').slideUp('slow');
                        
                        $('#shipping-address .checkout-content').slideDown('slow');
                        
                        $('#payment-address .checkout-heading a').remove();
                        $('#shipping-address .checkout-heading a').remove();
                        $('#shipping-method .checkout-heading a').remove();
                        $('#payment-method .checkout-heading a').remove();
                        
                        $('#payment-address .checkout-heading').append('');   
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
                <?php } else { ?>
                $.ajax({
                    url: 'index.php?route=checkout/payment_method',
                    dataType: 'html',
                    success: function(html) {
                        $('#payment-method .checkout-content').html(html);
                    
                        $('#payment-address .checkout-content').slideUp('slow');
                        
                        $('#payment-method .checkout-content').slideDown('slow');
                        
                        $('#payment-address .checkout-heading a').remove();
                        $('#payment-method .checkout-heading a').remove();
                                                    
                        $('#payment-address .checkout-heading').append('');   
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                }); 
                <?php } ?>
                
                $.ajax({
                    url: 'index.php?route=checkout/payment_address',
                    dataType: 'html',
                    success: function(html) {
                        $('#payment-address .checkout-content').html(html);
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

// Shipping Address         
$(document).on('click', '#button-shipping-address', function() {
    $.ajax({
        url: 'index.php?route=checkout/shipping_address/validate',
        type: 'post',
        data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select'),
        dataType: 'json',
        beforeSend: function() {
			$('#button-shipping-address').button('loading');
	    },  
        complete: function() {
			$('#button-shipping-address').button('reset');
        },          
        success: function(json) {
            $('.alert-warning, .alert-error').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#shipping-address .checkout-content').prepend('<div class="alert alert-warning" style="display: none;"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error']['warning'] + '</div>');
                    
                    $('.alert-warning').fadeIn('slow');
                }
                                
                if (json['error']['firstname']) {
                    $('#shipping-address input[name=\'firstname\']').after('<span class="alert alert-error">' + json['error']['firstname'] + '</span>');
                }
                
                if (json['error']['lastname']) {
                    $('#shipping-address input[name=\'lastname\']').after('<span class="alert alert-error">' + json['error']['lastname'] + '</span>');
                }   
                
                if (json['error']['email']) {
                    $('#shipping-address input[name=\'email\']').after('<span class="alert alert-error">' + json['error']['email'] + '</span>');
                }
                
                if (json['error']['telephone']) {
                    $('#shipping-address input[name=\'telephone\']').after('<span class="alert alert-error">' + json['error']['telephone'] + '</span>');
                }       
                                        
                if (json['error']['address_1']) {
                    $('#shipping-address input[name=\'address_1\']').after('<span class="alert alert-error">' + json['error']['address_1'] + '</span>');
                }   
                
                if (json['error']['city']) {
                    $('#shipping-address input[name=\'city\']').after('<span class="alert alert-error">' + json['error']['city'] + '</span>');
                }   
                
                if (json['error']['postcode']) {
                    $('#shipping-address input[name=\'postcode\']').after('<span class="alert alert-error">' + json['error']['postcode'] + '</span>');
                }   
                
                if (json['error']['country']) {
                    $('#shipping-address select[name=\'country_id\']').after('<span class="alert alert-error">' + json['error']['country'] + '</span>');
                }   
                
                if (json['error']['zone']) {
                    $('#shipping-address select[name=\'zone_id\']').after('<span class="alert alert-error">' + json['error']['zone'] + '</span>');
                }
            } else {
                $.ajax({
                    url: 'index.php?route=checkout/shipping_method',
                    dataType: 'html',
                    success: function(html) {
                        $('#shipping-method .checkout-content').html(html);
                        
                        $('#shipping-address .checkout-content').slideUp('slow');
                        
                        $('#shipping-method .checkout-content').slideDown('slow');
                        
                        $('#shipping-address .checkout-heading a').remove();
                        $('#shipping-method .checkout-heading a').remove();
                        $('#payment-method .checkout-heading a').remove();
                        
                        $('#shipping-address .checkout-heading').append('');                          
                        
                        $.ajax({
                            url: 'index.php?route=checkout/shipping_address',
                            dataType: 'html',
                            success: function(html) {
                                $('#shipping-address .checkout-content').html(html);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                        });
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                }); 
                
                $.ajax({
                    url: 'index.php?route=checkout/payment_address',
                    dataType: 'html',
                    success: function(html) {
                        $('#payment-address .checkout-content').html(html);
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

// Guest
$(document).on('click', '#button-guest', function() {
    $.ajax({
        url: 'index.php?route=checkout/guest/validate',
        type: 'post',
        data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
        dataType: 'json',
        beforeSend: function() {
       		$('#button-guest').button('loading');
	    },  
        complete: function() {
			$('#button-guest').button('reset');
        },          
        success: function(json) {
            $('.alert').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#payment-address .checkout-content').prepend('<div class="alert alert-warning" style="display: none;"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error']['warning'] + '</div>');
                    
                    $('.alert-warning').fadeIn('slow');
                }
                                
                if (json['error']['firstname']) {
                    $('#payment-address input[name=\'firstname\'] + br').after('<span class="alert alert-error">' + json['error']['firstname'] + '</span>');
                }
                
                if (json['error']['lastname']) {
                    $('#payment-address input[name=\'lastname\'] + br').after('<span class="alert alert-error">' + json['error']['lastname'] + '</span>');
                }   
                
                if (json['error']['email']) {
                    $('#payment-address input[name=\'email\'] + br').after('<span class="alert alert-error">' + json['error']['email'] + '</span>');
                }
                
                if (json['error']['telephone']) {
                    $('#payment-address input[name=\'telephone\'] + br').after('<span class="alert alert-error">' + json['error']['telephone'] + '</span>');
                }   
                    
                if (json['error']['company_id']) {
                    $('#payment-address input[name=\'company_id\'] + br').after('<span class="alert alert-error">' + json['error']['company_id'] + '</span>');
                }   
                
                if (json['error']['tax_id']) {
                    $('#payment-address input[name=\'tax_id\'] + br').after('<span class="alert alert-error">' + json['error']['tax_id'] + '</span>');
                }   
                                                                        
                if (json['error']['address_1']) {
                    $('#payment-address input[name=\'address_1\'] + br').after('<span class="alert alert-error">' + json['error']['address_1'] + '</span>');
                }   
                
                if (json['error']['city']) {
                    $('#payment-address input[name=\'city\'] + br').after('<span class="alert alert-error">' + json['error']['city'] + '</span>');
                }   
                
                if (json['error']['postcode']) {
                    $('#payment-address input[name=\'postcode\'] + br').after('<span class="alert alert-error">' + json['error']['postcode'] + '</span>');
                }   
                
                if (json['error']['country']) {
                    $('#payment-address select[name=\'country_id\'] + br').after('<span class="alert alert-error">' + json['error']['country'] + '</span>');
                }   
                
                if (json['error']['zone']) {
                    $('#payment-address select[name=\'zone_id\'] + br').after('<span class="alert alert-error">' + json['error']['zone'] + '</span>');
                }
            } else {
                <?php if ($shipping_required) { ?>  
                var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').prop('value');
                
                if (shipping_address) {
                    $.ajax({
                        url: 'index.php?route=checkout/shipping_method',
                        dataType: 'html',
                        success: function(html) {
                            $('#shipping-method .checkout-content').html(html);
                            
                            $('#payment-address .checkout-content').slideUp('slow');
                            
                            $('#shipping-method .checkout-content').slideDown('slow');
                            
                            $('#payment-address .checkout-heading a').remove();
                            $('#shipping-address .checkout-heading a').remove();
                            $('#shipping-method .checkout-heading a').remove();
                            $('#payment-method .checkout-heading a').remove();      
                                                            
                            $('#payment-address .checkout-heading').append('');   
                            $('#shipping-address .checkout-heading').append('');                                  
                            
                            $.ajax({
                                url: 'index.php?route=checkout/guest_shipping',
                                dataType: 'html',
                                success: function(html) {
                                    $('#shipping-address .checkout-content').html(html);
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });                 
                } else {
                    $.ajax({
                        url: 'index.php?route=checkout/guest_shipping',
                        dataType: 'html',
                        success: function(html) {
                            $('#shipping-address .checkout-content').html(html);
                            
                            $('#payment-address .checkout-content').slideUp('slow');
                            
                            $('#shipping-address .checkout-content').slideDown('slow');
                            
                            $('#payment-address .checkout-heading a').remove();
                            $('#shipping-address .checkout-heading a').remove();
                            $('#shipping-method .checkout-heading a').remove();
                            $('#payment-method .checkout-heading a').remove();
                            
                            $('#payment-address .checkout-heading').append('');   
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
                <?php } else { ?>               
                $.ajax({
                    url: 'index.php?route=checkout/payment_method',
                    dataType: 'html',
                    success: function(html) {
                        $('#payment-method .checkout-content').html(html);
                        
                        $('#payment-address .checkout-content').slideUp('slow');
                            
                        $('#payment-method .checkout-content').slideDown('slow');
                            
                        $('#payment-address .checkout-heading a').remove();
                        $('#payment-method .checkout-heading a').remove();
                                                        
                        $('#payment-address .checkout-heading').append('');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });             
                <?php } ?>
            }    
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }); 
});

// Guest Shipping
$(document).on('click', '#button-guest-shipping', function() {
    $.ajax({
        url: 'index.php?route=checkout/guest_shipping/validate',
        type: 'post',
        data: $('#shipping-address input[type=\'text\'], #shipping-address select'),
        dataType: 'json',
        beforeSend: function() {
        	$('#button-guest-shipping').button('loading');
		},  
        complete: function() {
			$('#button-guest-shipping').button('reset');
        },          
        success: function(json) {
            $('.alert').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#shipping-address .checkout-content').prepend('<div class="alert alert-warning" style="display: none;"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error']['warning'] + '</div>');
                    
                    $('.alert-warning').fadeIn('slow');
                }
                                
                if (json['error']['firstname']) {
                    $('#shipping-address input[name=\'firstname\']').after('<span class="alert alert-error">' + json['error']['firstname'] + '</span>');
                }
                
                if (json['error']['lastname']) {
                    $('#shipping-address input[name=\'lastname\']').after('<span class="alert alert-error">' + json['error']['lastname'] + '</span>');
                }   
                                        
                if (json['error']['address_1']) {
                    $('#shipping-address input[name=\'address_1\']').after('<span class="alert alert-error">' + json['error']['address_1'] + '</span>');
                }   
                
                if (json['error']['city']) {
                    $('#shipping-address input[name=\'city\']').after('<span class="alert alert-error">' + json['error']['city'] + '</span>');
                }   
                
                if (json['error']['postcode']) {
                    $('#shipping-address input[name=\'postcode\']').after('<span class="alert alert-error">' + json['error']['postcode'] + '</span>');
                }   
                
                if (json['error']['country']) {
                    $('#shipping-address select[name=\'country_id\']').after('<span class="alert alert-error">' + json['error']['country'] + '</span>');
                }   
                
                if (json['error']['zone']) {
                    $('#shipping-address select[name=\'zone_id\']').after('<span class="alert alert-error">' + json['error']['zone'] + '</span>');
                }
            } else {
                $.ajax({
                    url: 'index.php?route=checkout/shipping_method',
                    dataType: 'html',
                    success: function(html) {
                        $('#shipping-method .checkout-content').html(html);
                        
                        $('#shipping-address .checkout-content').slideUp('slow');
                        
                        $('#shipping-method .checkout-content').slideDown('slow');
                        
                        $('#shipping-address .checkout-heading a').remove();
                        $('#shipping-method .checkout-heading a').remove();
                        $('#payment-method .checkout-heading a').remove();
                            
                        $('#shipping-address .checkout-heading').append('');
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

$(document).on('click', '#button-shipping-method', function() {
    $.ajax({
        url: 'index.php?route=checkout/shipping_method/validate',
        type: 'post',
        data: $('#shipping-method input[type=\'radio\']:checked, #shipping-method textarea'),
        dataType: 'json',
        beforeSend: function() {
        	$('#button-shipping-method').button('loading');
		},  
        complete: function() {
			$('#button-shipping-method').button('reset');
        },          
        success: function(json) {
            $('.alert').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#shipping-method .checkout-content').prepend('<div class="alert alert-warning" style="display: none;"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error']['warning'] + '</div>');
                    
                    $('.alert-warning').fadeIn('slow');
                }           
            } else {
                $.ajax({
                    url: 'index.php?route=checkout/payment_method',
                    dataType: 'html',
                    success: function(html) {
                        $('#payment-method .checkout-content').html(html);
                        
                        $('#shipping-method .checkout-content').slideUp('slow');
                        
                        $('#payment-method .checkout-content').slideDown('slow');

                        $('#shipping-method .checkout-heading a').remove();
                        $('#payment-method .checkout-heading a').remove();
                        
                        $('#shipping-method .checkout-heading').append('');   
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

$(document).on('click', '#button-payment-method', function() {
    $.ajax({
        url: 'index.php?route=checkout/payment_method/validate', 
        type: 'post',
        data: $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
        dataType: 'json',
        beforeSend: function() {
         	$('#button-payment-method').button('loading');
		},  
        complete: function() {
            $('#button-payment-method').button('reset');
        },          
        success: function(json) {
            $('.alert').remove();
            
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#payment-method .checkout-content').prepend('<div class="alert alert-warning" style="display: none;"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error']['warning'] + '</div>');
                    
                    $('.alert-warning').fadeIn('slow');
                }           
            } else {
                $.ajax({
                    url: 'index.php?route=checkout/confirm',
                    dataType: 'html',
                    success: function(html) {
                        $('#confirm .checkout-content').html(html);
                        
                        $('#payment-method .checkout-content').slideUp('slow');
                        
                        $('#confirm .checkout-content').slideDown('slow');
                        
                        $('#payment-method .checkout-heading a').remove();
                        
                        $('#payment-method .checkout-heading').append('');    
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
//--></script> 
<?php echo $footer; ?>