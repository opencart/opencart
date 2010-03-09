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
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_restore; ?></span></a><a onclick="location='<?php echo $backup; ?>'" class="button"><span><?php echo $button_backup; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td width="25%"><?php echo $entry_restore; ?></td>
          <td><input type="file" name="import" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>