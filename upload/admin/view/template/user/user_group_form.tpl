<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user_group.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_name; ?></td>
          <td><input type="text" name="name" value="<?php echo $name; ?>" />
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php  } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_access; ?></td>
          <td><div class="scrollbox">
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
            </div></td>
        </tr>
        <tr>
          <td><?php echo $entry_modify; ?></td>
          <td><div class="scrollbox">
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
            </div></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>