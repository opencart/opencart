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
    <label class="col-sm-2 control-label" for="tracking-no"><?php echo $text_ajax_tracking; ?></label>
    <div class="col-sm-10">
      <input type="text" name="tracking_no" value="<?php if (isset($order_info['tracking_no'])){ echo $order_info['tracking_no']; } ?>" placeholder="<?php echo $text_ajax_tracking; ?>" id="tracking-no" class="form-control openbay-data" />
    </div>
  </div>

  <input type="hidden" value="eBay" id="order-channel"/>
</div>

<script type="text/javascript">
  function verifyStatusChange() {
  }
</script>