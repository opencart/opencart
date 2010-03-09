<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/setting.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_local"><?php echo $tab_local; ?></a><a tab="#tab_option"><?php echo $tab_option; ?></a><a tab="#tab_mail"><?php echo $tab_mail; ?></a><a tab="#tab_server"><?php echo $tab_server; ?></a></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab_general">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_owner; ?></td>
            <td><input type="text" name="config_owner" value="<?php echo $config_owner; ?>" />
              <?php if ($error_owner) { ?>
              <span class="error"><?php echo $error_owner; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_address; ?></td>
            <td><textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
              <?php if ($error_address) { ?>
              <span class="error"><?php echo $error_address; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_email; ?></td>
            <td><input type="text" name="config_email" value="<?php echo $config_email; ?>" />
              <?php if ($error_email) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
            <td><input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" />
              <?php if ($error_telephone) { ?>
              <span class="error"><?php echo $error_telephone; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_fax; ?></td>
            <td><input type="text" name="config_fax" value="<?php echo $config_fax; ?>" /></td>
          </tr>
        </table>
      </div>
      <div id="tab_local">
        <table class="form">
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
            <td><?php echo $entry_currency_auto; ?></td>
            <td><?php if ($config_currency_auto) { ?>
              <input type="radio" name="config_currency_auto" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_currency_auto" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_currency_auto" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_currency_auto" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_length_class; ?></td>
            <td><select name="config_length_class">
                <?php foreach ($length_classes as $length_class) { ?>
                <?php if ($length_class['unit'] == $config_length_class) { ?>
                <option value="<?php echo $length_class['unit']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $length_class['unit']; ?>"><?php echo $length_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_weight_class; ?></td>
            <td><select name="config_weight_class">
                <?php foreach ($weight_classes as $weight_class) { ?>
                <?php if ($weight_class['unit'] == $config_weight_class) { ?>
                <option value="<?php echo $weight_class['unit']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $weight_class['unit']; ?>"><?php echo $weight_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </div>
      <div id="tab_option">
        <table class="form">
          <tr>
            <td><?php echo $entry_alert_mail; ?></td>
            <td><?php if ($config_alert_mail) { ?>
              <input type="radio" name="config_alert_mail" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_alert_mail" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_alert_mail" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_alert_mail" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
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
            <td><?php echo $entry_download_status; ?></td>
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
      <div id="tab_mail">
        <table class="form">
          <tr>
            <td><?php echo $entry_mail_protocol; ?></td>
            <td><select name="config_mail_protocol">
                <?php if ($config_mail_protocol == 'mail') { ?>
                <option value="mail" selected="selected"><?php echo $text_mail; ?></option>
                <?php } else { ?>
                <option value="mail"><?php echo $text_mail; ?></option>
                <?php } ?>
                <?php if ($config_mail_protocol == 'smtp') { ?>
                <option value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
                <?php } else { ?>
                <option value="smtp"><?php echo $text_smtp; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_smtp_host; ?></td>
            <td><input type="text" name="config_smtp_host" value="<?php echo $config_smtp_host; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_smtp_username; ?></td>
            <td><input type="text" name="config_smtp_username" value="<?php echo $config_smtp_username; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_smtp_password; ?></td>
            <td><input type="text" name="config_smtp_password" value="<?php echo $config_smtp_password; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_smtp_port; ?></td>
            <td><input type="text" name="config_smtp_port" value="<?php echo $config_smtp_port; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_smtp_timeout; ?></td>
            <td><input type="text" name="config_smtp_timeout" value="<?php echo $config_smtp_timeout; ?>" /></td>
          </tr>
        </table>
      </div>
      <div id="tab_server">
        <table class="form">
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
            <td><?php echo $entry_encryption; ?></td>
            <td><input type="text" name="config_encryption" value="<?php echo $config_encryption; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_seo_url; ?></td>
            <td><?php if ($config_seo_url) { ?>
              <input type="radio" name="config_seo_url" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_seo_url" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_seo_url" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_seo_url" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_compression; ?></td>
            <td><input type="text" name="config_compression" value="<?php echo $config_compression; ?>" size="3" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_error_display; ?></td>
            <td><?php if ($config_error_display) { ?>
              <input type="radio" name="config_error_display" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_error_display" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_error_display" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_error_display" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_error_log; ?></td>
            <td><?php if ($config_error_log) { ?>
              <input type="radio" name="config_error_log" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_error_log" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_error_log" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_error_log" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_error_filename; ?></td>
            <td><input type="text" name="config_error_filename" value="<?php echo $config_error_filename; ?>" />
              <?php if ($error_error_filename) { ?>
              <span class="error"><?php echo $error_error_filename; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$.tabs('#tabs a');
//--></script>
<?php echo $footer; ?>