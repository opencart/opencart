<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <?php if ($coupon_id) { ?>
          <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="control-group">
              <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_name; ?></label>
              <div class="controls">
                <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" />
                <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-code"><span class="required">*</span> <?php echo $entry_code; ?></label>
              <div class="controls">
                <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" />
                <span class="help-block"><?php echo $help_code; ?></span>
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-type"><?php echo $entry_type; ?></label>
              <div class="controls">
                <select name="type" id="input-type">
                  <?php if ($type == 'P') { ?>
                  <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                  <?php } else { ?>
                  <option value="P"><?php echo $text_percent; ?></option>
                  <?php } ?>
                  <?php if ($type == 'F') { ?>
                  <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                  <?php } else { ?>
                  <option value="F"><?php echo $text_amount; ?></option>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $help_type; ?></span></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-discount"><?php echo $entry_discount; ?></label>
              <div class="controls">
                <input type="text" name="discount" value="<?php echo $discount; ?>" placeholder="<?php echo $entry_discount; ?>" id="input-discount" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
              <div class="controls">
                <input type="text" name="total" value="<?php echo $total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" />
                <span class="help-block"><?php echo $help_total; ?></span></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $entry_logged; ?></div>
              <div class="controls">
                <label class="radio inline">
                  <?php if ($logged) { ?>
                  <input type="radio" name="logged" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="logged" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio inline">
                  <?php if (!$logged) { ?>
                  <input type="radio" name="logged" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="logged" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
                <span class="help-block"><?php echo $help_logged; ?></span></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $entry_shipping; ?></div>
              <div class="controls">
                <label class="radio inline">
                  <?php if ($shipping) { ?>
                  <input type="radio" name="shipping" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="shipping" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio inline">
                  <?php if (!$shipping) { ?>
                  <input type="radio" name="shipping" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="shipping" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
                </label>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-product"><?php echo $entry_product; ?></label>
              <div class="controls">
                <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" />
                <span class="help-block"><?php echo $help_product; ?></span>
                <div id="coupon-product" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($coupon_product as $coupon_product) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="coupon-product<?php echo $coupon_product['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $coupon_product['name']; ?><img src="view/image/icon-delete.png" alt="" />
                    <input type="hidden" name="coupon_product[]" value="<?php echo $coupon_product['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-category"><?php echo $entry_category; ?></label>
              <div class="controls">
                <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" />
                <span class="help-block"><?php echo $help_category ?></span>
                <div id="coupon-category" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($coupon_category as $coupon_category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="coupon-category<?php echo $coupon_category['category_id']; ?>" class="<?php echo $class; ?>"> <?php echo $coupon_category['name']; ?><img src="view/image/icon-delete.png" alt="" />
                    <input type="hidden" name="coupon_category[]" value="<?php echo $coupon_category['category_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
              <div class="controls">
                <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" id="input-date-start" class="input-small" id="date-start" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
              <div class="controls">
                <input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" id="input-date-end" class="input-small" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-uses-total"><?php echo $entry_uses_total; ?></label>
              <div class="controls">
                <input type="text" name="uses_total" value="<?php echo $uses_total; ?>" placeholder="<?php echo $entry_uses_total; ?>" id="input-uses-total" />
                <span class="help-block"><?php echo $help_uses_total; ?></span></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-uses-customer"><?php echo $entry_uses_customer; ?></label>
              <div class="controls">
                <input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" placeholder="<?php echo $entry_uses_customer; ?>" id="input-uses-customer" />
                <span class="help-block"><?php echo $help_uses_customer; ?></span></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="controls">
                <select name="status" id="input-status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <?php if ($coupon_id) { ?>
          <div class="tab-pane" id="tab-history">
            <div id="history"></div>
          </div>
          <?php } ?>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#coupon-product' + ui.item.value).remove();
		
		$('#coupon-product').append('<div id="coupon-product' + ui.item.value + '">' + ui.item.label + '<img src="view/image/icon-delete.png" alt="" /><input type="hidden" name="coupon_product[]" value="' + ui.item.value + '" /></div>');

		$('#coupon-product div:odd').attr('class', 'odd');
		$('#coupon-product div:even').attr('class', 'even');
		
		$('input[name=\'product\']').val('');
		
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('#coupon-product div img').on('click', function() {
	$(this).parent().remove();
	
	$('#coupon-product div:odd').attr('class', 'odd');
	$('#coupon-product div:even').attr('class', 'even');	
});

$('input[name=\'category\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.category_id
					}
				}));
			}
		});
		
	}, 
	select: function(event, ui) {
		$('#coupon-category' + ui.item.value).remove();
		
		$('#coupon-category').append('<div id="product-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/icon-delete.png" alt="" /><input type="hidden" name="coupon_category[]" value="' + ui.item.value + '" /></div>');

		$('#coupon-category div:odd').attr('class', 'odd');
		$('#coupon-category div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#coupon-category div img').on('click', function() {
	$(this).parent().remove();
	
	$('#coupon-category div:odd').attr('class', 'odd');
	$('#coupon-category div:even').attr('class', 'even');	
});
//--></script> 
<script type="text/javascript"><!--
$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
//--></script>
<?php if ($coupon_id) { ?>
<script type="text/javascript"><!--
$('#history .pagination a').on('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('index.php?route=sale/coupon/history&token=<?php echo $token; ?>&coupon_id=<?php echo $coupon_id; ?>');
//--></script>
<?php } ?>
<?php echo $footer; ?>