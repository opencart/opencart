<?php /*
<h3><?php echo $heading_title; ?></h3>
*/ ?>

<div class="row">
  <?php foreach ($products as $key => $product) { ?>
  <div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <!--Sale & Off-->
      <div class="discount">
      <?php foreach ($product['saving_images'] as $saving_image) { ?>
        <?php if(!empty($saving_image)) { ?>
        <img src="<?php echo HTTPS_SERVER . 'image/' . $saving_image; ?>" />
        <?php } ?>
      <?php } ?>
      </div>
      <div class="image">
        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
      </div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"

              <?php  if($product['ccttype']==1) { ?>
                 class="a5000"
              <?php  } else { ?>
               class="a2700"
              <?php  }  ?>

          ><?php echo $product['name']; ?></a></h4>
        <p><?php echo $product['description']; ?></p>
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
        <?php /*
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php } ?>
          <?php if ($product['tax']) { ?>
          <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
          <?php } ?>
        </p>
        <?php } ?>
        */ ?>
      </div>
      <div class="button-group">
        <button type="button" onclick="    <?php echo 'javascript:window.location='.'\'' .$product['href'].'\''  ; ?>"><i class="fa fa-eye"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_viewdetail ?></span></button>
       <?php /* >
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
        <*/ ?>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
