<div id="openbay-info">
  <div class="alert alert-warning"><?php echo $text_marketplace_shipped; ?></div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="carrier-id"><?php echo $entry_courier; ?></label>
    <div class="col-sm-10">
      <select name="carrier_id" id="carrier-id" class="form-control openbay-data">
        <?php foreach($carriers as $carrier){ ?>
        <option <?php if (isset($order_info['carrier_id']) && $order_info['carrier_id'] == $carrier['description']){ echo ' selected'; } ?>><?php echo $carrier['description']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="carrier-other"><?php echo $entry_courier_other; ?></label>
    <div class="col-sm-10">
      <input type="text" name="courier_other" value="<?php if ($order_info['courier_other']){ echo $order_info['courier_id']; } ?>" placeholder="<?php echo $entry_courier_other; ?>" id="carrier-other" class="form-control openbay-data" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="tracking-no"><?php echo $entry_tracking; ?></label>
    <div class="col-sm-10">
      <input type="text" name="tracking_no" value="<?php if (isset($order_info['tracking_no'])){ echo $order_info['tracking_no']; } ?>" placeholder="<?php echo $entry_tracking; ?>" id="tracking-no" class="form-control openbay-data" />
    </div>
  </div>
  <input type="hidden" value="Amazon EU" id="order-channel" />
</div>

<script type="text/javascript">
		function verifyStatusChange() {
				var carrier = '';
				var carrier_other = '';
				var tracking = '';

				if ($('#order-channel').val()){
						carrier = $('#carrier-id').val();
            carrier_other = $('#carrier-other').val();
						tracking = $('#tracking-no').val();

						if (carrier != '' && carrier_other != '') {
								alert('<?php echo $error_tracking_custom; ?>');
								return false;
						}

						if (tracking != '' && carrier == '' && carrier_other == ''){
								alert('<?php echo $error_tracking_courier; ?>');
								return false;
						}

						if ((tracking.indexOf('>') != -1) || (tracking.indexOf('<') != -1)) {
								alert('<?php echo $error_tracking_id_format; ?>');
								return false;
						}
				}
		}
</script>