<ul>
  <li class="result-header"><?php echo $text_products ?></li>
  <?php if(!empty($products)) { ?>
  <?php foreach($products as $product) { ?>
  <li>
    <a href="<?php echo $product['url']; ?>" style="display: block; overflow: auto;">
      <img src="<?php echo $product['image']; ?>"/>
      <span><?php echo $product['name']; ?></span>
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
  <li class="result-header"><?php echo $text_categories ?></li>
  <?php if(!empty($categories)) { ?>
  <?php foreach($categories as $category) { ?>
  <li>
    <a href="<?php echo $category['url']; ?>" style="display: block; overflow: auto;">
      <img src="<?php echo $category['image']; ?>"/>
      <span><?php echo $category['name']; ?></span>
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
  <li class="result-header"><?php echo $text_manufacturers ?></li>
  <?php if(!empty($manufacturers)) { ?>
  <?php foreach($manufacturers as $manufacturer) { ?>
  <li>
    <a href="<?php echo $manufacturer['url']; ?>" style="display: block; overflow: auto;">
      <img src="<?php echo $manufacturer['image']; ?>"/>
      <span><?php echo $manufacturer['name']; ?></span>
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