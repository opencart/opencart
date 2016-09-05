<div class="tile">
  <div class="tile-heading"><?php echo $heading_title; ?> <span class="pull-right">
    <?php if ($percentage > 0) { ?>
    <i class="mi mi-caret-up">arrow_drop_up</i>
    <?php } elseif ($percentage < 0) { ?>
    <i class="mi mi-caret-down">arrow_drop_down</i>
    <?php } ?>
    <?php echo $percentage; ?>%</span></div>
  <div class="tile-body"><i class="mi mi-shopping-cart">shopping_cart</i>
    <h2 class="pull-right"><?php echo $total; ?></h2>
  </div>
  <div class="tile-footer"><a href="<?php echo $order; ?>"><?php echo $text_view; ?></a></div>
</div>
