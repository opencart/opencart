<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($attention) { ?>
  <div class="alert alert-info"><?php echo $attention; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <div id="content" class="col-sm-12"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?>
        <?php if ($weight) { ?>
        &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
      </h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-center"><?php echo $column_image; ?></td>
                <td class="text-left"><?php echo $column_name; ?></td>
                <td class="text-left"><?php echo $column_model; ?></td>
                <td class="text-left"><?php echo $column_quantity; ?></td>
                <td class="text-right"><?php echo $column_price; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td class="text-center"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                  <?php } ?></td>
                <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                  <?php if (!$product['stock']) { ?>
                  <span class="stock">***</span>
                  <?php } ?>
                  <div>
                    <?php foreach ($product['option'] as $option) { ?>
                    <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                    <?php } ?>
                  </div>
                  <?php if ($product['reward']) { ?>
                  <small><?php echo $product['reward']; ?></small>
                  <?php } ?></td>
                <td class="text-left"><?php echo $product['model']; ?></td>
                <td class="text-left"><div class="input-group">
                    <input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
                    <div class="input-group-btn btn-group">
                      <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary"><i class="icon-refresh"></i></button>
                      <a href="<?php echo $product['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="icon-remove"></i></a></div>
                  </div></td>
                <td class="text-right"><?php echo $product['price']; ?></td>
                <td class="text-right"><?php echo $product['total']; ?></td>
              </tr>
              <?php } ?>
              <?php foreach ($vouchers as $vouchers) { ?>
              <tr>
                <td></td>
                <td class="text-left"><?php echo $vouchers['description']; ?></td>
                <td class="text-left"></td>
                <td class="text-left"><input type="text" name="" value="1" size="1" disabled="disabled" class="input-mini" />
                  <a href="<?php echo $vouchers['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>"><i class="icon-remove"></i></a></td>
                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
                <td class="text-right"><?php echo $vouchers['amount']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
      <?php if ($coupon_status || $voucher_status || $reward_status || $shipping_status) { ?>
      <h2><?php echo $text_next; ?></h2>
      <p><?php echo $text_next_choice; ?></p>
        
          <div class="panel-group" id="accordion">
            
            <?php if ($coupon_status) { ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title"><a href="#collapse-coupon" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_use_coupon; ?> <i class="icon-caret-down"></i></a></h4>
              </div>
              <div id="collapse-coupon" class="panel-collapse collapse">
                <div class="panel-body">
                  <label class="col-sm-2 control-label" for="input-coupon"><?php echo $entry_coupon; ?></label>
                  <div class="input-group">
                    <input type="text" name="coupon" value="<?php echo $coupon; ?>" placeholder="<?php echo $entry_coupon; ?>" id="input-coupon" class="form-control" />
                    <span class="input-group-btn">
                      <input type="button" value="<?php echo $button_coupon; ?>" id="button-coupon" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary" />
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
            
            <?php if ($voucher_status) { ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title"><a href="#collapse-voucher" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_use_voucher; ?> <i class="icon-caret-down"></i></a></h4>
              </div>
              <div id="collapse-voucher" class="panel-collapse collapse">
                <div class="panel-body">
                  <label class="col-sm-2 control-label" for="input-voucher"><?php echo $entry_voucher; ?></label>
                  
                  <div class="input-group">
                    <input type="text" name="voucher" value="<?php echo $voucher; ?>" placeholder="<?php echo $entry_voucher; ?>" id="input-voucher" class="form-control" />
                    <span class="input-group-btn">
                      <input type="submit" value="<?php echo $button_voucher; ?>" id="button-voucher" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary" />
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
            
            
            <?php if ($reward_status) { ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title"><a href="#collapse-reward" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_use_reward; ?> <i class="icon-caret-down"></i></a></h4>
              </div>
              <div id="collapse-reward" class="panel-collapse collapse">
                <div class="panel-body">
                  <label class="col-sm-2 control-label" for="input-reward"><?php echo $entry_reward; ?></label>
                  
                  <div class="input-group">
                    <input type="text" name="reward" value="<?php echo $reward; ?>" placeholder="<?php echo $entry_reward; ?>" id="input-reward" class="form-control" />
                    <span class="input-group-btn">
                      <input type="submit" value="<?php echo $button_reward; ?>" id="button-reward" data-loading-text="<?php echo $text_loading; ?>"  class="btn btn-primary" />
                    </span>
                  </div>
                  
                </div>
              </div>
            </div>
            <?php } ?>
            
            <?php if ($shipping_status) { ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title"><a href="#collapse-shipping" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_shipping_estimate; ?> <i class="icon-caret-down"></i></a></h4>
              </div>
              <div id="collapse-shipping" class="panel-collapse collapse">
                <div class="panel-body">
                  <p><?php echo $text_shipping_detail; ?></p>
                  <form class="form-horizontal">
                    <div class="form-group required">
                      <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
                      <div class="col-sm-10">
                        <select name="country_id" id="input-country" class="form-control">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($countries as $country) { ?>
                          <?php if ($country['country_id'] == $country_id) { ?>
                          <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group required">
                      <label class="col-sm-2 control-label" for="input-zone"><?php echo $entry_zone; ?></label>
                      <div class="col-sm-10">
                        <select name="zone_id" id="input-zone" class="form-control">
                        </select>
                      </div>
                    </div>
                    <div class="form-group required">
                      <label class="col-sm-2 control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
                      <div class="col-sm-10">
                        <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
                      </div>
                    </div>
                    <input type="button" value="<?php echo $button_quote; ?>" id="button-quote" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
                  </form>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
          
          
          <?php } ?>
      <br />
      <div class="row">
        <div class="col-sm-4 col-sm-offset-8">
          <table class="table table-bordered">
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </div>
      <div class="buttons">
        <div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
        <div class="pull-right"><a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $button_checkout; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php if ($shipping_status) { ?>
<script type="text/javascript"><!--
$('#button-coupon').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/coupon',
		type: 'post',
		data: 'coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
		dataType: 'json',    
		beforeSend: function() {
			$('#button-coupon').button('loading');
		},
		complete: function() {
			$('#button-coupon').button('reset');
		},
		success: function(json) {
			$('.alert').remove();   

			if (json['error']) {
				$('.breadcrumb').after('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}  

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});

$('#button-voucher').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/voucher',
		type: 'post',
		data: 'voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
		dataType: 'json',    
		beforeSend: function() {
			$('#button-voucher').button('loading');
		},
		complete: function() {
			$('#button-voucher').button('reset');
		},
		success: function(json) {
			$('.alert').remove();   

			if (json['error']) {
				$('.breadcrumb').after('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}  

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});

$('#button-reward').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/reward',
		type: 'post',
		data: 'reward=' + encodeURIComponent($('input[name=\'reward\']').val()),
		dataType: 'json',    
		beforeSend: function() {
			$('#button-reward').button('loading');
		},
		complete: function() {
			$('#button-reward').button('reset');
		},
		success: function(json) {
			$('.alert').remove();   

			if (json['error']) {
				$('.breadcrumb').after('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}  

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});

$('#button-quote').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/quote',
		type: 'post',
		data: 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val()),
		dataType: 'json',    
		beforeSend: function() {
			$('#button-quote').button('loading');
		},
		complete: function() {
			$('#button-quote').button('reset');
		},
		success: function(json) {
			$('.alert').remove();      
		
			if (json['error']) {
				if (json['error']['warning']) {
					$('.breadcrumb').after('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			
					$('html, body').animate({ scrollTop: 0 }, 'slow'); 
				}  
		
				if (json['error']['country']) {
					$('select[name=\'country_id\']').after('<div class="text-danger">' + json['error']['country'] + '</div>');
				}  
		
				if (json['error']['zone']) {
					$('select[name=\'zone_id\']').after('<div class="text-danger">' + json['error']['zone'] + '</div>');
				}
		
				if (json['error']['postcode']) {
					$('input[name=\'postcode\']').after('<div class="text-danger">' + json['error']['postcode'] + '</div>');
				}              
			}

			if (json['shipping_method']) {
				$('#modal-shipping').remove();
				
				html  = '<div id="modal-shipping" class="modal">';
				html += '  <div class="modal-dialog">';
				html += '    <div class="modal-content">';
				html += '      <div class="modal-header">';
				html += '        <h4 class="modal-title"><?php echo $text_shipping_method; ?></h4>';
				html += '      </div>';
				html += '      <div class="modal-body">';				
				
				for (i in json['shipping_method']) {
					html += '<p><strong>' + json['shipping_method'][i]['title'] + '</strong></p>';
			
					if (!json['shipping_method'][i]['error']) {
						for (j in json['shipping_method'][i]['quote']) {
							html += '<div class="radio">';
							html += '  <label>';
					
							if (json['shipping_method'][i]['quote'][j]['code'] == '<?php echo $shipping_method; ?>') {
								html += '<input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" checked="checked" />';
							} else {
								html += '<input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" />';
							}
					
							html += json['shipping_method'][i]['quote'][j]['title'] + ' - ' + json['shipping_method'][i]['quote'][j]['text'] + '</label></div>';
						}     
					} else {
						html += '<div class="alert alert-danger">' + json['shipping_method'][i]['error'] + '</div>';
					}
				}
				
				html += '      </div>';
				html += '      <div class="modal-footer">';
				html += '        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_cancel; ?></button>'; 
				
				<?php if ($shipping_method) { ?>
				html += '        <input type="button" value="<?php echo $button_shipping; ?>" id="button-shipping" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />';  
				<?php } else { ?>
				html += '        <input type="button" value="<?php echo $button_shipping; ?>" id="button-shipping" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" disabled="disabled" />';   
				<?php } ?>
				
				html += '      </div>';
				html += '    </div>';
				html += '  </div>';
				html += '</div> ';	
  				
				$('body').append(html);
  				
				$('#modal-shipping').modal('show');
  
				$('input[name=\'shipping_method\']').bind('change', function() {
					$('#button-shipping').prop('disabled', false);
				});
			}
		}
	});
});

$(document).delegate('#button-shipping', 'click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/shipping',
		type: 'post',
		data: 'shipping_method=' + encodeURIComponent($('input[name=\'shipping_method\']:checked').val()),
		dataType: 'json',    
		beforeSend: function() {
			$('#button-shipping').button('loading');
		},
		complete: function() {
			$('#button-shipping').button('reset');
		},
		success: function(json) {
			$('.alert').remove();   
			
			if (json['error']) {
				$('.breadcrumb').after('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}  

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="icon-spinner icon-spin"></i>');
		},
		complete: function() {
			$('.icon-spinner').remove();
		},       
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#input-postcode').parent().parent().addClass('required');
			} else {
				$('#input-postcode').parent().parent().removeClass('required');
			}

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone']) {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';
				
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
			  		}
			
			  		html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
		   alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script>
<?php } ?>
<?php echo $footer; ?> 