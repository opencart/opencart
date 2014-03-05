
<div class="well shipping_international_<?php echo $key; ?>">
  <input type="hidden" name="service_international[<?php echo $key; ?>]" value="<?php echo $service['id']; ?>" />
  <div class="row form-group">
    <div class="col-sm-1 text-right">
      <label class="control-label"><?php echo $text_shipping_service; ?><label>
    </div>
    <div class="col-sm-11">
      <?php echo $service['name']; ?>
    </div>
  </div>
  <div class="row form-group">
    <div class="col-sm-1 text-right">
      <label class="control-label"><?php echo $text_shipping_zones; ?></label>
    </div>
    <div class="col-sm-10">
      <label class="checkbox-inline">
        <input type="checkbox" name="shipto_international[<?php echo $key; ?>][]" value="Worldwide" <?php if(in_array('Worldwide', $service['shipto'])){ echo'checked="checked"'; } ?> /> <?php echo $text_shipping_worldwide; ?>
      </label>
      <?php foreach($zones as $zone) { ?>
        <label class="checkbox-inline">
          <input type="checkbox" name="shipto_international[<?php echo $key; ?>][]" value="<?php echo $zone['shipping_location']; ?>" <?php if(in_array($zone['shipping_location'], $service['shipto'])){  echo'checked="checked"'; } ?> /> <?php echo $zone['description']; ?>
        </label>
      <?php } ?>
    </div>
  </div>
  <div class="row form-group">
    <div class="col-sm-1 text-right">
      <label class="control-label"><?php echo $text_shipping_first; ?></label>
    </div>
    <div class="col-sm-3">
      <input type="text" name="price_international[<?php echo $key; ?>]" class="form-control" value="<?php echo $service['price']; ?>" class="form-control" />
    </div>
    <div class="col-sm-2 text-right">
      <label class="control-label"><?php echo $text_shipping_add; ?></label>
    </div>
    <div class="col-sm-3">
      <input type="text" name="priceadditional_international[<?php echo $key; ?>]" class="form-control" value="<?php echo $service['additional']; ?>" />
    </div>
    <div class="col-sm-3">
      <a onclick="removeShipping('international','<?php echo $key; ?>');" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_btn_remove; ?></a>
    </div>
  </div>
</div>