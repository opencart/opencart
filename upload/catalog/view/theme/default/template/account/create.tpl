<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="create">
    <p><?php echo $text_account_already; ?></p>
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_your_details; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
      <table width="100%">
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
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_your_address; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
      <table width="100%">
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
          <td><select name="country_id" onchange="$('#zone').load('index.php?route=account/create/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
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
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_your_password; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
      <table width="100%">
        <tr>
          <td width="150"><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="<?php echo $password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_newsletter; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
      <table width="100%">
        <tr>
          <td width="150"><?php echo $entry_newsletter; ?></td>
          <td><?php if ($newsletter == 1) { ?>
            <input type="radio" name="newsletter" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="newsletter" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="$('#create').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<div class="bottom"></div>
<script type="text/javascript"><!--
$('#zone').load('index.php?route=account/create/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script>
