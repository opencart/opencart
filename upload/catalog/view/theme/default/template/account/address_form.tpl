<?php echo $header; ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<div class="row"><?php echo $column_left; ?>
  <div id="content" class="span9"><?php echo $content_top; ?>
    <h2><?php echo $text_edit_address; ?></h2>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
      <div class="content">
        <fieldset>
          <div class="control-group required">
            <label class="control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="controls">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" />
              <?php if ($error_firstname) { ?>
              <div class="error"><?php echo $error_firstname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="control-group required">
            <label class="control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="controls">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" />
              <?php if ($error_lastname) { ?>
              <div class="error"><?php echo $error_lastname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="input-company"><?php echo $entry_company; ?></label>
            <div class="controls">
              <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-company" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="input-address-1"><?php echo $entry_address_1; ?></label>
            <div class="controls">
              <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" />
              <?php if ($error_address_1) { ?>
              <div class="error"><?php echo $error_address_1; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="input-address-2"><?php echo $entry_address_2; ?></label>
            <div class="controls">
              <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2" />
            </div>
          </div>
          <div class="control-group required">
            <label class="control-label" for="input-city"><?php echo $entry_city; ?> </label>
            <div class="controls">
              <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" />
              <?php if ($error_city) { ?>
              <div class="error"><?php echo $error_city; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="control-group required">
            <label class="control-label" for="input-postcode"><?php echo $entry_postcode; ?></label>
            <div class="controls">
              <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" />
              <?php if ($error_postcode) { ?>
              <div class="error"><?php echo $error_postcode; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="control-group required">
            <label class="control-label" for="input-country"><?php echo $entry_country; ?> </label>
            <div class="controls">
              <select name="country_id" id="input-country">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <?php if ($error_country) { ?>
              <div class="error"><?php echo $error_country; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="control-group required">
            <label class="control-label" for="input-zone"><?php echo $entry_zone; ?></label>
            <div class="controls">
              <select name="zone_id" id="input-zone">
              </select>
              <?php if ($error_zone) { ?>
              <div class="error"><?php echo $error_zone; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="control-group">
            <div class="control-label"><?php echo $entry_default; ?></div>
            <div class="controls">
              <?php if ($default) { ?>
              <label class="radio">
                <input type="radio" name="default" value="1" checked="checked" />
                <?php echo $text_yes; ?></label>
              <label class="radio">
                <input type="radio" name="default" value="0" />
                <?php echo $text_no; ?></label>
              <?php } else { ?>
              <label class="radio">
                <input type="radio" name="default" value="1" />
                <?php echo $text_yes; ?></label>
              <label class="radio">
                <input type="radio" name="default" value="0" checked="checked" />
                <?php echo $text_no; ?></label>
              <?php } ?>
            </div>
          </div>
        </fieldset>
      </div>
      <div class="buttons clearfix">
        <div class="pull-left"><a href="<?php echo $back; ?>" class="btn"><?php echo $button_back; ?></a></div>
        <div class="pull-right">
          <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
        </div>
      </div>
    </form>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/address/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="icon-spinner icon-spin"></i>');
		},		
		complete: function() {
			$('.icon-spinner').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#input-postcode').parent().parent().addClass('required');
			} else {
				$('#input-postcode').parent().parent().removeClass('required');
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
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
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script> 
<?php echo $footer; ?>