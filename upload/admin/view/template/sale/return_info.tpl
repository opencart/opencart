<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="box">
    <div class="box-heading">
      <h1><i class=""></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <div class="buttons"><a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <div class="tabbable tabs-left">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-return" data-toggle="tab"><?php echo $tab_return; ?></a></li>
          <li><a href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li>
          <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
        </ul>
        <div class="tab-content">
        <h2>Return Details</h2>
          <div class="row-fluid">
            <div class="span6"><?php echo $text_return_id; ?> <?php echo $return_id; ?></div>
            <?php if ($order) { ?>
            <div class="span6"><?php echo $text_order_id; ?><a href="<?php echo $order; ?>"><?php echo $order_id; ?></a></div>
            <?php } else { ?>
            <div class="span6"><?php echo $text_order_id; ?> <?php echo $order_id; ?></div>
            <?php } ?>
          </div>
          
               <div class="row-fluid">
            <div class="span6"><?php echo $text_date_ordered; ?> <?php echo $date_ordered; ?></div>  
          
           <?php if ($customer) { ?>
          <div class="span6"><?php echo $text_customer; ?> <a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a></div>
          
          <?php } else { ?>
          
          <div class="span6"><?php echo $text_customer; ?> <?php echo $firstname; ?> <?php echo $lastname; ?></div>
          <?php } ?>
          </div>
          
            <div class="row-fluid">
            <div class="span6"><?php echo $text_email; ?> <?php echo $email; ?></div>
            <div class="span6"><?php echo $text_telephone; ?> <?php echo $telephone; ?></div>
            
            </div>
            
            
            
            <table class="form">
              <?php if ($return_status) { ?>
              <tr>
                <td><?php echo $text_return_status; ?></td>
                <td id="return-status"><?php echo $return_status; ?></td>
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
  <h2>Product</h2>
  
            <table class="form">
              <tr>
                <td><?php echo $text_product; ?></td>
                <td><?php echo $product; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_model; ?></td>
                <td><?php echo $model; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_quantity; ?></td>
                <td><?php echo $quantity; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_return_reason; ?></td>
                <td><?php echo $return_reason; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_opened; ?></td>
                <td><?php echo $opened; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_return_action; ?></td>
                <td><select name="return_action_id">
                    <option value="0"></option>
                    <?php foreach ($return_actions as $return_action) { ?>
                    <?php if ($return_action['return_action_id'] == $return_action_id) { ?>
                    <option value="<?php echo $return_action['return_action_id']; ?>" selected="selected"><?php echo $return_action['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $return_action['return_action_id']; ?>"><?php echo $return_action['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <?php if ($comment) { ?>
              <tr>
                <td><?php echo $text_comment; ?></td>
                <td><?php echo $comment; ?></td>
              </tr>
              <?php } ?>
            </table>
    <h2>Product</h2>

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
                  <div style="margin-top: 10px; text-align: right;">
                    <button id="button-history" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_history; ?></button>
                  </div></td>
              </tr>
            </table>
   
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'return_action_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/return/action&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'return_action_id=' + this.value,
		beforeSend: function() {
			$('.alert').remove();
			
			$('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		success: function(json) {
			$('.success, .warning, .attention').remove();
			
			if (json['error']) {
				$('.box').before('<div class="alert alert-error" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json['success']) {
				$('.box').before('<div class="alert alert-success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

$('#history .pagination a').on('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>');

$('#button-history').on('click', function() {
	$.ajax({
		url: 'index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'return_status_id=' + encodeURIComponent($('select[name=\'return_status_id\']').val()) + '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0) + '&append=' + encodeURIComponent($('input[name=\'append\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('.alert').remove();
			
			$('#button-history i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-history').prop('disabled', true);
		},
		complete: function() {
			$('#button-history i').replaceWith('<i class="icon-plus-sign"></i>');
			$('#button-history').prop('disabled', false);
		},
		success: function(html) {
			$('#history').html(html);
			
			$('textarea[name=\'comment\']').val(''); 
			
			$('#return-status').html($('select[name=\'return_status_id\'] option:selected').text());
		}
	});
});
//--></script> 
<?php echo $footer; ?>