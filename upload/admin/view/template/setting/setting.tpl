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
<div class="tabs"><a tab="#tab_shop"><?php echo $tab_shop; ?></a><a tab="#tab_local"><?php echo $tab_local; ?></a><a tab="#tab_option"><?php echo $tab_option; ?></a><a tab="#tab_cache"><?php echo $tab_cache; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_shop" class="page"> <span class="required">*</span> <?php echo $entry_store; ?><br />
    <input type="text" name="config_store" value="<?php echo $config_store; ?>" />
    <br />
    <?php if (@$error_store) { ?>
    <span class="error"><?php echo $error_store; ?></span>
    <?php } ?>
    <br />
    <?php foreach ($languages as $language) { ?>
    <span class="required">*</span> <?php echo $entry_welcome; ?> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <textarea name="config_welcome_<?php echo $language['language_id']; ?>" id="description<?php echo $language['language_id']; ?>"><?php echo ${'config_welcome_' . $language['language_id']}; ?></textarea>
    <?php if (@${'error_welcome_' . $language['language_id']}) { ?>
    <span class="error"><?php echo ${'error_welcome_' . $language['language_id']}; ?></span>
    <?php } ?>
    &nbsp;<br />
    <?php } ?>
    <span class="required">*</span> <?php echo $entry_owner; ?><br />
    <input type="text" name="config_owner" value="<?php echo $config_owner; ?>" />
    <br />
    <?php if ($error_owner) { ?>
    <span class="error"><?php echo $error_owner; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_address; ?><br />
    <textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
    <br />
    <?php if ($error_address) { ?>
    <span class="error"><?php echo $error_address; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_email; ?><br />
    <input type="text" name="config_email" value="<?php echo $config_email; ?>" />
    <br />
    <?php if ($error_email) { ?>
    <span class="error"><?php echo $error_email; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_telephone; ?><br />
    <input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" />
    <br />
    <?php if ($error_telephone) { ?>
    <span class="error"><?php echo $error_telephone; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_fax; ?><br />
    <input type="text" name="config_fax" value="<?php echo $config_fax; ?>" />
    <br />
    <br />
    <?php echo $entry_url_alias; ?><br />
    <?php if ($config_url_alias) { ?>
    <input type="radio" name="config_url_alias" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_url_alias" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="config_url_alias" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_url_alias" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
    <br />
    <br />
    <?php echo $entry_parse_time; ?><br />
    <?php if ($config_parse_time) { ?>
    <input type="radio" name="config_parse_time" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_parse_time" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="config_parse_time" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_parse_time" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
    <br />
    <br />
    <?php echo $entry_ssl; ?><br />
    <?php if ($config_ssl) { ?>
    <input type="radio" name="config_ssl" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_ssl" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="config_ssl" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_ssl" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
  </div>
  <div id="tab_local" class="page"><?php echo $entry_country; ?><br />
    <select name="config_country_id" id="country" onchange="$('#zone').load('index.php?route=setting/setting/zone&country_id=' + this.value + '&zone_id=<?php echo $config_zone_id; ?>');">
      <?php foreach ($countries as $country) { ?>
      <?php if ($country['country_id'] == $config_country_id) { ?>
      <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_zone; ?><br />
    <select name="config_zone_id" id="zone">
    </select>
    <br />
    <br />
    <?php echo $entry_language; ?><br />
    <select name="config_language">
      <?php foreach ($languages as $language) { ?>
      <?php if ($language['code'] == $config_language) { ?>
      <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_currency; ?><br />
    <select name="config_currency">
      <?php foreach ($currencies as $currency) { ?>
      <?php if ($currency['code'] == $config_currency) { ?>
      <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_tax; ?><br />
    <?php if ($config_tax) { ?>
    <input type="radio" name="config_tax" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_tax" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="config_tax" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_tax" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
    <br />
    <br />
    <?php echo $entry_weight; ?><br />
    <select name="config_weight_class_id">
      <?php foreach ($weight_classes as $weight_class) { ?>
      <?php if ($weight_class['weight_class_id'] == $config_weight_class_id) { ?>
      <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
  </div>
  <div id="tab_option" class="page"><?php echo $entry_stock_check; ?><br />
    <?php if ($config_stock_check) { ?>
    <input type="radio" name="config_stock_check" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_stock_check" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="config_stock_check" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_stock_check" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_stock_check; ?>')" onmouseout="toolTip()" /><br />
    <br />
    <?php echo $entry_stock_checkout; ?><br />
    <?php if ($config_stock_checkout) { ?>
    <input type="radio" name="config_stock_checkout" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_stock_checkout" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="config_stock_checkout" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_stock_checkout" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_stock_checkout; ?>')" onmouseout="toolTip()" /><br />
    <br />
    <?php echo $entry_stock_subtract; ?><br />
    <?php if ($config_stock_subtract) { ?>
    <input type="radio" name="config_stock_subtract" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_stock_subtract" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="config_stock_subtract" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_stock_subtract" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_stock_subtract; ?>')" onmouseout="toolTip()" /><br />
    <br />
    <?php echo $entry_order_status; ?><br />
    <select name="config_order_status_id">
      <?php foreach ($order_statuses as $order_status) { ?>
      <?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_stock_status; ?><br />
    <select name="config_stock_status_id">
      <?php foreach ($stock_statuses as $stock_status) { ?>
      <?php if ($stock_status['stock_status_id'] == $config_stock_status_id) { ?>
      <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_download; ?><br />
    <?php if ($config_download) { ?>
    <input type="radio" name="config_download" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_download" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="config_download" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_download" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
    <br />
    <br />
    <?php echo $entry_download_status; ?><br />
    <select name="config_download_status">
      <?php foreach ($order_statuses as $order_status) { ?>
      <?php if ($order_status['order_status_id'] == $config_download_status) { ?>
      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_download_status; ?>')" onmouseout="toolTip()" /></div>
  <div id="tab_cache" class="page"> <?php echo $entry_cache; ?><br />
    <?php if ($config_cache) { ?>
    <input type="radio" name="config_cache" value="1" checked="checked" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_cache" value="0" />
    <?php echo $text_no; ?>
    <?php } else { ?>
    <input type="radio" name="config_cache" value="1" />
    <?php echo $text_yes; ?>
    <input type="radio" name="config_cache" value="0" checked="checked" />
    <?php echo $text_no; ?>
    <?php } ?>
    <br />
    <br />
    <?php echo $entry_compression; ?><br />
    <input type="text" name="config_compression" value="<?php echo $config_compression; ?>" size="3" />
  </div>
</form>
<script type="text/javascript" src="view/javascript/fckeditor/fckeditor.js"></script>
<script type="text/javascript"><!--
var sBasePath = document.location.href.replace(/index\.php.*/, 'view/javascript/fckeditor/');
<?php foreach ($languages as $language) { ?>
var oFCKeditor<?php echo $language['language_id']; ?>          = new FCKeditor('description<?php echo $language['language_id']; ?>');
	oFCKeditor<?php echo $language['language_id']; ?>.BasePath = sBasePath;
	oFCKeditor<?php echo $language['language_id']; ?>.Value	   = document.getElementById('description<?php echo $language['language_id']; ?>').value;
	oFCKeditor<?php echo $language['language_id']; ?>.Width    = '100%';
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
