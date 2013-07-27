<h3><?php echo $heading_title; ?></h3>
<div class="best-sellers-list layout-row-4">
  <?php foreach ($products as $product) { ?>
  <div class="span3">
    <div class="product-thumb transition">
      <?php if ($product['thumb']) { ?>
      <div class="image"> <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a> </div>
      <?php } else { ?>
      <div class="image"> <a href="<?php echo $product['href']; ?>"> <img src="catalog/view/theme/default/image/placeholder.png" alt="<?php echo $product['name']; ?>" /> </a> </div>
      <?php } ?>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <p><?php echo $product['description']; ?></p>
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span><span class="price-old"><?php echo $product['price']; ?></span>
          <?php } ?>
        </p>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /> </div>
        <?php } ?>
      </div>
      <div class="button-group"><a title="<?php echo $button_cart; ?>" class="add-to-cart" onclick="addToCart('<?php echo $product['product_id']; ?>');" href="#"><span class="hidden-tablet"><?php echo $button_cart; ?></span><span><i class="icon-shopping-cart visible-tablet"></i></span></a><a title="<?php echo $button_wishlist; ?>" class="tooltip-item" onclick="addToWishList('<?php echo $product['product_id']; ?>');"><i class="icon-heart"></i></a> <a title="<?php echo $button_compare; ?>" class="tooltip-item" onclick="addToCompare('<?php echo $product['product_id']; ?>');"><i class="icon-exchange"></i></a>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
