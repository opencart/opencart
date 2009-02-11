<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_send"></span><span class="button_middle"><?php echo $button_send; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"> <?php echo $entry_to; ?><br />
    <select name="to">
      <?php if ($to == 'newsletter') { ?>
      <option value="newsletter" selected="selected"><?php echo $text_newsletter; ?></option>
      <?php } else { ?>
      <option value="newsletter"><?php echo $text_newsletter; ?></option>
      <?php } ?>
      <?php if ($to == 'customer') { ?>
      <option value="customer" selected="selected"><?php echo $text_customer; ?></option>
      <?php } else { ?>
      <option value="customer"><?php echo $text_customer; ?></option>
      <?php } ?>
      <?php foreach ($customers as $customer) { ?>
      <?php if ($customer['customer_id'] == $to) { ?>
      <option value="<?php echo $customer['customer_id']; ?>" selected="selected"><?php echo $customer['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <span class="required">*</span> <?php echo $entry_subject; ?><br />
    <input name="subject" value="<?php echo $subject; ?>" />
    <br />
    <?php if ($error_subject) { ?>
    <span class="error"><?php echo $error_subject; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_message; ?><br />
    <textarea name="message" id="message"><?php echo $message; ?></textarea>
    <?php if ($error_message) { ?>
    <span class="error"><?php echo $error_message; ?></span>
    <?php } ?>
  </div>
</form>
<script type="text/javascript" src="view/javascript/fckeditor/fckeditor.js"></script>
<script type="text/javascript"><!--
var sBasePath           = document.location.href.replace(/index\.php.*/, 'view/javascript/fckeditor/');
var oFCKeditor          = new FCKeditor('message');
	oFCKeditor.BasePath = sBasePath;
	oFCKeditor.Value	= document.getElementById('message').value;
	oFCKeditor.Width    = '100%';
	oFCKeditor.Height   = '300';
	oFCKeditor.ReplaceTextarea();
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>