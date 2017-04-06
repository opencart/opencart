<table class="table table-bordered">
  <tr>
	<td><?php echo $column_order_id; ?></td>
	<td>
		<?php echo $transaction['order_id']; ?>
		<?php if ($cancel_action) { ?>
		<a class="btn btn-primary button-command" data-type="cancel"><?php echo $button_cancel; ?></a>
		<?php } ?>
	</td>
  </tr>
  <tr>
    <td><?php echo $column_merchant_id; ?></td>
    <td><?php echo $transaction['merchant_id']; ?></td>
  </tr>
  <tr>
	<td><?php echo $column_reference; ?></td>
	<td><?php echo $transaction['reference']; ?></td>
  </tr>
  <tr>
	<td><?php echo $column_status; ?></td>
	<td><?php echo $transaction['status']; ?></td>
  </tr>
  <tr>
    <td><?php echo $column_fraud_status; ?></td>
    <td><?php echo $transaction['fraud_status']; ?></td>
  </tr>
  <tr>
	<td><?php echo $column_merchant_reference_1; ?></td>
	<td>
		<?php if ($merchant_reference_action) { ?>
		<div class="col-sm-2">
			<input class="form-control" type="text" name="merchant_reference_1" value="<?php echo $transaction['merchant_reference_1']; ?>" />
		</div>
		<a class="btn btn-primary button-command" data-type="merchant_reference"><?php echo $button_update; ?></a>
		<?php } else { ?>
		<?php echo $transaction['merchant_reference_1']; ?>
		<?php } ?>
	</td>
  </tr>
  <tr>
	<td><?php echo $column_customer_details; ?></td>
	<td>
	  <table class="table table-bordered">
		<thead>
		  <tr>
			<td><?php echo $column_billing_address; ?></td>
			<td><?php echo $column_shipping_address; ?></td>
		  </tr>
		</thead>
		<tr>
		  <td><?php echo $transaction['billing_address_formatted']; ?></td>
		  <td><?php echo $transaction['shipping_address_formatted']; ?></td>
		</tr>
		<?php if ($address_action) { ?>
		<tr>
		  <td>
			<a class="btn btn-primary" data-toggle="modal" data-target="#billing_address"><?php echo $button_edit; ?></a>
		  </td>
		  <td>
			<a class="btn btn-primary" data-toggle="modal" data-target="#shipping_address"><?php echo $button_edit; ?></a>
		  </td>
		</tr>
		<?php } ?>
	  </table>
	</td>
  </tr>
  <tr>
	<td><?php echo $column_order_lines; ?></td>
	<td>
	  <table class="table table-bordered">
		<thead>
		  <tr>
			<td class="text-left"><?php echo $column_item_reference; ?></td>
			<td class="text-left"><?php echo $column_type; ?></td>
			<td class="text-left"><?php echo $column_quantity; ?></td>
			<td class="text-left"><?php echo $column_quantity_unit; ?></td>
			<td class="text-left"><?php echo $column_name; ?></td>
			<td class="text-left"><?php echo $column_total_amount; ?></td>
			<td class="text-left"><?php echo $column_unit_price; ?></td>
			<td class="text-left"><?php echo $column_total_discount_amount; ?></td>
			<td class="text-left"><?php echo $column_tax_rate; ?></td>
			<td class="text-left"><?php echo $column_total_tax_amount; ?></td>
		  </tr>
		</thead>
		<tbody>
			<?php foreach ($transaction['order_lines'] as $order_line) { ?>
			  <tr>
				<td><?php echo $order_line['reference']; ?></td>
				<td><?php echo $order_line['type']; ?></td>
				<td><?php echo $order_line['quantity']; ?></td>
				<td><?php echo $order_line['quantity_unit']; ?></td>
				<td><?php echo $order_line['name']; ?></td>
				<td><?php echo $order_line['total_amount']; ?></td>
				<td><?php echo $order_line['unit_price']; ?></td>
				<td><?php echo $order_line['total_discount_amount']; ?></td>
				<td><?php echo $order_line['tax_rate']; ?></td>
				<td><?php echo $order_line['total_tax_amount']; ?></td>
			  </tr>
		  <?php } ?>
		</tbody>
	  </table>
	</td>
  </tr>
  <tr>
	<td><?php echo $column_amount; ?></td>
	<td><?php echo $transaction['amount']; ?></td>
  </tr>
  <tr>
	<td><?php echo $column_authorization_remaining; ?></td>
	<td>
	  <?php echo $transaction['authorization_remaining']; ?>
	  <?php if ($release_authorization_action) { ?>
	  <a class="btn btn-primary button-command" data-type="release_authorization"><?php echo $button_release_authorization; ?></a>
	  <?php } ?>
	</td>
  </tr>
  <?php if ($transaction['authorization_expiry']) { ?>
  <tr>
	<td><?php echo $column_authorization_expiry; ?></td>
	<td>
		<?php echo $transaction['authorization_expiry']; ?>
		<?php if ($extend_authorization_action) { ?>
		<a class="btn btn-primary button-command" data-type="extend_authorization"><?php echo $button_extend_authorization; ?></a>
		<?php } ?>
	</td>
  </tr>
  <?php } ?>
  <tr>
	<td><?php echo $column_capture; ?></td>
	<td>
		<table class="table table-bordered">
		  <thead>
			<tr>
			  <td class="text-left"><?php echo $column_capture_id; ?></td>
			  <td class="text-left"><?php echo $column_date; ?></td>
			  <td class="text-left"><?php echo $column_amount; ?></td>
			  <td class="text-left"><?php echo $column_reference; ?></td>
			  <td class="text-left"><?php echo $column_action; ?></td>
			</tr>
		  </thead>
		  <tbody>
			<?php if ($captures) { ?>
			<?php foreach ($captures as $capture) { ?>
			<tr>
			  <td><?php echo $capture['capture_id']; ?></td>
			  <td><?php echo $capture['date_added']; ?></td>
			  <td><?php echo $capture['amount']; ?></td>
			  <td><?php echo $capture['reference']; ?></td>
			  <td>
				<a class="btn btn-primary button-command" data-type="trigger_send_out" data-id="<?php echo $capture['capture_id']; ?>"><?php echo $button_trigger_send_out; ?></a>
				<a class="btn btn-primary" data-toggle="modal" data-target="#capture-shipping-info-<?php echo $capture['capture_id']; ?>"><?php echo $button_edit_shipping_info; ?></a>
				<a class="btn btn-primary" data-toggle="modal" data-target="#capture-billing-address-<?php echo $capture['capture_id']; ?>"><?php echo $button_edit_billing_address; ?></a>
			  </td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
			  <td class="text-center" colspan="4"><?php echo $text_no_capture; ?></td>
			</tr>
			<?php } ?>
		  </tbody>
		  <?php if ($capture_action) { ?>
		  <tfoot>
			<tr>
			  <td colspan="4"></td>
			  <td class="text-left"><a class="btn btn-primary" data-toggle="modal" data-target="#capture"><?php echo $button_new_capture; ?></a></td>
			</tr>
		  </tfoot>
		  <?php } ?>
		</table>
	</td>
  </tr>
  <tr>
	<td><?php echo $column_refund; ?></td>
	<td>
		<table class="table table-bordered">
		  <thead>
			<tr>
			  <td class="text-left"><?php echo $column_date; ?></td>
			  <td class="text-left"><?php echo $column_amount; ?></td>
			  <td class="text-left"><?php echo $column_action; ?></td>
			</tr>
		  </thead>
		  <tbody>
			<?php if ($refunds) { ?>
			<?php foreach ($refunds as $refund) { ?>
			<tr>
			  <td><?php echo $refund['date_added']; ?></td>
			  <td><?php echo $refund['amount']; ?></td>
			</tr>
			<?php } ?>
			<?php } ?>
		  </tbody>
		  <?php if ($refund_action) { ?>
		  <tfoot>
			<tr>
			  <td colspan="2"></td>
			  <td class="text-left">
				<a class="btn btn-primary" data-toggle="modal" data-target="#refund"><?php echo $button_new_refund; ?></a>
			  </td>
			</tr>
		  </tfoot>
		  <?php } ?>
		</table>
	</td>
  </tr>
</table>

<!-- Modals -->
<div class="modal fade" id="billing_address" tabindex="-1" role="dialog" aria-labelledby="billing_address_title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $button_close; ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="billing_address_title"><?php echo $column_billing_address; ?></h4>
      </div>
	  <div class="modal-body form-horizontal">
		<div class="form-group">
		  <label class="col-sm-2 control-label" for="input-billing-address-title"><?php echo $column_title; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="title" value="<?php echo $transaction['billing_address']['title']; ?>" id="input-capture-billing-address-title" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-billing-address-given-name"><?php echo $column_given_name; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="given_name" value="<?php echo $transaction['billing_address']['given_name']; ?>" id="input-billing-address-given-name" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-billing-address-family-name"><?php echo $column_family_name; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="family_name" value="<?php echo $transaction['billing_address']['family_name']; ?>" id="input-billing-address-family-name" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-billing-address-street-address"><?php echo $column_street_address; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="street_address" value="<?php echo $transaction['billing_address']['street_address']; ?>" id="input-billing-address-street-address" class="form-control" />
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-2 control-label" for="input-billing-address-street-address2"><?php echo $column_street_address2; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="street_address2" value="<?php echo $transaction['billing_address']['street_address2']; ?>" id="input-billing-address-street-address2" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-billing-address-city"><?php echo $column_city; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="city" value="<?php echo $transaction['billing_address']['city']; ?>" id="input-billing-address-city" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-billing-address-postal-code"><?php echo $column_postal_code; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="postal_code" value="<?php echo $transaction['billing_address']['postal_code']; ?>" id="input-billing-address-postal-code" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-billing-address-region"><?php echo $column_region; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="region" value="<?php echo $transaction['billing_address']['region']; ?>" id="input-billing-address-region" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-billing-address-country"><?php echo $column_country; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="country" value="<?php echo $transaction['billing_address']['country']; ?>" id="input-billing-address-country" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-billing-address-email"><?php echo $column_email; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="email" value="<?php echo $transaction['billing_address']['email']; ?>" id="input-billing-address-email" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-billing-address-phone"><?php echo $column_phone; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="phone" value="<?php echo $transaction['billing_address']['phone']; ?>" id="input-billing-address-phone" class="form-control" />
		  </div>
		</div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_close; ?></button>
        <button type="button" class="btn btn-primary button-command" data-type="billing_address" data-modal="#billing_address"><?php echo $button_update; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="shipping_address" tabindex="-1" role="dialog" aria-labelledby="shipping_address_title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $button_close; ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="shipping_address_title"><?php echo $column_shipping_address; ?></h4>
      </div>
	  <div class="modal-body form-horizontal">
		<div class="form-group">
		  <label class="col-sm-2 control-label" for="input-shipping-address-title"><?php echo $column_title; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="title" value="<?php echo $transaction['shipping_address']['title']; ?>" id="input-capture-shipping-address-title" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-shipping-address-given-name"><?php echo $column_given_name; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="given_name" value="<?php echo $transaction['shipping_address']['given_name']; ?>" id="input-shipping-address-given-name" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-shipping-address-family-name"><?php echo $column_family_name; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="family_name" value="<?php echo $transaction['shipping_address']['family_name']; ?>" id="input-shipping-address-family-name" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-shipping-address-street-address"><?php echo $column_street_address; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="street_address" value="<?php echo $transaction['shipping_address']['street_address']; ?>" id="input-shipping-address-street-address" class="form-control" />
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-2 control-label" for="input-shipping-address-street-address2"><?php echo $column_street_address2; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="street_address2" value="<?php echo $transaction['shipping_address']['street_address2']; ?>" id="input-shipping-address-street-address2" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-shipping-address-city"><?php echo $column_city; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="city" value="<?php echo $transaction['shipping_address']['city']; ?>" id="input-shipping-address-city" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-shipping-address-postal-code"><?php echo $column_postal_code; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="postal_code" value="<?php echo $transaction['shipping_address']['postal_code']; ?>" id="input-shipping-address-postal-code" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-shipping-address-region"><?php echo $column_region; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="region" value="<?php echo $transaction['shipping_address']['region']; ?>" id="input-shipping-address-region" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-shipping-address-country"><?php echo $column_country; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="country" value="<?php echo $transaction['shipping_address']['country']; ?>" id="input-shipping-address-country" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-shipping-address-email"><?php echo $column_email; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="email" value="<?php echo $transaction['shipping_address']['email']; ?>" id="input-shipping-address-email" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-shipping-address-phone"><?php echo $column_phone; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="phone" value="<?php echo $transaction['shipping_address']['phone']; ?>" id="input-shipping-address-phone" class="form-control" />
		  </div>
		</div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_close; ?></button>
        <button type="button" class="btn btn-primary button-command" data-type="shipping_address" data-modal="#shipping_address"><?php echo $button_update; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="capture" tabindex="-1" role="dialog" aria-labelledby="capture_title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $button_close; ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="capture_title"><?php echo $text_new_capture_title; ?></h4>
      </div>
	  <div class="modal-body form-horizontal">
		<div class="form-group">
		  <label class="col-sm-2 control-label" for="input-capture-amount"><?php echo $column_amount; ?></label>
		  <div class="col-sm-10">
			<input text="text" name="capture_amount" value="<?php echo $max_capture_amount; ?>" id="input-capture-amount" class="form-control" />
		  </div>
		</div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_close; ?></button>
        <button type="button" class="btn btn-primary button-command" data-type="capture" data-modal="#capture"><?php echo $button_update; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="refund" tabindex="-1" role="dialog" aria-labelledby="refund_title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $button_close; ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="refund_title"><?php echo $text_new_refund_title; ?></h4>
      </div>
	  <div class="modal-body form-horizontal">
		<div class="form-group">
		  <label class="col-sm-2 control-label" for="input-refund-amount"><?php echo $column_amount; ?></label>
		  <div class="col-sm-10">
			<input text="text" name="refund_amount" value="<?php echo $max_refund_amount; ?>" id="input-refund-amount" class="form-control" />
		  </div>
		</div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_close; ?></button>
        <button type="button" class="btn btn-primary button-command" data-type="refund" data-modal="#refund"><?php echo $button_update; ?></button>
      </div>
    </div>
  </div>
</div>

<?php foreach ($captures as $capture) { ?>
<div class="modal fade" id="capture-shipping-info-<?php echo $capture['capture_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="capture-shipping-info-<?php echo $capture['capture_id']; ?>-title" style="">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $button_close; ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="capture-shipping-info-<?php echo $capture['capture_id']; ?>-title"><?php echo $capture['shipping_info_title']; ?></h4>
      </div>
      <div class="modal-body">
		<table class="table table-bordered shipping-info-data">
		  <tbody>
			<tr>
			  <?php foreach ($capture['shipping_info'] as $key => $shipping_info) { ?>
			  <td><?php echo $shipping_info['shipping_company']; ?></td>
			  <?php } ?>
			</tr>
			<tr>
			  <?php foreach ($capture['shipping_info'] as $key => $shipping_info) { ?>
			  <td><?php echo $shipping_info['shipping_method']; ?></td>
			  <?php } ?>
			</tr>
			<tr>
			  <?php foreach ($capture['shipping_info'] as $key => $shipping_info) { ?>
			  <td><?php echo $shipping_info['tracking_number']; ?></td>
			  <?php } ?>
			</tr>
			<tr>
			  <?php foreach ($capture['shipping_info'] as $key => $shipping_info) { ?>
			  <td><?php echo $shipping_info['tracking_uri']; ?></td>
			  <?php } ?>
			</tr>
			<tr>
			  <?php foreach ($capture['shipping_info'] as $key => $shipping_info) { ?>
			  <td><?php echo $shipping_info['return_shipping_company']; ?></td>
			  <?php } ?>
			</tr>
			<tr>
			  <?php foreach ($capture['shipping_info'] as $key => $shipping_info) { ?>
			  <td><?php echo $shipping_info['return_tracking_number']; ?></td>
			  <?php } ?>
			</tr>
			<tr>
			  <?php foreach ($capture['shipping_info'] as $key => $shipping_info) { ?>
			  <td><?php echo $shipping_info['return_tracking_uri']; ?></td>
			  <?php } ?>
			</tr>
		  </tbody>
		  <tfoot>
			<tr>
			  <td colspan="<?php echo count($capture['shipping_info']) + 1; ?>"></td>
			  <td class="text-left"><button id="add-shipping-info" type="button" onclick="addShippingInfo('#capture-shipping-info-<?php echo $capture['capture_id']; ?>');" data-toggle="tooltip" title="<?php echo $button_add_shipping_info; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
			</tr>
		  </tfoot>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_close; ?></button>
        <button type="button" class="btn btn-primary button-command" data-type="capture_shipping_info" data-id="<?php echo $capture['capture_id']; ?>" data-modal="#capture-shipping-info-<?php echo $capture['capture_id']; ?>"><?php echo $button_update; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="capture-billing-address-<?php echo $capture['capture_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="capture-billing-address-<?php echo $capture['capture_id']; ?>-title" style="">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $button_close; ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="capture-billing-address-<?php echo $capture['capture_id']; ?>-title"><?php echo $capture['billing_address_title']; ?></h4>
      </div>
      <div class="modal-body form-horizontal">
		<div class="form-group">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-title"><?php echo $column_title; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="title" value="<?php echo $capture['billing_address']['title']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-title" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-given-name"><?php echo $column_given_name; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="given_name" value="<?php echo $capture['billing_address']['given_name']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-given-name" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-family-name"><?php echo $column_family_name; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="family_name" value="<?php echo $capture['billing_address']['family_name']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-family-name" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-street-address"><?php echo $column_street_address; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="street_address" value="<?php echo $capture['billing_address']['street_address']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-street-address" class="form-control" />
		  </div>
		</div>
		<div class="form-group">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-street-address2"><?php echo $column_street_address2; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="street_address2" value="<?php echo $capture['billing_address']['street_address2']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-street-address2" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-city"><?php echo $column_city; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="city" value="<?php echo $capture['billing_address']['city']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-city" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-postal-code"><?php echo $column_postal_code; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="postal_code" value="<?php echo $capture['billing_address']['postal_code']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-postal-code" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-region"><?php echo $column_region; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="region" value="<?php echo $capture['billing_address']['region']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-region" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-country"><?php echo $column_country; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="country" value="<?php echo $capture['billing_address']['country']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-country" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-email"><?php echo $column_email; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="email" value="<?php echo $capture['billing_address']['email']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-email" class="form-control" />
		  </div>
		</div>
		<div class="form-group required">
		  <label class="col-sm-2 control-label" for="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-phone"><?php echo $column_phone; ?></label>
		  <div class="col-sm-10">
			<input type="text" name="phone" value="<?php echo $capture['billing_address']['phone']; ?>" id="input-capture-billing-address-<?php echo $capture['capture_id']; ?>-phone" class="form-control" />
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_close; ?></button>
        <button type="button" class="btn btn-primary button-command" data-type="capture_billing_address" data-id="<?php echo $capture['capture_id']; ?>" data-modal="#capture-billing-address-<?php echo $capture['capture_id']; ?>"><?php echo $button_update; ?></button>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<script type="text/javascript"><!--
$(document).off('click', '.button-command').on('click', '.button-command', function() {
	var type = $(this).attr('data-type');
	var id = $(this).attr('data-id');
	var modal = $(this).attr('data-modal');
	var confirm_text = '';
	var clicked_button = $(this);
	var data = {};

	if (type === 'cancel') {
		confirm_text = '<?php echo $text_confirm_cancel; ?>';
	} else if (type === 'capture') {
		data = $('#input-capture-amount').val();

		<?php if ($symbol_left) { ?>
		confirm_text = '<?php echo $text_confirm_capture; ?> ' + '<?php echo $symbol_left; ?>' + $('#input-capture-amount').val();
		<?php } elseif ($symbol_right) { ?>
		confirm_text = '<?php echo $text_confirm_capture; ?> ' + $('#input-capture-amount').val() + '<?php echo $symbol_right; ?>';
		<?php } ?>
	} else if (type === 'refund') {
		data = $('#input-refund-amount').val();

		<?php if ($symbol_left) { ?>
		confirm_text = '<?php echo $text_confirm_refund; ?> ' + '<?php echo $symbol_left; ?>' + $('#input-refund-amount').val();
		<?php } elseif ($symbol_right) { ?>
		confirm_text = '<?php echo $text_confirm_refund; ?> ' + $('#input-refund-amount').val() + '<?php echo $symbol_right; ?>';
		<?php } ?>
	} else if (type === 'extend_authorization') {
		confirm_text = '<?php echo $text_confirm_extend_authorization; ?>';
	} else if (type === 'merchant_reference') {
		data = $('input[name=\'merchant_reference_1\']').serialize();

		confirm_text = '<?php echo $text_confirm_merchant_reference; ?>';
	} else if (type === 'billing_address') {
		data = $('#billing_address :input').serialize();

		confirm_text = '<?php echo $text_confirm_billing_address; ?>';
	} else if (type === 'shipping_address') {
		data = $('#shipping_address :input').serialize();

		confirm_text = '<?php echo $text_confirm_shipping_address; ?>';
	} else if (type === 'release_authorization') {
		confirm_text = '<?php echo $text_confirm_release_authorization; ?>';
	} else if (type === 'capture_shipping_info') {
		data = $('#capture-shipping-info-' + id + ' :input').serialize();

		confirm_text = '<?php echo $text_confirm_shipping_info; ?>';
	} else if (type === 'capture_billing_address') {
		data = $('#capture-billing-address-' + id + ' :input').serialize();

		confirm_text = '<?php echo $text_confirm_billing_address; ?>';
	} else if (type === 'trigger_send_out') {
		confirm_text = '<?php echo $text_confirm_trigger_send_out; ?>';
	} else {
		return;
	}

	if (confirm(confirm_text)) {
		$.ajax({
			url: 'index.php?route=extension/payment/klarna_checkout/transactionCommand&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			data: {
				type: type,
				id: id,
				order_ref: '<?php echo $order_ref; ?>',
				data: data
			},
			dataType: 'json',
			beforeSend: function() {
				clicked_button.button('loading');

				$('.kc-alert').hide();

				$('.kc-alert').removeClass('alert alert-success alert-danger');
			},
			complete: function() {
				clicked_button.button('reset');

				$(modal).modal('hide');
			},
			success: function(json) {
				if (json.error) {
					$('.kc-alert').show().addClass('alert alert-danger').html('<i class="fa fa-check-circle"></i> ' + json.error);
				}

				if (json.success) {
					$('.kc-alert').show().addClass('alert alert-success').html('<i class="fa fa-exclamation-circle"></i> ' + json.success);
				}

                if (json.order_status_id) {
                    $.ajax({
                        url: '<?php echo $store_url; ?>index.php?route=api/order/history&token=' + token + '&order_id=<?php echo $order_id; ?>',
                        type: 'post',
                        dataType: 'json',
                        data: 'order_status_id=' + encodeURIComponent(json.order_status_id) + '&notify=0&override=1&comment',
                        beforeSend: function() {
                            $('#button-history').button('loading');
                        },
                        complete: function() {
                            $('#button-history').button('reset');
                        },
                        success: function(json) {
                            $('.alert').remove();

                            if (json['error']) {
                                $('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                            }

                            if (json['success']) {
                                $('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

                                $('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                                $('textarea[name=\'comment\']').val('');
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }

				setTimeout(function() {
					getTransaction('<?php echo $order_id; ?>');
				}, 300);
			}
		});
	}
});

var shipping_info_row = 0;

function addShippingInfo(id) {
	$(id + ' .shipping-info-data tbody tr:nth-child(1)').append('<td><div class="col-sm-12"><input class="form-control" type="text" name="shipping_info[' + shipping_info_row + '][shipping_company]" value="" data-id="' + shipping_info_row + '" placeholder="<?php echo $entry_shipping_company; ?>" /></div></td>');

    html = '  <td><div class="col-sm-12"><select name="shipping_info[' + shipping_info_row + '][shipping_method]" class="form-control">';
    <?php foreach ($allowed_shipping_methods as $shipping_method) { ?>
    html += '    <option value="<?php echo $shipping_method; ?>"><?php echo addslashes($shipping_method); ?></option>';
    <?php } ?>
    html += '  </select></div></td>';

	$(id + ' .shipping-info-data tbody tr:nth-child(2)').append(html);
	$(id + ' .shipping-info-data tbody tr:nth-child(3)').append('<td><div class="col-sm-12"><input class="form-control" type="text" name="shipping_info[' + shipping_info_row + '][tracking_number]" value="" data-id="' + shipping_info_row + '" placeholder="<?php echo $entry_tracking_number; ?>" /></div></td>');
	$(id + ' .shipping-info-data tbody tr:nth-child(4)').append('<td><div class="col-sm-12"><input class="form-control" type="text" name="shipping_info[' + shipping_info_row + '][tracking_uri]" value="" data-id="' + shipping_info_row + '" placeholder="<?php echo $entry_tracking_uri; ?>" /></div></td>');
	$(id + ' .shipping-info-data tbody tr:nth-child(5)').append('<td><div class="col-sm-12"><input class="form-control" type="text" name="shipping_info[' + shipping_info_row + '][return_shipping_company]" value="" data-id="' + shipping_info_row + '" placeholder="<?php echo $entry_return_shipping_company; ?>" /></div></td>');
	$(id + ' .shipping-info-data tbody tr:nth-child(6)').append('<td><div class="col-sm-12"><input class="form-control" type="text" name="shipping_info[' + shipping_info_row + '][return_tracking_number]" value="" data-id="' + shipping_info_row + '" placeholder="<?php echo $entry_return_tracking_number; ?>" /></div></td>');
	$(id + ' .shipping-info-data tbody tr:nth-child(7)').append('<td><div class="col-sm-12"><input class="form-control" type="text" name="shipping_info[' + shipping_info_row + '][return_tracking_uri]" value="" data-id="' + shipping_info_row + '" placeholder="<?php echo $entry_return_tracking_uri; ?>" /></div></td>');

	var colspan = $(id + ' .shipping-info-data tfoot tr td:nth-child(1)').attr('colspan');
	$(id + ' .shipping-info-data tfoot tr td:nth-child(1)').attr('colspan', colspan + 1);

	shipping_info_row++;
}
//--></script>