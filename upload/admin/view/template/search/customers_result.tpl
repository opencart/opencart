<ul>
  <li class="result-header"><?php echo $text_customers; ?></li>
  <?php if(!empty($customers)) { ?>
  <?php foreach($customers as $customer) { ?>
  <li>
    <a href="<?php echo $customer['url']; ?>" style="display: block; overflow: auto;">
      <i class="fa fa-user"></i>
      <span><?php echo $customer['name']; ?> - </span>
      <span><?php echo $customer['email']; ?></span>
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