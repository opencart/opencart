<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/location.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_image; ?></td>
            <td><div class="image"><img src="<?php echo $image; ?>" alt="" id="thumb-logo" />
                <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                <br />
                <a onclick="image_upload('image', 'thumb-image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-image').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
          </tr>
          <tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
            <td><input id="address_1" type="text" name="address_1" value="<?php echo $address_1; ?>" />
              <?php if ($error_address_1) { ?>
              <span class="error"><?php echo $error_address_1; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_address_2; ?></td>
            <td><input id="address_2" type="text" name="address_2" value="<?php echo $address_2; ?>" />
              <?php if ($error_address_2) { ?>
              <span class="error"><?php echo $error_address_2; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_city; ?></td>
            <td><input id="city" type="text" name="city" value="<?php echo $city; ?>" />
              <?php if ($error_city) { ?>
              <span class="error"><?php echo $error_city; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
            <td><input id="postcode" type="text" name="postcode" value="<?php echo $postcode; ?>" /></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_geocode; ?></td>
            <td><input id="geocode" type="text" name="geocode" value="<?php echo $geocode; ?>" />
              <button type="button" onclick="getGeocode()">Get Geocode</button>
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_geocode; ?></span><br />
              <div id="geocode_error"></div>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_times; ?></td>
            <td><input type="text" name="times" value="<?php echo $times; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_comment; ?></td>
            <td><input type="text" name="comment" value="<?php echo $comment; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> 
<script type="text/javascript"><!--
function getGeocode() {  
	//  Put our address isnt a string
	var address = document.getElementById('address_1').value && document.getElementById('address_2').value && document.getElementById('city').value && document.getElementById('postcode').value;
	//address.toString();
	geocoder = new google.maps.Geocoder();
	
	geocoder.geocode({'address': address}, function(results, status) {
	//    Make sure google returns a geocode for us  
	if (status == google.maps.GeocoderStatus.OK) {
		//  Save our geocode to variable  
		var geoCode =   results[0].geometry.location;
		//  replace the () with spaces for saving
		geoCodeFilter = geoCode.toString().replace('(','').replace(')','');
		
		$('#geocode').val(geoCodeFilter);
	} else {
		alert('Geocode was not successful for the following reason: ' + status);
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
<?php echo $footer; ?>