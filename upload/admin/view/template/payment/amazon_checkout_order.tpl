<legend><?php echo $text_amazon_details ?></legend>
<table class="table table-bordered">
  <tr>
    <td><?php echo $text_amazon_order_id ?></td>
    <td><?php echo $amazon_order_id ?></td>
  </tr>
</table>
<table class="table table-bordered">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_product; ?></td>
      <td class="text-left"><?php echo $column_model; ?></td>
      <td class="text-left"><?php echo $column_amazon_order_item_code; ?></td>
      <td class="text-right"><?php echo $column_quantity; ?></td>
      <td class="text-right"><?php echo $column_price; ?></td>
      <td class="text-right"><?php echo $column_total; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $product) { ?>
    <tr>
      <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
        <?php foreach ($product['option'] as $option) { ?>
        <br />
        <?php if ($option['type'] != 'file') { ?>
        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
        <?php } else { ?>
        &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
        <?php } ?>
        <?php } ?></td>
      <td class="text-left"><?php echo $product['model']; ?></td>
      <td class="text-left"><?php echo $product['amazon_order_item_code']; ?></td>
      <td class="text-right"><?php echo $product['quantity']; ?></td>
      <td class="text-right"><?php echo $product['price']; ?></td>
      <td class="text-right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<p><?php echo $help_adjustment; ?></p>
<p><?php echo $text_download; ?></p>
<p><?php echo $text_upload_template; ?></p>
<p>
  <button type="button" id="button-upload" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $text_upload; ?></button>
</p>
<table class="table table-bordered">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_submission_id; ?></td>
      <td class="text-left"><?php echo $column_status; ?></td>
      <td class="text-left"><?php echo $column_text; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($report_submissions as $report_submission) { ?>
    <tr>
      <td class="text-left"><?php echo $report_submission['submission_id'] ?></td>
      <td class="text-left"><?php echo $report_submission['status'] ?></td>
      <td class="text-left"><?php echo $report_submission['text'] ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<script type="text/javascript"><!--
$('#button-upload').on('click', function() {
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');
	
	$('#form-upload input[name=\'file\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=payment/amazon_checkout/uploadOrderAdjustment&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: new FormData($(this).parent()[0]),
			cache: false,
			contentType: false,
			processData: false,	
			beforeSend: function() {
				$('#button-upload').button('loading');
			},
			complete: function() {
				$('#button-upload').button('reset');
			},
			success: function(json) {
				$('#button-upload').attr('disabled', false);

				if (json['success']) {
					alert(json['success']);

					$.get('index.php?route=payment/amazon_checkout/addSubmission&order_id=<?php echo $order_id ?>&submission_id=' + json['submission_id'] + '&token=<?php echo $token; ?>');
				}

				if (json['error']) {
					alert(json['error']);
				}

				$('.loading').remove();	
			}
		});
	});
});
//--></script>