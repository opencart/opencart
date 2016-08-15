<ul>
  <li class="result-header"><?php echo $text_orders; ?></li>
  <?php if(!empty($orders)) { ?>
  <?php foreach($orders as $order) { ?>
  <li>
    <a href="<?php echo $order['url']; ?>" style="display: block; overflow: auto;">
      <i class="fa fa-money"></i>
      <span><?php echo $text_order_id; ?><?php echo $order['order_id']; ?></span>
      <?php if(!empty($order['customer']) and $order['customer'] != ' '){ ?> <span><?php echo $order['customer']; ?></span> <?php } ?>
      <span><?php echo $order['date_added']; ?></span>
    </a>
  </li>
  <?php } ?>
  <?php } else { ?>
  <li>
    <a href="javascript::void(0)" style="display: block; overflow: auto;">
      <span><?php echo $text_no_result ?></span>
    </a>
  </li>
  <?php } ?>
</ul>