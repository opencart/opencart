
            <div id="special" style="margin-top: 15px;"><h3><?php echo $heading_title; ?></h3> </div>
            
<div class="row">
  <?php foreach ($products as $product) { ?>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
    <div class="manufacture-thumb transition">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        </div>
    </div>
  </div>
  <?php } ?>
</div>
