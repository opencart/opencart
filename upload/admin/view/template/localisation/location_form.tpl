<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons">
          <button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_name; ?></label>
          <div class="controls">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" />
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-address-1"><span class="required">*</span> <?php echo $entry_address_1; ?></label>
          <div class="controls">
            <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" />
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-address-2"><?php echo $entry_address_2; ?></label>
          <div class="controls">
            <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-city"><span class="required">*</span> <?php echo $entry_city; ?></label>
          <div class="controls">
            <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-postcode"><span id="postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
          <div class="controls">
            <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-country"><span class="required">*</span> <?php echo $entry_country; ?></label>
          <div class="controls">
            <select name="country_id" id="input-country">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"> <?php echo $country['name']; ?> </option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <?php if ($error_country) { ?>
            <span class="error"><?php echo $error_country; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-zone"><span class="required">*</span> <?php echo $entry_zone; ?></label>
          <div class="controls">
            <select name="zone_id" id="input-zone">
            </select>
            <?php if ($error_zone) { ?>
            <span class="error"><?php echo $error_zone; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-geocode"><span class="required">*</span> <?php echo $entry_geocode; ?></label>
          <div class="controls">
            <div class="input-append">
              <input type="text" name="geocode" value="<?php echo $geocode; ?>" placeholder="<?php echo $entry_geocode; ?>" class="span2" id="input-geocode" />
              <button type="button" onclick="getGeoCode()" class="btn"><i class="icon-search"></i> <?php echo $button_geocode; ?></button>
            </div>

            
            <a data-toggle="tooltip" title="<?php echo $help_geocode; ?>"><i class="icon-info-sign"></i></a>
            <?php if ($error_geocode) { ?>
            <span class="error"><?php echo $error_geocode; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-image"><?php echo $entry_image; ?></label>
          <div class="controls">
            <div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" class="img-polaroid" />
              <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
              <br />
              <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-open"><?php echo $entry_open; ?></label>
          <div class="controls">
            <textarea name="open" cols="40" rows="5" placeholder="<?php echo $entry_open; ?>" id="input-open"><?php echo $open; ?></textarea>

            
            <a data-toggle="tooltip" title="<?php echo $help_open; ?>"><i class="icon-info-sign"></i></a>
            </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-comment"><?php echo $entry_comment; ?></label>
          <div class="controls">
            <textarea name="comment" cols="40" rows="5" placeholder="<?php echo $entry_comment; ?>" id="input-comment"><?php echo $comment; ?></textarea>

            
            <a data-toggle="tooltip" title="<?php echo $help_comment; ?>"><i class="icon-info-sign"></i></a>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> 
<script type="text/javascript"><!--
function getGeoCode() { 
	

	var address = new Array();
	
	address[0] = $('input[name=\'address_1\']').attr('value');
	address[1] = $('input[name=\'address_2\']').attr('value');
	address[2] = $('input[name=\'city\']').attr('value');
	address[3] = $('input[name=\'postcode\']').attr('value');
	address[4] = $('select[name=\'country_id\'] option:selected').text();
	
	geocoder = new google.maps.Geocoder();
	
	geocoder.geocode({'address': address.join(', ')}, function(results, status) {
		// Make sure google returns a geocode for us  
		if (status == google.maps.GeocoderStatus.OK) {
			//  Save our geocode to variable  
			var location = results[0].geometry.location;
			
			$('input[name=\'geocode\']').val(location.toString().replace('(', '').replace(')', ''));
		} else {
			alert('<?php echo addslashes($text_geocode); ?> ' + status);
		}
		
		
	});
}    
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
    $('#dialog').remove();
    
    $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
    
    $('#dialog').dialog({
        title: '<?php echo $text_image_manager; ?>',
        close: function (event, ui) {
            if ($('#' + field).attr('value')) {
                $.ajax({
                    url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
                    dataType: 'text',
                    success: function(data) {
                        $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                    }
                });
            }
        },  
        bgiframe: false,
        width: 800,
        height: 400,
        resizable: false,
        modal: false
    });
};
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=localisation/location/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after(' <i class="icon-spinner icon-spin"></i>');
		},
		complete: function() {
			$('.icon-spinner').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json != '' && json['zone'] != '') {
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