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
    <h1 style="background-image: url('view/image/backup.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#restore').submit();" class="button"><span><?php echo $button_restore; ?></span></a><a onclick="$('#backup').submit();" class="button"><span><?php echo $button_backup; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="restore">
      <table class="form">
        <tr>
          <td><?php echo $entry_restore; ?></td>
          <td><input type="file" name="import" /></td>
        </tr>
      </table>
    </form>
    <form action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" id="backup">
      <table class="form">
        <tr>
          <td><?php echo $entry_backup; ?></td>
          <td><div class="scrollbox" style="margin-bottom: 5px;">
              <?php $class = 'odd'; ?>
              <?php foreach ($tables as $table) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked="checked" />
                <?php echo $table; ?> </div>
              <?php } ?>
            </div>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>