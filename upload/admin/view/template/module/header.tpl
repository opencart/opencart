<div class="div1">
  <div class="div2"><?php echo $text_heading; ?></div>
  <?php if ($logged) { ?>
  <div class="div3"><?php echo $user; ?> | <a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></div>
  <?php } ?>
</div>