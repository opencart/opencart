<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"><span class="required">*</span> <?php echo $entry_firstname; ?><br />
    <input type="text" name="firstname" value="<?php echo $firstname; ?>" />
    <br />
    <?php if ($error_firstname) { ?>
    <span class="error"><?php echo $error_firstname; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_lastname; ?><br />
    <input type="text" name="lastname" value="<?php echo $lastname; ?>" />
    <br />
    <?php if ($error_lastname) { ?>
    <span class="error"><?php echo $error_lastname; ?></span>
    <?php } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_email; ?><br />
    <input type="text" name="email" value="<?php echo $email; ?>" />
    <br />
    <?php if ($error_email) { ?>
    <span class="error"><?php echo $error_email; ?></span>
    <?php  } ?>
    <br />
    <span class="required">*</span> <?php echo $entry_telephone; ?><br />
    <input type="text" name="telephone" value="<?php echo $telephone; ?>" />
    <br />
    <?php if ($error_telephone) { ?>
    <span class="error"><?php echo $error_telephone; ?></span>
    <?php  } ?>
    <br />
    <?php echo $entry_fax; ?><br />
    <input type="text" name="fax" value="<?php echo $fax; ?>" />
    <br />
    <br />
    <?php echo $entry_password; ?><br />
    <input type="password" name="password" value="<?php echo $password; ?>"  />
    <br />
    <?php if ($error_password) { ?>
    <span class="error"><?php echo $error_password; ?></span>
    <?php  } ?>
    <br />
    <?php echo $entry_confirm; ?><br />
    <input type="password" name="confirm" value="<?php echo $confirm; ?>" />
    <br />
    <?php if ($error_confirm) { ?>
    <span class="error"><?php echo $error_confirm; ?></span>
    <?php  } ?>
    <br />
    <?php echo $entry_newsletter; ?><br />
    <select name="newsletter">
      <?php if ($newsletter) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
    <br />
    <br />
    <?php echo $entry_status; ?><br />
    <select name="status">
      <?php if ($status) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>