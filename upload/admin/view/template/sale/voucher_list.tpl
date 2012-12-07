<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="document.getElementById('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'v.code') { ?>
                <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'v.from_name') { ?>
                <a href="<?php echo $sort_from; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_from; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_from; ?>"><?php echo $column_from; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'v.to_name') { ?>
                <a href="<?php echo $sort_to; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_to; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_to; ?>"><?php echo $column_to; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'v.amount') { ?>
                <a href="<?php echo $sort_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_amount; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_amount; ?>"><?php echo $column_amount; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'theme') { ?>
                <a href="<?php echo $sort_theme; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_theme; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_theme; ?>"><?php echo $column_theme; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'v.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'v.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($vouchers) { ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($voucher['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $voucher['voucher_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $voucher['voucher_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $voucher['code']; ?></td>
              <td class="left"><?php echo $voucher['from']; ?></td>
              <td class="left"><?php echo $voucher['to']; ?></td>
              <td class="right"><?php echo $voucher['amount']; ?></td>
              <td class="left"><?php echo $voucher['theme']; ?></td>
              <td class="left"><?php echo $voucher['status']; ?></td>
              <td class="left"><?php echo $voucher['date_added']; ?></td>
              <td class="right">[ <a onclick="sendVoucher('<?php echo $voucher['voucher_id']; ?>');"><?php echo $text_send; ?></a> ]
                <?php foreach ($voucher['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function sendVoucher(voucher_id) {
	$.ajax({
		url: 'index.php?route=sale/voucher/send&token=<?php echo $token; ?>&voucher_id=' + voucher_id,
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('.success, .warning').remove();
			$('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('.attention').remove();
		},
		success: function(json) {
			if (json['error']) {
				$('.box').before('<div class="warning">' + json['error'] + '</div>');
			}
			
			if (json['success']) {
				$('.box').before('<div class="success">' + json['success'] + '</div>');
			}		
		}
	});
}
//--></script> 
<?php echo $footer; ?>