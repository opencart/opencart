<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <div class="vtabs"><a href="#tab-return"><?php echo $tab_return; ?></a><a href="#tab-product"><?php echo $tab_product; ?></a><a href="#tab-history"><?php echo $tab_return_history; ?></a></div>
      <div id="tab-return" class="vtabs-content">
        <table class="form">
          <tr>
            <td><?php echo $text_return_id; ?></td>
            <td><?php echo $return_id; ?></td>
          </tr>
          <?php if ($order) { ?>
          <tr>
            <td><?php echo $text_order_id; ?></td>
            <td><a href="<?php echo $order; ?>"><?php echo $order_id; ?></a></td>
          </tr>
          <?php } else { ?>
          <tr>
            <td><?php echo $text_order_id; ?></td>
            <td><?php echo $order_id; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_date_ordered; ?></td>
            <td><?php echo $date_ordered; ?></td>
          </tr>
          <?php if ($customer) { ?>
          <tr>
            <td><?php echo $text_customer; ?></td>
            <td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
          </tr>
          <?php } else { ?>
          <tr>
            <td><?php echo $text_customer; ?></td>
            <td><?php echo $firstname; ?> <?php echo $lastname; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_email; ?></td>
            <td><?php echo $email; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_telephone; ?></td>
            <td><?php echo $telephone; ?></td>
          </tr>
          <?php if ($return_status) { ?>
          <tr>
            <td><?php echo $text_return_status; ?></td>
            <td id="return-status"><?php echo $return_status; ?></td>
          </tr>
          <?php } ?>
          <?php if ($comment) { ?>
          <tr>
            <td><?php echo $text_comment; ?></td>
            <td><?php echo $comment; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_date_added; ?></td>
            <td><?php echo $date_added; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_date_modified; ?></td>
            <td><?php echo $date_modified; ?></td>
          </tr>
        </table>
      </div>
      <div id="tab-product" class="vtabs-content">
        <table id="product" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_product; ?></td>
              <td class="left"><?php echo $column_model; ?></td>
              <td class="right"><?php echo $column_quantity; ?></td>
              <td class="left"><?php echo $column_reason; ?></td>
              <td class="left"><?php echo $column_opened; ?></td>
              <td class="left"><?php echo $column_comment; ?></td>
              <td class="left"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <?php foreach ($return_products as $return_product) { ?>
          <tbody>
            <tr>
              <td class="left"><?php echo $return_product['name']; ?></td>
              <td class="left"><?php echo $return_product['model']; ?></td>
              <td class="right"><?php echo $return_product['quantity']; ?></td>
              <td class="left"><?php echo $return_product['reason']; ?></td>
              <td class="left"><?php echo $return_product['opened'] ? $text_yes : $text_no; ?></td>
              <td class="left"><?php echo $return_product['comment']; ?></td>
              <td class="left"><select name="return_product[<?php echo $return_product['return_product_id']; ?>][return_action_id]">
                  <option value="0"></option>
                  <?php foreach ($return_actions as $return_action) { ?>
                  <?php if ($return_action['return_action_id'] == $return_product['return_action_id']) { ?>
                  <option value="<?php echo $return_action['return_action_id']; ?>" selected="selected"><?php echo $return_action['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $return_action['return_action_id']; ?>"><?php echo $return_action['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
          </tbody>
          <?php } ?>
        </table>
      </div>
      <div id="tab-history" class="vtabs-content">
        <div id="history"></div>
        <table class="form">
          <tr>
            <td><?php echo $entry_return_status; ?></td>
            <td><select name="return_status_id">
                <?php foreach ($return_statuses as $return_status) { ?>
                <?php if ($return_status['return_status_id'] == $return_status_id) { ?>
                <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_notify; ?></td>
            <td><input type="checkbox" name="notify" value="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_comment; ?></td>
            <td><textarea name="comment" cols="40" rows="8" style="width: 99%"></textarea>
              <div style="margin-top: 10px; text-align: right;"><a onclick="history();" id="button-history" class="button"><span><?php echo $button_add_history; ?></span></a></div></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#product select').bind('change', function() {
	var element = this;
	 
	$.ajax({
		type: 'POST',
		url: 'index.php?route=sale/return/product&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>',
		dataType: 'json',
		data: $('#product select'),
		beforeSend: function() {
			$(element).after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {
			$('.success, .warning').remove();
			
			if (json['error']) {
				$('#product').before('<div class="warning">' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('#product').before('<div class="success">' + json['success'] + '</div>');
			}
		}
	});	
});

$('#history .pagination a').live('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>');

function history() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>',
		dataType: 'html',
		data: 'return_status_id=' + encodeURIComponent($('select[name=\'return_status_id\']').val()) + '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0) + '&append=' + encodeURIComponent($('input[name=\'append\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-history').attr('disabled', true);
			$('#history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-history').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(html) {
			$('#history').html(html);
			
			$('textarea[name=\'comment\']').val(''); 
			
			$('#return-status').html($('select[name=\'return_status_id\'] option:selected').text());
		}
	});
}
//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script> 
<?php echo $footer; ?>