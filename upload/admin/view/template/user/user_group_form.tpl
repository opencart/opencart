<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"><span class="required">*</span> <?php echo $entry_name; ?><br />
    <input type="text" name="name" value="<?php echo $name; ?>" />
    <br />
    <?php if ($error_name) { ?>
    <span class="error"><?php echo $error_name; ?></span>
    <?php  } ?>
    <br />
    <?php echo $entry_access; ?><br />
    <div class="scrollbox">
      <?php $class = 'odd'; ?>
      <?php foreach ($permissions as $permission) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <div class="<?php echo $class; ?>">
        <?php if (in_array($permission, $access)) { ?>
        <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" />
        <?php echo $permission; ?>
        <?php } else { ?>
        <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" />
        <?php echo $permission; ?>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
    <br />
    <?php echo $entry_modify; ?><br />
    <div class="scrollbox">
      <?php $class = 'odd'; ?>
      <?php foreach ($permissions as $permission) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <div class="<?php echo $class; ?>">
        <?php if (in_array($permission, $modify)) { ?>
        <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked="checked" />
        <?php echo $permission; ?>
        <?php } else { ?>
        <input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" />
        <?php echo $permission; ?>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
