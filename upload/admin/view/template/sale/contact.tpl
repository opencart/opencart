<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-envelope"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form class="form-horizontal">
        <div class="buttons">
          <button id="button-send" class="btn"><i class="icon-envelope"></i> <?php echo $button_send; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-store"><?php echo $entry_store; ?></label>
          <div class="controls">
            <select name="store_id" id="input-store">
              <option value="0"><?php echo $text_default; ?></option>
              <?php foreach ($stores as $store) { ?>
              <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-to"><?php echo $entry_to; ?></label>
          <div class="controls">
            <select name="to" id="input-to">
              <option value="newsletter"><?php echo $text_newsletter; ?></option>
              <option value="customer_all"><?php echo $text_customer_all; ?></option>
              <option value="customer_group"><?php echo $text_customer_group; ?></option>
              <option value="customer"><?php echo $text_customer; ?></option>
              <option value="affiliate_all"><?php echo $text_affiliate_all; ?></option>
              <option value="affiliate"><?php echo $text_affiliate; ?></option>
              <option value="product"><?php echo $text_product; ?></option>
            </select>
          </div>
        </div>
        <div class="control-group to" id="to-customer-group">
          <label class="control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
          <div class="controls">
            <select name="customer_group_id" id="input-customer-group">
              <?php foreach ($customer_groups as $customer_group) { ?>
              <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group to" id="to-customer">
          <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
          <div class="controls">
            <input type="text" name="customers" value="" placeholder="<?php echo $entry_customer; ?>" id="input-customer" data-toggle="dropdown" data-target="#autocomplete-customer" autocomplete="off" />
            <a data-toggle="tooltip" title="<?php echo $help_customer; ?>"><i class="icon-question-sign icon-large"></i></a>
            <div id="autocomplete-customer" class="dropdown">
              <ul class="dropdown-menu">
                <li class="disabled"><a href="#"><i class="icon-spinner icon-spin"></i> <?php echo $text_loading; ?></a></li>
              </ul>
            </div>
            <br />
            <div id="customer" class="well well-small scrollbox"></div>
          </div>
        </div>
        <div class="control-group to" id="to-affiliate">
          <label class="control-label" for="input-affiliate"><?php echo $entry_affiliate; ?></label>
          <div class="controls">
            <input type="text" name="affiliates" value="" placeholder="<?php echo $entry_affiliate; ?>" id="input-affiliate" data-toggle="dropdown" data-target="#autocomplete-affiliate" autocomplete="off" />
            <a data-toggle="tooltip" title="<?php echo $help_affiliate; ?>"><i class="icon-question-sign icon-large"></i></a>
            <div id="autocomplete-affiliate" class="dropdown">
              <ul class="dropdown-menu">
                <li class="disabled"><a href="#"><i class="icon-spinner icon-spin"></i> <?php echo $text_loading; ?></a></li>
              </ul>
            </div>
            <br />
            <div id="affiliate" class="well well-small scrollbox"></div>
          </div>
        </div>
        <div class="control-group to" id="to-product">
          <label class="control-label" for="input-product"><?php echo $entry_product; ?></label>
          <div class="controls">
            <input type="text" name="products" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" data-toggle="dropdown" data-target="#autocomplete-product" autocomplete="off" />
            <a data-toggle="tooltip" title="<?php echo $help_product; ?>"><i class="icon-question-sign icon-large"></i></a>
            <div id="autocomplete-product" class="dropdown">
              <ul class="dropdown-menu">
                <li class="disabled"><a href="#"><i class="icon-spinner icon-spin"></i> <?php echo $text_loading; ?></a></li>
              </ul>
            </div>
            <br />
            <div id="product" class="well well-small scrollbox"></div>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-subject"><span class="required">*</span> <?php echo $entry_subject; ?></label>
          <div class="controls">
            <input type="text" name="subject" value="" placeholder="<?php echo $entry_subject; ?>" id="input-subject" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-message"><span class="required">*</span> <?php echo $entry_message; ?></label>
          <div class="controls">
            <textarea name="message" placeholder="<?php echo $entry_message; ?>" id="input-message"></textarea>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
CKEDITOR.replace('message', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
//--></script> 
<script type="text/javascript"><!--	
$('select[name=\'to\']').on('change', function() {
	$('.to').hide();
	
	$('#to-' + this.value.replace('_', '-')).show();
});

$('select[name=\'to\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
// Customer
var timer = null;

$('input[name=\'customers\']').on('click keyup', function() {
	var input = this;
	
	if (timer != null) {
		clearTimeout(timer);
	}

	timer = setTimeout(function() {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($(input).val()),
			dataType: 'json',			
			success: function(json) {
				if (json.length) {
					html = '';
					
					for (i in json) {
						html += '<li class="disabled"><a href="#"><b>' + json[i]['name'] + '</b></a></li>';
						
						for (j = 0; j < json[i]['customer'].length; j++) {
							customer = json[i]['customer'][j];
							
							html += '<li data-value="' + customer['customer_id'] + '"><a href="#">' + customer['name'] + '</a></li>';						
						}
					}
				} else {
					html = '<li class="disabled"><a href="#"><?php echo $text_none; ?></a></li>';
				}
				
				$($(input).attr('data-target')).find('ul').html(html);
			}
		});
	}, 250);
});

$('#autocomplete-customer').delegate('a', 'click', function(e) {
	e.preventDefault();
	
	var value = $(this).parent().attr('data-value');
	
	if (typeof value !== 'undefined') {
		$('#customer' + value).remove();
		
		$('#customer').append('<div id="customer' + value + '"><i class="icon-minus-sign"></i> ' + $(this).text() + '<input type="hidden" name="customer[]" value="' + value + '" /></div>');
		
	}
});

$('#customer').delegate('.icon-minus-sign', 'click', function() {
	$(this).parent().remove();
});

// Affiliates
var timer = null;

$('input[name=\'affiliates\']').on('click keyup', function() {
	var input = this;
	
	if (timer != null) {
		clearTimeout(timer);
	}

	timer = setTimeout(function() {
		$.ajax({
			url: 'index.php?route=sale/affiliate/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($(input).val()),
			dataType: 'json',			
			success: function(json) {
				if (json.length) {
					html = '';
					
					for (i = 0; i < json.length; i++) {
						html += '<li data-value="' + json[i]['customer_id'] + '"><a href="#">' + json[i]['name'] + '</a></li>';						
					}
				} else {
					html = '<li class="disabled"><a href="#"><?php echo $text_none; ?></a></li>';
				}
				
				$($(input).attr('data-target')).find('ul').html(html);
			}
		});
	}, 250);
});

$('#autocomplete-affiliate').delegate('a', 'click', function(e) {
	e.preventDefault();
	
	var value = $(this).parent().attr('data-value');
	
	if (typeof value !== 'undefined') {
		$('#affiliate' + value).remove();
		
		$('#affiliate').append('<div id="affiliate' + value + '"><i class="icon-minus-sign"></i> ' + $(this).text() + '<input type="hidden" name="affiliate[]" value="' + value + '" /></div>');
		
	}
});

$('#affiliate').delegate('.icon-minus-sign', 'click', function() {
	$(this).parent().remove();
});

// Products
var timer = null;

$('input[name=\'products\']').on('click keyup', function() {
	var input = this;
	
	if (timer != null) {
		clearTimeout(timer);
	}

	timer = setTimeout(function() {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($(input).val()),
			dataType: 'json',			
			success: function(json) {
				if (json.length) {
					html = '';
					
					for (i = 0; i < json.length; i++) {
						html += '<li data-value="' + json[i]['product_id'] + '"><a href="#">' + json[i]['name'] + '</a></li>';
					}
				} else {
					html = '<li class="disabled"><a href="#"><?php echo $text_none; ?></a></li>';
				}
				
				$($(input).attr('data-target')).find('ul').html(html);
			}
		});
	}, 250);
});

$('#autocomplete-product').delegate('a', 'click', function(e) {
	e.preventDefault();
	
	var value = $(this).parent().attr('data-value');
	
	if (typeof value !== 'undefined') {
		$('#product' + value).remove();
		
		$('#product').append('<div id="product' + value + '"><i class="icon-minus-sign"></i> ' + $(this).text() + '<input type="hidden" name="product[]" value="' + value + '" /></div>');
	}
});

$('#product').delegate('.icon-minus-sign', 'click', function() {
	$(this).parent().remove();
});

$('#button-send').on('click', function() {
	$('textarea[name=\'message\']').html(CKEDITOR.instances.message.getData());
	
	$.ajax({
		url: 'index.php?route=sale/contact/send&token=<?php echo $token; ?>',
		type: 'post',
		data: $('select, input, textarea'),		
		dataType: 'json',
		beforeSend: function() {
			$('#button-send i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-send').attr('disabled', true);
		},
		complete: function() {
			$('#button-send i').replaceWith('<i class="icon-envelope"></i>');
			$('#button-send').attr('disabled', false);
		},				
		success: function(json) {
			$('.alert, .error').remove();
			
			if (json['error']) {
				if (json['error']['warning']) {
					$('.box').before('<div class="alert alert-error" style="display: none;">' + json['error']['warning'] + '</div>');
			
					$('.alert-error').fadeIn('slow');
				}
				
				if (json['error']['subject']) {
					$('input[name=\'subject\']').after('<span class="error">' + json['error']['subject'] + '</span>');
				}	
				
				if (json['error']['message']) {
					$('textarea[name=\'message\']').parent().append('<span class="error">' + json['error']['message'] + '</span>');
				}									
			}			
			
			if (json['next']) {
				if (json['success']) {
					$('.box').before('<div class="alert alert-success">' + json['success'] + '</div>');
					
					send(json['next']);
				}		
			} else {
				if (json['success']) {
					$('.box').before('<div class="alert alert-success" style="display: none;">' + json['success'] + '</div>');
			
					$('.alert-success').fadeIn('slow');
				}					
			}				
		}
	});
});
//--></script> 
<?php echo $footer; ?>