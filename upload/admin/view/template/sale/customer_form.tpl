<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/customer.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div style="display: inline-block; width: 100%;">
      <div id="vtabs" class="vtabs"><a tab="#tab_general"><?php echo $tab_general; ?></a>
        <?php $address_row = 1; ?>
        <?php foreach ($addresses as $address) { ?>
        <a id="address_<?php echo $address_row; ?>" tab="#tab_address_<?php echo $address_row; ?>"><?php echo $tab_address . ' ' . $address_row; ?><span onclick="$('#vtabs a:first').trigger('click'); $('#address_<?php echo $address_row; ?>').remove(); $('#tab_address_<?php echo $address_row; ?>').remove();" class="remove">&nbsp;</span></a>
        <?php $address_row++; ?>
        <?php } ?>
        <span id="address_add" onclick="addAddress();" class="add" style="float: right; margin-right: 14px; font-size: 13px; font-weight: bold;"><?php echo $button_add; ?></span></div>
      <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab_general" class="vtabs_page">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <span class="entry"><?php echo $entry_firstname; ?></span></td>
              <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                <?php if ($error_firstname) { ?>
                <span class="error"><?php echo $error_firstname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <span class="entry"><?php echo $entry_lastname; ?></span></td>
              <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                <?php if ($error_lastname) { ?>
                <span class="error"><?php echo $error_lastname; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_email; ?></td>
              <td><input type="text" name="email" value="<?php echo $email; ?>" />
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
              <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
                <?php if ($error_telephone) { ?>
                <span class="error"><?php echo $error_telephone; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_fax; ?></td>
              <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_password; ?></td>
              <td><input type="password" name="password" value="<?php echo $password; ?>"  />
                <br />
                <?php if ($error_password) { ?>
                <span class="error"><?php echo $error_password; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_confirm; ?></td>
              <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
                <?php if ($error_confirm) { ?>
                <span class="error"><?php echo $error_confirm; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_newsletter; ?></td>
              <td><select name="newsletter">
                  <?php if ($newsletter) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_customer_group; ?></td>
              <td><select name="customer_group_id">
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
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
          </table>
        </div>
        <?php $address_row = 1; ?>
        <?php foreach ($addresses as $address) { ?>
        <div id="tab_address_<?php echo $address_row; ?>" class="vtabs_page">
          <table class="form">
            <tr>
              <td><?php echo $entry_firstname; ?></td>
              <td><input type="text" name="addresses[<?php echo $address_row; ?>][firstname]" value="<?php echo $address['firstname']; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_lastname; ?></td>
              <td><input type="text" name="addresses[<?php echo $address_row; ?>][lastname]" value="<?php echo $address['lastname']; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_company; ?></td>
              <td><input type="text" name="addresses[<?php echo $address_row; ?>][company]" value="<?php echo $address['company']; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_address_1; ?></td>
              <td><input type="text" name="addresses[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_address_2; ?></td>
              <td><input type="text" name="addresses[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_city; ?></td>
              <td><input type="text" name="addresses[<?php echo $address_row; ?>][city]" value="<?php echo $address['city']; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_postcode; ?></td>
              <td><input type="text" name="addresses[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_country; ?></td>
              <td><select name="addresses[<?php echo $address_row; ?>][country_id]" id="addresses[<?php echo $address_row; ?>][country_id]" onchange="$('select[name=\'addresses[<?php echo $address_row; ?>][zone_id]\']').load('index.php?route=sale/customer/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $address['zone_id']; ?>');">
                  <option value="FALSE"><?php echo $text_select; ?></option>
                  <?php foreach ($countries as $country) { ?>
                  <?php if ($country['country_id'] == $address['country_id']) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <?php if ($error_country) { ?>
                <span class="error"><?php echo $error_country; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_zone; ?></td>
              <td><select name="addresses[<?php echo $address_row; ?>][zone_id]">
                </select>
                <?php if ($error_zone) { ?>
                <span class="error"><?php echo $error_zone; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_default; ?></td>
              <td>
              <?php if ((isset($address['default']) && $address['default']) || count($addresses) == 1) { ?>
              <input type="radio" name="addresses[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" /></td>
              <?php } else { ?>
              <input type="radio" name="addresses[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" /></td>
              <?php } ?>
            </tr>
          </table>
          <script type="text/javascript"><!--
		  $('select[name=\'addresses[<?php echo $address_row; ?>][zone_id]\']').load('index.php?route=sale/customer/zone&token=<?php echo $token; ?>&country_id=<?php echo $address['country_id']; ?>&zone_id=<?php echo $address['zone_id']; ?>');
		  //--></script> 
        </div>
        <?php $address_row++; ?>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var address_row = <?php echo $address_row; ?>;

function addAddress() {	
	html  = '<div id="tab_address_' + address_row + '" class="vtabs_page">';
	html += '<table class="form">'; 
	html += '<tr>';
    html += '<td><?php echo $entry_firstname; ?></td>';
    html += '<td><input type="text" name="addresses[' + address_row + '][firstname]" value="" /></td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td><?php echo $entry_lastname; ?></td>';
    html += '<td><input type="text" name="addresses[' + address_row + '][lastname]" value="" /></td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td><?php echo $entry_company; ?></td>';
    html += '<td><input type="text" name="addresses[' + address_row + '][company]" value="" /></td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td><?php echo $entry_address_1; ?></td>';
    html += '<td><input type="text" name="addresses[' + address_row + '][address_1]" value="" /></td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td><?php echo $entry_address_2; ?></td>';
    html += '<td><input type="text" name="addresses[' + address_row + '][address_2]" value="" /></td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td><?php echo $entry_city; ?></td>';
    html += '<td><input type="text" name="addresses[' + address_row + '][city]" value="" /></td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td><?php echo $entry_postcode; ?></td>';
    html += '<td><input type="text" name="addresses[' + address_row + '][postcode]" value="" /></td>';
    html += '</tr>';
    html += '<td><?php echo $entry_country; ?></td>';
    html += '<td>';
    html += '<select name="addresses[' + address_row + '][country_id]" onchange="$(\'select[name=\\\'addresses[' + address_row + '][zone_id]\\\']\').load(\'index.php?route=sale/customer/zone&token=<?php echo $token; ?>&country_id=\' + this.value + \'&zone_id=0\');">';
    html += '<option value="FALSE"><?php echo $text_select; ?></option>';
    <?php foreach ($countries as $country) { ?>
    html += '<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
    <?php } ?>
    html += '</select>';
    <?php if ($error_country) { ?>
    html += '<span class="error"><?php echo $error_country; ?></span>';
    <?php } ?>
    html += '</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td><?php echo $entry_zone; ?></td>';
    html += '<td>';
    html += '<select name="addresses[' + address_row + '][zone_id]"><option value="FALSE"><?php echo $this->language->get('text_none'); ?></option></select>';
    <?php if ($error_zone) { ?>
    html += '<span class="error"><?php echo $error_zone; ?></span>';
    <?php } ?>  
    html += '</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td><?php echo $entry_default; ?></td>';
    html += '<td><input type="radio" name="addresses[' + address_row + '][default]" value="1" /></td>';
    html += '</tr>';
    html += '</table>';
    html += '</div>';
	
	$('#form').append(html);
	
	$('#address_add').before('<a id="address_' + address_row + '" tab="#tab_address_' + address_row + '"><?php echo $tab_address; ?> ' + address_row + '<span onclick="$(\'#vtabs a:first\').trigger(\'click\'); $(\'#address_' + address_row + '\').remove(); $(\'#tab_address_' + address_row + '\').remove();" class="remove">&nbsp;</span></a>');
		
	$.tabs('.vtabs a', address_row);
	
	$('#address_' + address_row).trigger('click');
	
	address_row++;
}
//--></script> 
<script type="text/javascript"><!--
$.tabs('.vtabs a');
$('form input[type=radio]').live('click', function () {
	$('form input[type=radio]').attr('checked', false);
	$(this).attr('checked', true);
});
//--></script> 
<?php echo $footer; ?>