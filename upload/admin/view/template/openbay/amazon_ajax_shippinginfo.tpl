<div id="openbayInfo">
  <div class="alert alert-warning"><?php echo $text_ajax_amazoneu_shipped; ?></div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="carrier_id"><?php echo $text_ajax_courier; ?></label>
    <div class="col-sm-10">
      <select name="carrier_id" id="carrier_id" class="form-control openbayData">
        <?php foreach($carriers as $carrier){ ?>
        <option <?php if(isset($order_info['carrier_id']) && $order_info['carrier_id'] == $carrier['description']){ echo ' selected'; } ?>><?php echo $carrier['description']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label"><?php echo $text_ajax_courier_other; ?></label>
    <div class="col-sm-10">
      <input type="text" name="courier_other" value="<?php if($order_info['courier_other']){ echo $order_info['courier_id']; } ?>" placeholder="<?php echo $text_ajax_courier_other; ?>" id="courier_other" class="form-control openbayData" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label"><?php echo $text_ajax_courier_other; ?></label>
    <div class="col-sm-10">
      <input type="text" name="tracking_no" value="<?php if(isset($order_info['tracking_no'])){ echo $order_info['tracking_no']; } ?>" placeholder="<?php echo $text_ajax_courier_other; ?>" id="tracking_no" class="form-control openbayData" />
    </div>
  </div>
  <input type="hidden" name="orderChannel" value="Amazon EU" id="orderChannel" />
</div>

<script type="text/javascript">
		function verifyStatusChange() {
				var courier = '';
				var courier_other = '';
				var tracking = '';

				if($('#orderChannel').val()){
						courier = $('#courier_id').val();
						courier_other = $('#courier_other').val();
						tracking = $('#tracking_no').val();

						if(courier != '' && courier_other != '') {
								alert('<?php echo $text_ajax_tracking_msg4; ?>');
								return false;
						}

						if(tracking != '' && courier == '' && courier_other == ''){
								alert('<?php echo $text_ajax_tracking_msg3; ?>');
								return false;
						}

						if((tracking.indexOf('>') != -1) || (tracking.indexOf('<') != -1)) {
								alert('<?php echo $text_ajax_tracking_msg2; ?>');
								return false;
						}
				}
		}
</script>