<form class="form-horizontal">
  <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-shipping-firstname"><?php echo $entry_firstname; ?></label>
    <div class="col-sm-10">
      <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-shipping-firstname" class="form-control" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-shipping-lastname"><?php echo $entry_lastname; ?></label>
    <div class="col-sm-10">
      <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-shipping-lastname" class="form-control" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="input-shipping-company"><?php echo $entry_company; ?></label>
    <div class="col-sm-10">
      <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-shipping-company" class="form-control" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-shipping-address-1"><?php echo $entry_address_1; ?></label>
    <div class="col-sm-10">
      <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-shipping-address-1" class="form-control" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="input-shipping-address-2"><?php echo $entry_address_2; ?></label>
    <div class="col-sm-10">
      <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-shipping-address-2" class="form-control" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-shipping-city"><?php echo $entry_city; ?></label>
    <div class="col-sm-10">
      <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-shipping-city" class="form-control" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-shipping-postcode"><?php echo $entry_postcode; ?></label>
    <div class="col-sm-10">
      <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-shipping-postcode" class="form-control" />
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-shipping-country"><?php echo $entry_country; ?></label>
    <div class="col-sm-10">
      <select name="country_id" id="input-shipping-country" class="form-control">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($countries as $country) { ?>
        <?php if ($country['country_id'] == $country_id) { ?>
        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-shipping-zone"><?php echo $entry_zone; ?></label>
    <div class="col-sm-10">
      <select name="zone_id" id="input-shipping-zone" class="form-control">
      </select>
    </div>
  </div>
  <?php foreach ($custom_fields as $custom_field) { ?>
  <?php if ($custom_field['location'] == 'address') { ?>
  <?php if ($custom_field['type'] == 'select') { ?>
  <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
    <label class="col-sm-2 control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
        <?php if (isset($address_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address_custom_field[$custom_field['custom_field_id']]) { ?>
        <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'radio') { ?>
  <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
    <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
        <div class="radio">
          <?php if (isset($address_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address_custom_field[$custom_field['custom_field_id']]) { ?>
          <label>
            <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
            <?php echo $custom_field_value['name']; ?></label>
          <?php } else { ?>
          <label>
            <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
            <?php echo $custom_field_value['name']; ?></label>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'checkbox') { ?>
  <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
    <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
        <div class="checkbox">
          <?php if (isset($address_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $address_custom_field[$custom_field['custom_field_id']])) { ?>
          <label>
            <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
            <?php echo $custom_field_value['name']; ?></label>
          <?php } else { ?>
          <label>
            <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
            <?php echo $custom_field_value['name']; ?></label>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'text') { ?>
  <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
    <label class="col-sm-2 control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'textarea') { ?>
  <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
    <label class="col-sm-2 control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'file') { ?>
  <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
    <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <button type="button" id="button-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
      <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : ''); ?>" />
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'date') { ?>
  <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
    <label class="col-sm-2 control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div class="input-group date">
        <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
        <span class="input-group-btn">
        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        </span></div>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'time') { ?>
  <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
    <label class="col-sm-2 control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div class="input-group time">
        <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
        <span class="input-group-btn">
        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        </span></div>
    </div>
  </div>
  <?php } ?>
  <?php if ($custom_field['type'] == 'datetime') { ?>
  <div class="form-group<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
    <label class="col-sm-2 control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
    <div class="col-sm-10">
      <div class="input-group datetime">
        <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address_custom_field[$custom_field['custom_field_id']]) ? $address_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
        <span class="input-group-btn">
        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        </span></div>
    </div>
  </div>
  <?php } ?>
  <?php } ?>
  <?php } ?>
  <div class="buttons">
    <div class="pull-right">
      <input type="button" value="<?php echo $button_continue; ?>" id="button-guest-shipping" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
<script type="text/javascript"><!--
// Sort the custom fields
$('#collapse-shipping-address .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#collapse-shipping-address .form-group').length) {
		$('#collapse-shipping-address .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#collapse-shipping-address .form-group').length) {
		$('#collapse-shipping-address .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#collapse-shipping-address .form-group').length) {
		$('#collapse-shipping-address .form-group:first').before(this);
	}
});
//--></script>
<script type="text/javascript"><!--
$('#collapse-shipping-address button[id^=\'button-shipping-custom-field\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$(node).parent().find('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input[name^=\'custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input[name^=\'custom_field\']').attr('value', json['file']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
<script type="text/javascript"><!--
$('#collapse-shipping-address select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#collapse-shipping-address select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#collapse-shipping-address input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('#collapse-shipping-address input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
          			}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('#collapse-shipping-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#collapse-shipping-address select[name=\'country_id\']').trigger('change');
//--></script>