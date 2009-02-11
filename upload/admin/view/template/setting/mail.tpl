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
<div class="tabs"><a tab="#tab_account"><?php echo $tab_account; ?></a><a tab="#tab_forgotten"><?php echo $tab_forgotten; ?></a><a tab="#tab_order"><?php echo $tab_order; ?></a><a tab="#tab_update"><?php echo $tab_update; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_account" class="page">
    <?php foreach ($languages as $language) { ?>
    <span class="required">*</span> <?php echo $entry_account_subject; ?><br />
    <input type="text" name="mail_account_subject_<?php echo $language['language_id']; ?>" value="<?php echo ${'mail_account_subject_' . $language['language_id']}; ?>" />
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_account; ?>')" onmouseout="toolTip()" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@${'error_account_subject_' . $language['language_id']}) { ?>
    <span class="error"><?php echo ${'error_account_subject_' . $language['language_id']}; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_account_message; ?><br />
    <textarea name="mail_account_message_<?php echo $language['language_id']; ?>" cols="80" rows="15"><?php echo ${'mail_account_message_' . $language['language_id']}; ?></textarea>
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_account; ?>')" onmouseout="toolTip()" /><br />
    <?php if (@${'error_account_message_' . $language['language_id']}) { ?>
    <span class="error"><?php echo ${'error_account_message_' . $language['language_id']}; ?></span>
    <?php } ?>
    <br />
    <?php } ?>
  </div>
  <div id="tab_forgotten" class="page">
    <?php foreach ($languages as $language) { ?>
    <span class="required">*</span> <?php echo $entry_forgotten_subject; ?><br />
    <input type="text" name="mail_forgotten_subject_<?php echo $language['language_id']; ?>" value="<?php echo ${'mail_forgotten_subject_' . $language['language_id']}; ?>" />
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_forgotten; ?>')" onmouseout="toolTip()" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@${'error_forgotten_subject_' . $language['language_id']}) { ?>
    <span class="error"><?php echo ${'error_forgotten_subject_' . $language['language_id']}; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_forgotten_message; ?><br />
    <textarea name="mail_forgotten_message_<?php echo $language['language_id']; ?>" cols="80" rows="15"><?php echo ${'mail_forgotten_message_' . $language['language_id']}; ?></textarea>
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_forgotten; ?>')" onmouseout="toolTip()" /><br />
    <?php if (@${'error_account_message_' . $language['language_id']}) { ?>
    <span class="error"><?php echo ${'error_forgotten_message_' . $language['language_id']}; ?></span>
    <?php } ?>
    <br />
    <?php } ?>
  </div>
  <div id="tab_order" class="page">
    <?php foreach ($languages as $language) { ?>
    <span class="required">*</span> <?php echo $entry_order_subject; ?><br />
    <input type="text" name="mail_order_subject_<?php echo $language['language_id']; ?>" value="<?php echo ${'mail_order_subject_' . $language['language_id']}; ?>" />
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_order; ?>')" onmouseout="toolTip()" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@${'error_order_subject_' . $language['language_id']}) { ?>
    <span class="error"><?php echo ${'error_order_subject_' . $language['language_id']}; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_order_message; ?><br />
    <textarea name="mail_order_message_<?php echo $language['language_id']; ?>" cols="80" rows="15"><?php echo ${'mail_order_message_' . $language['language_id']}; ?></textarea>
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_order; ?>')" onmouseout="toolTip()" /><br />
    <?php if (@${'error_order_message_' . $language['language_id']}) { ?>
    <span class="error"><?php echo ${'error_order_message_' . $language['language_id']}; ?></span>
    <?php } ?>
    <br />
    <?php } ?>
  </div>
  <div id="tab_update" class="page">
    <?php foreach ($languages as $language) { ?>
    <span class="required">*</span> <?php echo $entry_update_subject; ?><br />
    <input type="text" name="mail_update_subject_<?php echo $language['language_id']; ?>" value="<?php echo ${'mail_update_subject_' . $language['language_id']}; ?>" />
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_update; ?>')" onmouseout="toolTip()" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
    <?php if (@${'error_update_subject_' . $language['language_id']}) { ?>
    <span class="error"><?php echo ${'error_update_subject_' . $language['language_id']}; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_update_message; ?><br />
    <textarea name="mail_update_message_<?php echo $language['language_id']; ?>" cols="80" rows="15"><?php echo ${'mail_update_message_' . $language['language_id']}; ?></textarea>
    <img src="view/image/help.png" class="help" onmouseover="toolTip('<?php echo $help_update; ?>')" onmouseout="toolTip()" /><br />
    <?php if (@${'error_update_message_' . $language['language_id']}) { ?>
    <span class="error"><?php echo ${'error_update_message_' . $language['language_id']}; ?></span>
    <?php } ?>
    <br />
    <?php } ?>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>