<h3><?php echo $heading_title; ?></h3>
<div class="row">
  <?php foreach ($products as $product) { ?>
  <div class="col-sm-3">
    <div class="product-thumb transition">
      <?php if ($product['thumb']) { ?>
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
      <?php } else { ?>
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="catalog/view/theme/default/image/placeholder.png" alt="<?php echo $product['name']; ?>" /></a></div>
      <?php } ?>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <p><?php echo $product['description']; ?></p>
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['price']; ?></span> <span class="price-old"><?php echo $product['special']; ?></span>
          <?php } ?>
        </p>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <div class="button-group"> <a title="<?php echo $button_cart; ?>" class="add-to-cart" onclick="addToCart('<?php echo $product['product_id']; ?>');" href="#"> <span class="hidden-tablet"><?php echo $button_cart; ?></span><span><i class="fa fa-shopping-cart visible-tablet"></i></span> </a> <a data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="addToWishList('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></a> <a data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="addToCompare('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></a>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
