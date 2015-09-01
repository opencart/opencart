<div class="row category-layout">
  <?php foreach ($products as $product) { ?>
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
    <div class="category-thumb transition">
      <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <p><?php echo $product['description']; ?></p>
      </div>

      </div>
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
    </div>
  </div>
  <?php } ?>
</div>
