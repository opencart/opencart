<?php echo $header; ?>
<div class="container">
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<div class="row">
<?php echo $column_left; ?>
<div id="content" class="col-sm-9"><?php echo $content_top; ?>
  <h2><?php echo $heading_title; ?></h2>
  
  
  <?php if ($thumb || $description) { ?>
  <div class="category-info">
    <?php if ($thumb) { ?>
    <div class="pull-left"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
    <?php } ?>
    <?php if ($description) { ?>
    <?php echo $description; ?>
    <?php } ?>
  </div>
  <div class="clearfix"></div>
  <hr>
  
  <?php } ?>
  
  
  
  
  <?php if ($categories) { ?>
  <div class="category-list">
    <?php if (count($categories) <= 5) { ?>
    <h3><?php echo $text_refine; ?></h3>
    <div class="row">
      <div class="span3">
        <ul>
          <?php foreach ($categories as $category) { ?>
          <li> <a href="<?php echo $category['href']; ?>"> <?php echo $category['name']; ?> </a> </li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <?php } else { ?>
    <div class="row">
      <?php for ($i = 0; $i < count($categories);) { ?>
      <div class="cols-sm-3">
        <ul>
          <?php $j = $i + ceil(count($categories) / 4); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($categories[$i])) { ?>
          <li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
    </div>
    <?php } ?>
  </div>
  <?php } ?>
  
  
  <?php if ($products) { ?>
  <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div>
  <div class="product-filter">
    
    <div class="display pull-left">
      <div class="btn-group"><a id="list-view" class="btn btn-default tooltip-item" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="icon-th-list"></i></a> <a id="grid-view" class="btn square tooltip-item" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="icon-th"></i></a> </div>
    </div>
    
    
    <div class="limit"><?php echo $text_limit; ?>
      <select class="input-small" onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort"><?php echo $text_sort; ?>
      <select class="input-large" onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="clearfix"></div>
  </div>
  <?php if ($column_left) { ?>
  <div class="product-items layout-row-3 product-grid">
    <?php } elseif ($column_right) { ?>
    <div class="product-items layout-row-3 product-grid">
      <?php } else { ?>
      <div class="product-items layout-row-4 product-grid">
        <?php } ?>
        <?php foreach ($products as $product) { ?>
        <div class="span3">
          <div class="product-thumb transition">
            <?php if ($product['thumb']) { ?>
            <div class="image"> <a href="<?php echo $product['href']; ?>"> <img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /> </a> </div>
            <?php } else { ?>
            <div class="image"> <a href="<?php echo $product['href']; ?>"> <img src="catalog/view/theme/default/image/placeholder.png" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /> </a> </div>
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
                <?php if ($product['tax']) { ?>
                <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                <?php } ?>
              </p>
              <?php } ?>
              <?php if ($product['rating']) { ?>
              <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
              <?php } ?>
            </div>
            <div class="button-group"><a class="add-to-cart" onclick="addToCart('<?php echo $product['product_id']; ?>');"> <span class="hidden-tablet"><?php echo $button_cart; ?></span><span><i class="icon-shopping-cart visible-tablet"></i></span> </a> <a data-toggle="tooltip" class="tooltip-item" title="<?php echo $button_wishlist; ?>" onclick="addToWishList('<?php echo $product['product_id']; ?>');"><i class="icon-heart"></i></a> <a data-toggle="tooltip" class="tooltip-item" title="<?php echo $button_compare; ?>" onclick="addToCompare('<?php echo $product['product_id']; ?>');"><i class="icon-exchange"></i></a>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } ?>
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>