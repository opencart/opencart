<div id="openbayInfo">
  <div class="attention mTop15"><?php echo $lang_ajax_ebay_shipped; ?></div>
  <table class="form">
    <tbody>
      <tr>
        <td><?php echo $lang_ajax_courier; ?>:</td>
        <td>
          <select name="carrier_id" id="carrier_id" class="openbayData">
            <?php foreach($carriers as $carrier){ ?>
              <option<?php if(isset($order_info['carrier_id']) && $order_info['carrier_id'] == $carrier['description']){ echo ' selected'; } ?>><?php echo $carrier['description']; ?></option>
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td><?php echo $lang_ajax_tracking; ?>:</td>
        <td><input class="openbayData" id="tracking_no" type="text" name="tracking_no" value="<?php if(isset($order_info['tracking_no'])){ echo $order_info['tracking_no']; } ?>"></td>
      </tr>
    </tbody>
  </table>

  <input type="hidden" name="orderChannel" value="eBay" id="orderChannel" />
</div>

<script type="text/javascript">
    function verifyStatusChange(){ }
</script>