
            <div id="special" style="margin-top: 15px;"><h3><?php echo $heading_title; ?></h3> </div>
            
<div class="row">
  <?php foreach ($products as $product) { ?>
  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <div class="manufacturer-thumb transition">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        </div>
    </div>
  </div>
  <?php } ?>
</div>
