<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="middle">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="guest">
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_your_details; ?></b>
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
            <td><span class="required">*</span> <?php echo $entry_email; ?></td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" />
              <?php if ($error_email) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
            <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
              <?php if ($error_telephone) { ?>
              <span class="error"><?php echo $error_telephone; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_fax; ?></td>
            <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
          </tr>
        </table>
      </div>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_your_address; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table>
          <tr>
            <td width="150"><?php echo $entry_company; ?></td>
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
            <td><span class="required">*</span> <?php echo $entry_city; ?></td>
            <td><input type="text" name="city" value="<?php echo $city; ?>" />
              <?php if ($error_city) { ?>
              <span class="error"><?php echo $error_city; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_postcode; ?></td>
            <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" onblur="getMethods();" /></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_country; ?></td>
            <td><select name="country_id" id="country_id" onchange="$('select[name=\'zone_id\']').load('index.php?route=checkout/guest/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>'); getMethods();">
                <option value="FALSE"><?php echo $text_select; ?></option>
                <?php foreach ($countries as $country) { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
              </select>
              <?php if ($error_country) { ?>
              <span class="error"><?php echo $error_country; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
            <td><select name="zone_id" onchange="getMethods();">
              </select>
              <?php if ($error_zone) { ?>
              <span class="error"><?php echo $error_zone; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <div id="shipping"></div>
      <div id="payment"></div>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_comments; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <textarea name="comment" rows="8" style="width: 99%;"><?php echo $comment; ?></textarea>
      </div>
      <?php if ($text_agree) { ?>
      <div class="buttons">
        <table>
          <tr>
            <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
            <td align="right" style="padding-right: 5px;"><?php echo $text_agree; ?></td>
            <td width="5" style="padding-right: 10px;"><?php if ($agree) { ?>
              <input type="checkbox" name="agree" value="1" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="agree" value="1" />
              <?php } ?></td>
            <td align="right" width="5"><a onclick="$('#guest').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
      <?php } else { ?>
      <div class="buttons">
        <table>
          <tr>
            <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
            <td align="right"><a onclick="$('#guest').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
      <?php } ?>
    </form>
  </div>
  <div class="bottom">&nbsp;</div>
  <script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=checkout/guest/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');

$('#country_id').attr('value', '<?php echo $country_id; ?>');

function getMethods() {
	<?php if ($shipping) { ?>
	$.ajax({
		type: 'POST',
		url: 'index.php?route=checkout/guest/shipping',
		data: 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val()) + '&shipping_method=<?php echo $shipping_method; ?>',
		dataType: 'html',
		success: function(html) {
			$('#shipping').html(html);
		}
	});
	<?php } ?>
	$.ajax({
		type: 'POST',
		url: 'index.php?route=checkout/guest/payment',
		data: 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&payment_method=<?php echo $payment_method; ?>',
		dataType: 'html',
		success: function(html) {
			$('#payment').html(html);
		}
	});	
}

getMethods();
//--></script>
</div>
<?php echo $footer; ?> 