<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/country.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
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
          <td><span class="required">*</span> <?php echo $entry_description; ?></td>
          <td><input type="text" name="description" value="<?php echo $description; ?>" />
            <?php if ($error_description) { ?>
            <span class="error"><?php echo $error_description; ?></span>
            <?php } ?></td>
        </tr>
      </table>
      <br />
      <table id="zone_to_geo_zone" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_country; ?></td>
            <td class="left"><?php echo $entry_zone; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $zone_to_geo_zone_row = 0; ?>
        <?php foreach ($zone_to_geo_zones as $zone_to_geo_zone) { ?>
        <tbody id="zone_to_geo_zone_row<?php echo $zone_to_geo_zone_row; ?>">
          <tr>
            <td class="left"><select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][country_id]" id="country<?php echo $zone_to_geo_zone_row; ?>" onchange="$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=0');">
                <?php foreach ($countries as $country) { ?>
                <?php  if ($country['country_id'] == $zone_to_geo_zone['country_id']) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="left"><select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][zone_id]" id="zone<?php echo $zone_to_geo_zone_row; ?>">
              </select></td>
            <td class="left"><a onclick="$('#zone_to_geo_zone_row<?php echo $zone_to_geo_zone_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $zone_to_geo_zone_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="2"></td>
            <td class="left"><a onclick="addGeoZone();" class="button"><span><?php echo $button_add_geo_zone; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$('#zone_id').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + $('#country_id').attr('value') + '&zone_id=0');
//--></script>
<?php $zone_to_geo_zone_row = 0; ?>
<?php foreach ($zone_to_geo_zones as $zone_to_geo_zone) { ?>
<script type="text/javascript"><!--
$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=<?php echo $zone_to_geo_zone['country_id']; ?>&zone_id=<?php echo $zone_to_geo_zone['zone_id']; ?>');
//--></script>
<?php $zone_to_geo_zone_row++; ?>
<?php } ?>
<script type="text/javascript"><!--
var zone_to_geo_zone_row = <?php echo $zone_to_geo_zone_row; ?>;

function addGeoZone() {
	html  = '<tbody id="zone_to_geo_zone_row' + zone_to_geo_zone_row + '">';
	html += '<tr>';
	html += '<td class="left"><select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][country_id]" id="country' + zone_to_geo_zone_row + '" onchange="$(\'#zone' + zone_to_geo_zone_row + '\').load(\'index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=\' + this.value + \'&zone_id=0\');">';
	<?php foreach ($countries as $country) { ?>
	html += '<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
	<?php } ?>   
	html += '</select></td>';
	html += '<td class="left"><select name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][zone_id]" id="zone' + zone_to_geo_zone_row + '"></select></td>';
	html += '<td class="left"><a onclick="$(\'#zone_to_geo_zone_row' + zone_to_geo_zone_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>';
	
	$('#zone_to_geo_zone > tfoot').before(html);
		
	$('#zone' + zone_to_geo_zone_row).load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + $('#country' + zone_to_geo_zone_row).attr('value') + '&zone_id=0');
	
	zone_to_geo_zone_row++;
}
//--></script>
<?php echo $footer; ?>