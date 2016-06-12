<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-return" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-return" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <?php if ($return_id) { ?>
            <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <fieldset>
              <legend><?php echo $text_order; ?></legend>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="order_id" value="<?php echo $order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
                  <?php if ($error_order_id) { ?>
                  <div class="text-danger"><?php echo $error_order_id; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-ordered"><?php echo $entry_date_ordered; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" placeholder="<?php echo $entry_date_ordered; ?>" data-date-format="YYYY-MM-DD" id="input-date-ordered" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="customer" value="<?php echo $customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                  <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                  <?php if ($error_firstname) { ?>
                  <div class="text-danger"><?php echo $error_firstname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                  <?php if ($error_lastname) { ?>
                  <div class="text-danger"><?php echo $error_lastname; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                  <?php if ($error_email) { ?>
                  <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                  <?php if ($error_telephone) { ?>
                  <div class="text-danger"><?php echo $error_telephone; ?></div>
                  <?php  } ?>
                </div>
              </div>
            </fieldset>
            <fieldset>
              <legend><?php echo $text_product; ?></legend>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="product" value="<?php echo $product; ?>" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                  <?php if ($error_product) { ?>
                  <div class="text-danger"><?php echo $error_product; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-model"><?php echo $entry_model; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                  <?php if ($error_model) { ?>
                  <div class="text-danger"><?php echo $error_model; ?></div>
                  <?php  } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-return-reason"><?php echo $entry_return_reason; ?></label>
                <div class="col-sm-10">
                  <select name="return_reason_id" id="input-return-reason" class="form-control">
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
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-opened"><?php echo $entry_opened; ?></label>
                <div class="col-sm-10">
                  <select name="opened" id="input-opened" class="form-control">
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
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                <div class="col-sm-10">
                  <textarea name="comment" rows="5" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control"><?php echo $comment; ?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-return-action"><?php echo $entry_return_action; ?></label>
                <div class="col-sm-10">
                  <select name="return_action_id" id="input-return-action" class="form-control">
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
              <?php if (!$return_id) { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-return-status"><?php echo $entry_return_status; ?></label>
                <div class="col-sm-10">
                  <select name="return_status_id" id="input-return-status" class="form-control">
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
              <?php } ?>
            </fieldset>
          </div>
          <?php if ($return_id) { ?>
          <div class="tab-pane" id="tab-history">
            <div id="history"></div>
            <br />
            <fieldset>
              <legend><?php echo $text_history; ?></legend>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-return-status"><?php echo $entry_return_status; ?></label>
                <div class="col-sm-10">
                  <select name="return_status_id" id="input-return-status" class="form-control">
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
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
                <div class="col-sm-10">
                  <input type="checkbox" name="notify" value="1" id="input-notify" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-history-comment"><?php echo $entry_comment; ?></label>
                <div class="col-sm-10">
                  <textarea name="history_comment" rows="8" id="input-history-comment" class="form-control"></textarea>
                </div>
              </div>
              <div class="text-right">
                <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
              </div>
            </fieldset>
          </div>
          <?php } ?>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('input[name=\'customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
		$('input[name=\'firstname\']').val(item['firstname']);
		$('input[name=\'lastname\']').val(item['lastname']);
		$('input[name=\'email\']').val(item['email']);
		$('input[name=\'telephone\']').val(item['telephone']);
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

$('#history').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();
	
	$('#history').load(this.href);
});			

$('#history').load('index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>');

$('#button-history').on('click', function(e) {
  e.preventDefault();

	$.ajax({
		url: 'index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'return_status_id=' + encodeURIComponent($('select[name=\'return_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'history_comment\']').val()),
		beforeSend: function() {
			$('#button-history').button('loading');	
		},
		complete: function() {
			$('#button-history').button('reset');	
		},
		success: function(html) {
			$('.alert').remove();
			
			$('#history').html(html);
			
			$('textarea[name=\'history_comment\']').val('');
		}
	});
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
