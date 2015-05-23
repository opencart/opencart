<div class="buttons clearfix">
  <div class="pull-right">
    <?php foreach ($buttons as $button) { ?>
      <a href="<?php echo $button['link']; ?>" class="btn btn-primary"><?php echo $button['text']; ?></a>
    <?php } ?>
  </div>
</div>