<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><i class="icon-edit"></i><?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="btn"><i class="icon-ok"></i><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
        <?php if ($voucher_id) { ?>
        <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_voucher_history; ?></a></li>
        <?php } ?>
      </ul>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_code; ?></td>
                <td><input type="text" name="code" value="<?php echo $code; ?>" />
                  <?php if ($error_code) { ?>
                  <span class="error"><?php echo $error_code; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_from_name; ?></td>
                <td><input type="text" name="from_name" value="<?php echo $from_name; ?>" />
                  <?php if ($error_from_name) { ?>
                  <span class="error"><?php echo $error_from_name; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_from_email; ?></td>
                <td><input type="text" name="from_email" value="<?php echo $from_email; ?>" />
                  <?php if ($error_from_email) { ?>
                  <span class="error"><?php echo $error_from_email; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_to_name; ?></td>
                <td><input type="text" name="to_name" value="<?php echo $to_name; ?>" />
                  <?php if ($error_to_name) { ?>
                  <span class="error"><?php echo $error_to_name; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_to_email; ?></td>
                <td><input type="text" name="to_email" value="<?php echo $to_email; ?>" />
                  <?php if ($error_to_email) { ?>
                  <span class="error"><?php echo $error_to_email; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_theme; ?></td>
                <td><select name="voucher_theme_id">
                    <?php foreach ($voucher_themes as $voucher_theme) { ?>
                    <?php if ($voucher_theme['voucher_theme_id'] == $voucher_theme_id) { ?>
                    <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>" selected="selected"><?php echo $voucher_theme['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>"><?php echo $voucher_theme['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_message; ?></td>
                <td><textarea name="message" cols="40" rows="5"><?php echo $message; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_amount; ?></td>
                <td><input type="text" name="amount" value="<?php echo $amount; ?>" />
                  <?php if ($error_amount) { ?>
                  <span class="error"><?php echo $error_amount; ?></span>
                  <?php } ?></td>
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
          <?php if ($voucher_id) { ?>
          <div class="tab-pane" id="tab-history">
            <div id="history"></div>
          </div>
          <?php } ?>
        </div>
      </form>
    </div>
  </div>
</div>
<?php if ($voucher_id) { ?>
<script type="text/javascript"><!--
$('#history .pagination a').on('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('index.php?route=sale/voucher/history&token=<?php echo $token; ?>&voucher_id=<?php echo $voucher_id; ?>');
//--></script>
<?php } ?>
<?php echo $footer; ?>