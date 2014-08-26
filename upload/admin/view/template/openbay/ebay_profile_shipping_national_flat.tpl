<input type="hidden" value="<?php echo (int)$data['national']['flat']['count']; ?>" id="flat_count_national" />
<?php if (isset($data['national']['flat']['service_id'])) { ?>
  <?php foreach($data['national']['flat']['service_id'] as $key => $service){ ?>
    <div class="well" id="national_flat_<?php echo $key; ?>">
      <div class="row form-group">
        <div class="col-sm-1 text-right">
          <label class="control-label"><?php echo $text_shipping_service; ?></label>
        </div>
        <div class="col-sm-11">
          <select name="data[national][flat][service_id][<?php echo $key; ?>]" class="form-control">
            <?php foreach($data['national']['flat']['types']['service'] as $service_key => $service_type) { ?>
            <option value="<?php echo $service_key; ?>" <?php if ($service_key == $service) { echo ' selected'; } ?>><?php echo $service_type['description']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-1 text-right">
          <label class="control-label"><?php echo $text_shipping_first; ?></label>
        </div>
        <div class="col-sm-3">
          <input type="text" name="data[national][flat][price][<?php echo $key; ?>]" class="form-control" value="<?php echo $data['national']['flat']['price'][$key]; ?>" />
        </div>
        <div class="col-sm-2 text-right">
          <label class="control-label"><?php echo $text_shipping_add; ?></label>
        </div>
        <div class="col-sm-3">
          <input type="text" name="data[national][flat][price_additional][<?php echo $key; ?>]" class="form-control" value="<?php echo $data['national']['flat']['price_additional'][$key]; ?>" />
        </div>
        <div class="col-sm-3 pull-right text-right">
          <a onclick="removeShipping('national','<?php echo $key; ?>', 'flat');" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_delete; ?></a>
        </div>
      </div>
    </div>
  <?php } ?>
<?php } ?>