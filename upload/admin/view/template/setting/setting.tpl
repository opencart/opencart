<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_shop"><?php echo $tab_shop; ?></a><a tab="#tab_local"><?php echo $tab_local; ?></a><a tab="#tab_option"><?php echo $tab_option; ?></a><a tab="#tab_mail"><?php echo $tab_mail; ?></a><a tab="#tab_cache"><?php echo $tab_cache; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_shop" class="page">
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_store; ?></td>
        <td><input type="text" name="config_store" value="<?php echo $config_store; ?>" />
          <br />
          <?php if (@$error_store) { ?>
          <span class="error"><?php echo $error_store; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_meta_description; ?></td>
        <td><textarea name="config_meta_description" cols="40" rows="5"><?php echo $config_meta_description; ?></textarea>
          <?php if ($error_meta_description) { ?>
          <span class="error"><?php echo $error_meta_description; ?></span>
          <?php } ?></td>
      </tr>         
      <tr>
        <td><span class="required">*</span> <?php echo $entry_owner; ?></td>
        <td><input type="text" name="config_owner" value="<?php echo $config_owner; ?>" />
          <br />
          <?php if ($error_owner) { ?>
          <span class="error"><?php echo $error_owner; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_address; ?></td>
        <td><textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
          <br />
          <?php if ($error_address) { ?>
          <span class="error"><?php echo $error_address; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_email; ?></td>
        <td><input type="text" name="config_email" value="<?php echo $config_email; ?>" />
          <br />
          <?php if ($error_email) { ?>
          <span class="error"><?php echo $error_email; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
        <td><input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" />
          <br />
          <?php if ($error_telephone) { ?>
          <span class="error"><?php echo $error_telephone; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_fax; ?></td>
        <td><input type="text" name="config_fax" value="<?php echo $config_fax; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_template; ?></td>
        <td><select name="config_template">
            <?php foreach ($templates as $template) { ?>
            <?php if ($template['value'] == $config_template) { ?>
            <option value="<?php echo $template['value']; ?>" selected="selected"><?php echo $template['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $template['value']; ?>"><?php echo $template['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_ssl; ?></td>
        <td><?php if ($config_ssl) { ?>
          <input type="radio" name="config_ssl" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_ssl" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_ssl" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_ssl" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_parse_time; ?></td>
        <td><?php if ($config_parse_time) { ?>
          <input type="radio" name="config_parse_time" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_parse_time" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_parse_time" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_parse_time" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_welcome; ?></td>
        <td><textarea name="config_welcome_<?php echo $language['language_id']; ?>" id="description<?php echo $language['language_id']; ?>"><?php echo ${'config_welcome_' . $language['language_id']}; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />
          <?php if (@${'error_welcome_' . $language['language_id']}) { ?>
          <span class="error"><?php echo ${'error_welcome_' . $language['language_id']}; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div id="tab_local" class="page">
    <table class="form">
      <tr>
        <td width="25%"><?php echo $entry_country; ?></td>
        <td><select name="config_country_id" id="country" onchange="$('#zone').load('index.php?route=setting/setting/zone&country_id=' + this.value + '&zone_id=<?php echo $config_zone_id; ?>');">
            <?php foreach ($countries as $country) { ?>
            <?php if ($country['country_id'] == $config_country_id) { ?>
            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_zone; ?></td>
        <td><select name="config_zone_id" id="zone">
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_language; ?></td>
        <td><select name="config_language">
            <?php foreach ($languages as $language) { ?>
            <?php if ($language['code'] == $config_language) { ?>
            <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_currency; ?></td>
        <td><select name="config_currency">
            <?php foreach ($currencies as $currency) { ?>
            <?php if ($currency['code'] == $config_currency) { ?>
            <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_tax; ?></td>
        <td><?php if ($config_tax) { ?>
          <input type="radio" name="config_tax" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_tax" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_tax" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_tax" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_weight; ?></td>
        <td><select name="config_weight_class_id">
            <?php foreach ($weight_classes as $weight_class) { ?>
            <?php if ($weight_class['weight_class_id'] == $config_weight_class_id) { ?>
            <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
    </table>
  </div>
  <div id="tab_option" class="page">
    <table class="form">
      <tr>
        <td width="25%"><?php echo $entry_stock_check; ?><br />
          <span class="help"><?php echo $help_stock_check; ?></span></td>
        <td><?php if ($config_stock_check) { ?>
          <input type="radio" name="config_stock_check" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_stock_check" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_stock_check" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_stock_check" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_stock_checkout; ?><br />
          <span class="help"><?php echo $help_stock_checkout; ?></span></td>
        <td><?php if ($config_stock_checkout) { ?>
          <input type="radio" name="config_stock_checkout" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_stock_checkout" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_stock_checkout" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_stock_checkout" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_stock_subtract; ?><br />
          <span class="help"><?php echo $help_stock_subtract; ?></span></td>
        <td><?php if ($config_stock_subtract) { ?>
          <input type="radio" name="config_stock_subtract" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_stock_subtract" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_stock_subtract" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_stock_subtract" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_order_status; ?></td>
        <td><select name="config_order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_stock_status; ?></td>
        <td><select name="config_stock_status_id">
            <?php foreach ($stock_statuses as $stock_status) { ?>
            <?php if ($stock_status['stock_status_id'] == $config_stock_status_id) { ?>
            <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_download; ?></td>
        <td><?php if ($config_download) { ?>
          <input type="radio" name="config_download" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_download" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_download" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_download" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_download_status; ?><br />
          <span class="help"><?php echo $help_download_status; ?></span></td>
        <td><select name="config_download_status">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $config_download_status) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
    </table>
  </div>
  <div id="tab_mail" class="page">
    <table class="form">
      <tr>
        <td colspan="2"><b><?php echo $text_account; ?></b></td>
      </tr>
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_account_subject; ?><br />
          <img src="view/image/help.png" onmouseover="toolTip('<?php echo $help_account; ?>')" onmouseout="toolTip()" /></td>
        <td><input type="text" name="config_account_subject_<?php echo $language['language_id']; ?>" value="<?php echo ${'config_account_subject_' . $language['language_id']}; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (@${'error_account_subject_' . $language['language_id']}) { ?>
          <span class="error"><?php echo ${'error_account_subject_' . $language['language_id']}; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_account_message; ?><br />
          <img src="view/image/help.png" onmouseover="toolTip('<?php echo $help_account; ?>')" onmouseout="toolTip()" /></td>
        <td><textarea name="config_account_message_<?php echo $language['language_id']; ?>" cols="80" rows="15"><?php echo ${'config_account_message_' . $language['language_id']}; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
          <?php if (@${'error_account_message_' . $language['language_id']}) { ?>
          <span class="error"><?php echo ${'error_account_message_' . $language['language_id']}; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td colspan="2"><b><?php echo $text_forgotten; ?></b></td>
      </tr>
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_forgotten_subject; ?><br />
          <img src="view/image/help.png" onmouseover="toolTip('<?php echo $help_forgotten; ?>')" onmouseout="toolTip()" /></td>
        <td><input type="text" name="config_forgotten_subject_<?php echo $language['language_id']; ?>" value="<?php echo ${'config_forgotten_subject_' . $language['language_id']}; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (@${'error_forgotten_subject_' . $language['language_id']}) { ?>
          <span class="error"><?php echo ${'error_forgotten_subject_' . $language['language_id']}; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_forgotten_message; ?><br />
          <img src="view/image/help.png" onmouseover="toolTip('<?php echo $help_forgotten; ?>')" onmouseout="toolTip()" /></td>
        <td><textarea name="config_forgotten_message_<?php echo $language['language_id']; ?>" cols="80" rows="15"><?php echo ${'config_forgotten_message_' . $language['language_id']}; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
          <?php if (@${'error_forgotten_message_' . $language['language_id']}) { ?>
          <span class="error"><?php echo ${'error_forgotten_message_' . $language['language_id']}; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td colspan="2"><b><?php echo $text_order; ?></b></td>
      </tr>
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_order_subject; ?><br />
          <img src="view/image/help.png" onmouseover="toolTip('<?php echo $help_order; ?>')" onmouseout="toolTip()" /></td>
        <td><input type="text" name="config_order_subject_<?php echo $language['language_id']; ?>" value="<?php echo ${'config_order_subject_' . $language['language_id']}; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (@${'error_order_subject_' . $language['language_id']}) { ?>
          <span class="error"><?php echo ${'error_order_subject_' . $language['language_id']}; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_order_message; ?><br />
          <img src="view/image/help.png" onmouseover="toolTip('<?php echo $help_order; ?>')" onmouseout="toolTip()" /></td>
        <td><textarea name="config_order_message_<?php echo $language['language_id']; ?>" cols="80" rows="15"><?php echo ${'config_order_message_' . $language['language_id']}; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
          <?php if (@${'error_order_message_' . $language['language_id']}) { ?>
          <span class="error"><?php echo ${'error_order_message_' . $language['language_id']}; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td colspan="2"><b><?php echo $text_update; ?></b></td>
      </tr>
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_update_subject; ?><br />
          <img src="view/image/help.png" onmouseover="toolTip('<?php echo $help_update; ?>')" onmouseout="toolTip()" /></td>
        <td><input type="text" name="config_update_subject_<?php echo $language['language_id']; ?>" value="<?php echo ${'config_update_subject_' . $language['language_id']}; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (@${'error_update_subject_' . $language['language_id']}) { ?>
          <span class="error"><?php echo ${'error_update_subject_' . $language['language_id']}; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_update_message; ?><br />
          <img src="view/image/help.png" onmouseover="toolTip('<?php echo $help_update; ?>')" onmouseout="toolTip()" /></td>
        <td><textarea name="config_update_message_<?php echo $language['language_id']; ?>" cols="80" rows="15"><?php echo ${'config_update_message_' . $language['language_id']}; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /> <br />
          <?php if (@${'error_update_message_' . $language['language_id']}) { ?>
          <span class="error"><?php echo ${'error_update_message_' . $language['language_id']}; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div id="tab_cache" class="page">
    <table class="form">
      <tr>
        <td width="25%"><?php echo $entry_cache; ?></td>
        <td><?php if ($config_cache) { ?>
          <input type="radio" name="config_cache" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_cache" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_cache" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_cache" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_compression; ?></td>
        <td><input type="text" name="config_compression" value="<?php echo $config_compression; ?>" size="3" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript" src="view/javascript/fckeditor/fckeditor.js"></script>
<script type="text/javascript"><!--
var sBasePath = document.location.href.replace(/index\.php.*/, 'view/javascript/fckeditor/');
<?php foreach ($languages as $language) { ?>
var oFCKeditor<?php echo $language['language_id']; ?>          = new FCKeditor('description<?php echo $language['language_id']; ?>');
	oFCKeditor<?php echo $language['language_id']; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $language['language_id']; ?>.Value	   = document.getElementById('description<?php echo $language['language_id']; ?>').value;
	oFCKeditor<?php echo $language['language_id']; ?>.Width    = '520';
	oFCKeditor<?php echo $language['language_id']; ?>.Height   = '300';
	oFCKeditor<?php echo $language['language_id']; ?>.ReplaceTextarea();
<?php } ?>
//--></script>
<script type="text/javascript"><!--
$('#zone').load('index.php?route=setting/setting/zone&country_id=' + $('#country').attr('value') + '&zone_id=<?php echo $config_zone_id; ?>');
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
