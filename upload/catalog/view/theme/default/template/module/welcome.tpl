<?php if ($box_status) { ?>
<div class="box">
  <div class="box-heading"><?php echo $heading; ?></div>
  <div class="box-content"><?php echo $message; ?></div>
</div>
<?php } else { ?>
<div class="welcome"><?php echo $heading; ?></div>
<?php echo $message; ?>
<?php } ?>