<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($addresses) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="address_1">
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_entries; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
      <table width="100%">
        <?php foreach ($addresses as $address) { ?>
        <?php if ($address['address_id'] == $default) { ?>
        <tr>
          <td width="1"><input type="radio" name="address_id" value="<?php echo $address['address_id']; ?>" id="address_id[<?php echo $address['address_id']; ?>]" checked="checked" /></td>
          <td><label for="address_id[<?php echo $address['address_id']; ?>]"><?php echo $address['address']; ?></label></td>
        </tr>
        <?php } else { ?>
        <tr>
          <td width="1"><input type="radio" name="address_id" value="<?php echo $address['address_id']; ?>" id="address_id[<?php echo $address['address_id']; ?>]" /></td>
          <td><label for="address_id[<?php echo $address['address_id']; ?>]"><?php echo $address['address']; ?></label></td>
        </tr>
        <?php } ?>
        <?php } ?>
      </table>
    </div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="$('#address_1').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
  </form>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="address_2">
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_new_address; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
      <table>
        <tr>
          <td width="150"><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_company; ?></td>
          <td><input type="text" name="company" value="<?php echo $company; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" />
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_country; ?></td>
          <td><select name="country_id" id="country_id" onchange="$('#zone').load('index.php?route=checkout/address/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
              <?php foreach ($countries as $country) { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_zone; ?></td>
          <td id="zone"><select name="zone_id">
            </select></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="$('#address_2').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<div class="bottom">&nbsp;</div>
<script type="text/javascript"><!--
$('#zone').load('index.php?route=checkout/address/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');

$('#country_id').attr('value', '<?php echo $country_id; ?>');
//--></script>
