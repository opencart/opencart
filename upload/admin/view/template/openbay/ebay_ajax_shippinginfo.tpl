<div id="openbayInfo">
  <div class="alert alert-warning"><?php echo $text_ajax_ebay_shipped; ?></div>

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
    <label class="col-sm-2 control-label" for="tracking_no"><?php echo $text_ajax_tracking; ?></label>
    <div class="col-sm-10">
      <input type="text" name="tracking_no" value="<?php if(isset($order_info['tracking_no'])){ echo $order_info['tracking_no']; } ?>" placeholder="<?php echo $text_ajax_tracking; ?>" id="tracking_no" class="form-control openbayData" />
    </div>
  </div>

  <input type="hidden" name="orderChannel" value="eBay" id="orderChannel"/>
</div>

<script type="text/javascript">
  function verifyStatusChange() {
  }
</script>