<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/log.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a href="<?php echo $clear; ?>" class="button"><span><?php echo $button_clear; ?></span></a></div>
  </div>
  <div class="content">
    <textarea wrap="off" style="width: 99%; height: 300px; padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF; overflow: scroll;"><?php echo $log; ?></textarea>
  </div>
</div>
<?php echo $footer; ?>