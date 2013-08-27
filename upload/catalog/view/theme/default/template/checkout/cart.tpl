<?php echo $header; ?>
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
  <div id="content" class="col-12"><?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?>
      <?php if ($weight) { ?>
      &nbsp;(<?php echo $weight; ?>)
      <?php } ?>
    </h1>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
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
            <td class="text-left"><input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="input-mini" />
              <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn-link"><i class="icon-refresh"></i></button>
              <a href="<?php echo $product['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>"><i class="icon-remove"></i></a></td>
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
    </form>
    <?php if ($coupon_status || $voucher_status || $reward_status || $shipping_status) { ?>
    <h2><?php echo $text_next; ?></h2>
    <p><?php echo $text_next_choice; ?></p>
    <div class="row">
      <div class="span12">
        <div class="accordion" id="accordion">
          <?php if ($coupon_status) { ?>
          <div class="accordion-group">
            <div class="accordion-heading"><a href="#collapse-coupon" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_use_coupon; ?> <i class="icon-caret-down"></i></a></div>
            <div id="collapse-coupon" class="accordion-body collapse">
              <div class="accordion-inner form-inline">
                <label class="col-sm-2 control-label" for="input-coupon"><?php echo $entry_coupon; ?></label>
                <input type="text" name="coupon" value="<?php echo $coupon; ?>" placeholder="<?php echo $entry_coupon; ?>" id="input-coupon" />
                <input type="button" value="<?php echo $button_coupon; ?>" id="button-coupon" data-loading-text="<?php echo $text_loading; ?>"  class="btn" />
              </div>
            </div>
          </div>
          <?php } ?>
          <?php if ($voucher_status) { ?>
          <div class="accordion-group">
            <div class="accordion-heading"><a href="#collapse-voucher" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_use_voucher; ?> <i class="icon-caret-down"></i></a></div>
            <div id="collapse-voucher" class="accordion-body collapse">
              <div class="accordion-inner form-inline">
                <label class="col-sm-2 control-label" for="input-voucher"><?php echo $entry_voucher; ?></label>
                <input type="text" name="voucher" value="<?php echo $voucher; ?>" placeholder="<?php echo $entry_voucher; ?>" id="input-voucher" />
                <input type="submit" value="<?php echo $button_voucher; ?>" id="button-voucher" data-loading-text="<?php echo $text_loading; ?>"  class="btn" />
              </div>
            </div>
          </div>
          <?php } ?>
          <?php if ($reward_status) { ?>
          <div class="accordion-group">
            <div class="accordion-heading"><a href="#collapse-reward" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_use_reward; ?> <i class="icon-caret-down"></i></a></div>
            <div id="collapse-reward" class="accordion-body collapse">
              <div class="accordion-inner form-inline">
                <label class="col-sm-2 control-label" for="input-reward"><?php echo $entry_reward; ?></label>
                <input type="text" name="reward" value="<?php echo $reward; ?>" placeholder="<?php echo $entry_reward; ?>" id="input-reward" />
                <input type="submit" value="<?php echo $button_reward; ?>" id="button-reward" data-loading-text="<?php echo $text_loading; ?>"  class="btn" />
              </div>
            </div>
          </div>
          <?php } ?>
          <?php if ($shipping_status) { ?>
          <div class="accordion-group">
            <div class="accordion-heading"><a href="#collapse-shipping" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_shipping_estimate; ?> <i class="icon-caret-down"></i></a></div>
            <div id="collapse-shipping" class="accordion-body collapse">
              <div class="accordion-inner">
                <p><?php echo $text_shipping_detail; ?></p>
                <div class="form-horizontal">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
                    <div class="col-sm-10">
                      <select name="country_id" id="input-country">
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
                      <select name="zone_id" id="input-zone">
                      </select>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" />
                    </div>
                  </div>
                  <input type="button" value="<?php echo $button_quote; ?>" id="button-quote" data-loading-text="<?php echo $text_loading; ?>" class="btn" />
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
    <div class="row">
      <div class="span4 offset8">
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
      <div class="pull-left"><a class="btn" href="<?php echo $continue; ?>"><?php echo $button_shopping; ?></a></div>
      <div class="pull-right"><a class="btn btn-primary" href="<?php echo $checkout; ?>"><?php echo $button_checkout; ?></a></div>
    </div>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
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
				$('#content').prepend('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		
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
				$('#content').prepend('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		
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
				$('#content').prepend('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		
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
					$('#content').prepend('<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			
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
				html  = '<h2><?php echo $text_shipping_method; ?></h2>';
				html += '  <table class="table table-bordered">';
			
				for (i in json['shipping_method']) {
					html += '<tr>';
					html += '  <td colspan="3"><b>' + json['shipping_method'][i]['title'] + '</b></td>';
					html += '</tr>';
			
					if (!json['shipping_method'][i]['error']) {
						for (j in json['shipping_method'][i]['quote']) {
							html += '<tr class="highlight">';
					
							if (json['shipping_method'][i]['quote'][j]['code'] == '<?php echo $shipping_method; ?>') {
								html += '<td><input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" id="' + json['shipping_method'][i]['quote'][j]['code'] + '" checked="checked" /></td>';
							} else {
								html += '<td><input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" id="' + json['shipping_method'][i]['quote'][j]['code'] + '" /></td>';
							}
					
							html += '  <td><label for="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['title'] + '</label></td>';
							html += '  <td style="text-align: right;"><label for="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['text'] + '</label></td>';
							html += '</tr>';
						}     
					} else {
						html += '<tr>';
						html += '  <td colspan="3"><div class="text-danger">' + json['shipping_method'][i]['error'] + '</div></td>';
						html += '</tr>';                 
					}
				}
					
				html += '  </table>';

				<?php if ($shipping_method) { ?>
				html += '  <input type="submit" value="<?php echo $button_shipping; ?>" id="button-shipping" class="btn" />';  
				<?php } else { ?>
				html += '  <input type="submit" value="<?php echo $button_shipping; ?>" id="button-shipping" class="btn" disabled="disabled" />';   
				<?php } ?>
				
				$.magnificPopup({
					src: '<div>HTML string</div>',
					type: 'inline'
				});
	  
	  /*
				$.colorbox({
					overlayClose: true,
					opacity: 0.5,
					width: '600px',
					height: '400px',
					href: false,
					html: html
				});
		*/		
				$('input[name=\'shipping_method\']').bind('change', function() {
					$('#button-shipping').prop('disabled', false);
				});
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