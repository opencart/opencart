<?php foreach ($custom_fields as $custom_field) { ?>
<div class="form-group custom-field" class="">
  <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
  <div class="col-sm-10">
    <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
      <option value=""><?php echo $text_select; ?></option>
      <?php foreach ($custom_field['value'] as $custom_field_value) { ?>
      <?php if ($custom_field_value['custom_field_value_id'] == $custom_field_value_id) { ?>
      <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
      <?php } ?>
    </select>
    <?php if () {
    <div class="text-danger">' + $('#custom-field' + custom_field['custom_field_id'] + ' .custom-field-error').html() + '</div>
  	<?php } ?>
  </div>
</div>
<?php } ?>
