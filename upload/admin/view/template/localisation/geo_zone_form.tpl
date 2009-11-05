<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input type="text" name="name" value="<?php echo $name; ?>" />
          <br />
          <?php if ($error_name) { ?>
          <span class="error"><?php echo $error_name; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_description; ?></td>
        <td><input type="text" name="description" value="<?php echo $description; ?>" />
          <br />
          <?php if ($error_description) { ?>
          <span class="error"><?php echo $error_description; ?></span>
          <?php } ?></td>
      </tr>
    </table>
    <br />
    <div id="zone_to_geo_zone">
      <?php $zone_to_geo_zone_row = 0; ?>
      <?php foreach ($zone_to_geo_zones as $zone_to_geo_zone) { ?>
      <table class="green" id="zone_to_geo_zone_row<?php echo $zone_to_geo_zone_row; ?>">
        <tr>
          <td><?php echo $entry_country; ?><br />
            <select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][country_id]" id="country<?php echo $zone_to_geo_zone_row; ?>" onchange="$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('index.php?route=localisation/geo_zone/zone&country_id=' + this.value + '&zone_id=0');">
              <?php foreach ($countries as $country) { ?>
              <?php  if ($country['country_id'] == $zone_to_geo_zone['country_id']) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td rowspan="2"><a onclick="$('#zone_to_geo_zone_row<?php echo $zone_to_geo_zone_row; ?>').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>
        </tr>
        <tr>
          <td><?php echo $entry_zone; ?><br />
            <select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][zone_id]" id="zone<?php echo $zone_to_geo_zone_row; ?>">
            </select></td>
        </tr>
      </table>
      <script type="text/javascript"><!--
	  $('#zone<?php echo $zone_to_geo_zone_row; ?>').load('index.php?route=localisation/geo_zone/zone&country_id=<?php echo $zone_to_geo_zone['country_id']; ?>&zone_id=<?php echo $zone_to_geo_zone['zone_id']; ?>');
	  //--></script>
      <?php $zone_to_geo_zone_row++; ?>
      <?php } ?>
    </div>
    <a onclick="addGeoZone();" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_add_geo_zone; ?></span><span class="button_right"></span></a></div>
</form>
<script type="text/javascript"><!--
var zone_to_geo_zone_row = <?php echo $zone_to_geo_zone_row; ?>;

function addGeoZone() {
	html  = '<table class="green" id="zone_to_geo_zone_row' + zone_to_geo_zone_row + '">';
	html += '<tr>';
	html += '<td><?php echo $entry_country; ?><br /><select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][country_id]" id="country' + zone_to_geo_zone_row + '" onchange="$(\'#zone' + zone_to_geo_zone_row + '\').load(\'index.php?route=localisation/geo_zone/zone&country_id=\' + this.value + \'&zone_id=0\');">';
	<?php foreach ($countries as $country) { ?>
	html += '<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
	<?php } ?>   
	html += '</select></td>';
	html += '<td rowspan="2"><a onclick="$(\'#zone_to_geo_zone_row' + zone_to_geo_zone_row + '\').remove();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_remove; ?></span><span class="button_right"></span></a></td>';
	html += '</tr>';
	html += '<tr>';
	html += '<td><?php echo $entry_zone; ?><br /><select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][zone_id]" id="zone' + zone_to_geo_zone_row + '"></select></td>';
	html += '</tr>';
	html += '</table>';
	
	$('#zone_to_geo_zone').append(html);
		
	$('#zone' + zone_to_geo_zone_row).load('index.php?route=localisation/geo_zone/zone&country_id=' + $('#country' + zone_to_geo_zone_row).attr('value') + '&zone_id=0');
	
	zone_to_geo_zone_row++;
}
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; ?>