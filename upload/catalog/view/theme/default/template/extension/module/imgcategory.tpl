<h3 class="module-title"><span><?php echo $heading_title; ?></span></h3>
<div class="row imgcategory">
    <?php foreach ($categories as $category) { ?>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="product-thumb transition">
                <div class="image"><a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['thumb']; ?>" title="<?php echo $category['name']; ?>" alt="<?php echo $category['name']; ?>"/></a></div>
                <h4><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></h4>
            </div>
        </div>
    <?php } ?>
</div>