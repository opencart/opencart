<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-return" data-toggle="tab"><?php echo $tab_return; ?></a></li>
          <li><a href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-return">
            <div class="control-group">
              <label class="control-label" for="input-order-id"><span class="required">*</span> <?php echo $entry_order_id; ?></label>
              <div class="controls">
                <input type="text" name="order_id" value="<?php echo $order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" />
                <?php if ($error_order_id) { ?>
                <span class="error"><?php echo $error_order_id; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-date-ordered"><?php echo $entry_date_ordered; ?></label>
              <div class="controls">
                <input type="date" name="date_ordered" value="<?php echo $date_ordered; ?>" placeholder="<?php echo $entry_date_ordered; ?>" id="input-date-ordered" class="input-medium" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
              <div class="controls">
                <input type="text" name="customer" value="<?php echo $customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" />
                <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-firstname"><span class="required">*</span> <?php echo $entry_firstname; ?></label>
              <div class="controls">
                <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" />
                <?php if ($error_firstname) { ?>
                <span class="error"><?php echo $error_firstname; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-lastname"><span class="required">*</span> <?php echo $entry_lastname; ?></label>
              <div class="controls">
                <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" />
                <?php if ($error_lastname) { ?>
                <span class="error"><?php echo $error_lastname; ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-email"><span class="required">*</span> <?php echo $entry_email; ?></label>
              <div class="controls">
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" />
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php  } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-telephone"><span class="required">*</span> <?php echo $entry_telephone; ?></label>
              <div class="controls">
                <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" />
                <?php if ($error_telephone) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php  } ?>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-product">
            <div class="control-group">
              <label class="control-label" for="input-product"><span class="required">*</span> <?php echo $entry_product; ?></label>
              <div class="controls">
                <input type="text" name="product" value="<?php echo $product; ?>" placeholder="<?php echo $entry_product; ?>" id="input-product" />
                <a data-toggle="tooltip" title="<?php echo $help_product; ?>"><i class="icon-info-sign"></i></a>
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                <?php if ($error_product) { ?>
                <span class="error"><?php echo $error_product; ?></span>
                <?php  } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
              <div class="controls">
                <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
              <div class="controls">
                <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="input-small" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-reason"><?php echo $entry_reason; ?></label>
              <div class="controls">
                <select name="return_reason_id" id="input-reason">
                  <?php foreach ($return_reasons as $return_reason) { ?>
                  <?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
                  <option value="<?php echo $return_reason['return_reason_id']; ?>" selected="selected"><?php echo $return_reason['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $return_reason['return_reason_id']; ?>"><?php echo $return_reason['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-opened"><?php echo $entry_opened; ?></label>
              <div class="controls">
                <select name="opened" id="input-opened">
                  <?php if ($opened) { ?>
                  <option value="1" selected="selected"><?php echo $text_opened; ?></option>
                  <option value="0"><?php echo $text_unopened; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_opened; ?></option>
                  <option value="0" selected="selected"><?php echo $text_unopened; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-comment"><?php echo $entry_comment; ?></label>
              <div class="controls">
                <textarea name="comment" cols="40" rows="5" placeholder="<?php echo $entry_comment; ?>" id="input-comment"><?php echo $comment; ?></textarea>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-action"><?php echo $entry_action; ?></label>
              <div class="controls">
                <select name="return_action_id" id="input-action">
                  <option value="0"></option>
                  <?php foreach ($return_actions as $return_action) { ?>
                  <?php if ($return_action['return_action_id'] == $return_action_id) { ?>
                  <option value="<?php echo $return_action['return_action_id']; ?>" selected="selected"> <?php echo $return_action['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $return_action['return_action_id']; ?>"><?php echo $return_action['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="controls">
                <select name="return_status_id" id="input-status">
                  <?php foreach ($return_statuses as $return_status) { ?>
                  <?php if ($return_status['return_status_id'] == $return_status_id) { ?>
                  <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item['customer_group'],
						label: item['name'],
						value: item['customer_id'],
						firstname: item['firstname'],
						lastname: item['lastname'],
						email: item['email'],
						telephone: item['telephone']			
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'customer\']').val(item['label']);
		$('input[name=\'customer_id\']').val(item['value']);
		$('input[name=\'firstname\']').attr('value', item['firstname']);
		$('input[name=\'lastname\']').attr('value', item['lastname']);
		$('input[name=\'email\']').attr('value', item['email']);
		$('input[name=\'telephone\']').attr('value', item['telephone']);
	}
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id'],
						model: item['model']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'product\']').val(item['label']);
		$('input[name=\'product_id\']').val(item['value']);	
		$('input[name=\'model\']').val(item['model']);	
	}
});
//--></script>
<?php echo $footer; ?>