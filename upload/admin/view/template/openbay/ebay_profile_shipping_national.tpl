<div class="well shipping_national_<?php echo $key; ?>">
  <input type="hidden" name="service_national[<?php echo $key; ?>]" value="<?php echo $service['id']; ?>" />
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
      <label class="control-label"><?php echo $text_shipping_first; ?></label>
    </div>
    <div class="col-sm-3">
      <input type="text" name="price_national[<?php echo $key; ?>]" class="form-control" value="<?php echo $service['price']; ?>" class="form-control" />
    </div>
    <div class="col-sm-2 text-right">
      <label class="control-label"><?php echo $text_shipping_add; ?></label>
    </div>
    <div class="col-sm-3">
      <input type="text" name="priceadditional_national[<?php echo $key; ?>]" class="form-control" value="<?php echo $service['additional']; ?>" />
    </div>
    <div class="col-sm-3">
      <a onclick="removeShipping('national','<?php echo $key; ?>');" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_btn_remove; ?></a>
    </div>
  </div>
</div>