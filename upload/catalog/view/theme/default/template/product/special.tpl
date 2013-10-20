<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li> <a href="<?php echo $breadcrumb['href']; ?>"> <?php echo $breadcrumb['text']; ?> </a> </li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $cols = 6; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $cols = 9; ?>
    <?php } else { ?>
    <?php $cols = 12; ?>
    <?php } ?>    
  
  
  
  
    <div class="col-sm-<?php echo $cols; ?>"><?php echo $content_top; ?>
    
    
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($products) { ?>
      
      <p><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
      
      <div class="row">
        <div class="col-sm-2">
          <div class="btn-group">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="icon-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="icon-th"></i></button>
          </div>
        </div>
        
        <div class="col-sm-2 col-sm-offset-2 text-right">
          <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
        </div>
        
        <div class="col-sm-3 text-right">
          <select id="input-sort" class="form-control col-sm-3" onchange="location = this.value;">
            <?php foreach ($sorts as $sorts) { ?>
            <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
            <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
          
        <div class="col-sm-1 text-right">
          <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
        </div>
        
        <div class="col-sm-2 text-right">
          <select id="input-limit" class="form-control" onchange="location = this.value;">
            <?php foreach ($limits as $limits) { ?>
            <?php if ($limits['value'] == $limit) { ?>
            <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        
      </div>
      
      
      
      
      
      <div class="product-items layout-row-4 product-grid">
        
        <?php foreach ($products as $product) { ?>
        
        <div class="col-sm-3"> 
          
          <div class="product-thumb transition">
            
            <?php if ($product['thumb']) { ?>
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
            <?php } else { ?>
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="catalog/view/theme/default/image/placeholder.png" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
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
            
            <div class="button-group"><a class="add-to-cart" onclick="addToCart('<?php echo $product['product_id']; ?>');"><span class="hidden-tablet"><?php echo $button_cart; ?></span><span><i class="icon-shopping-cart visible-tablet"></i></span></a><a onclick="addToWishList('<?php echo $product['product_id']; ?>');"><i class="icon-heart"></i></a><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"><i class="icon-exchange"></i></a></div>
          
          </div>
        </div>
        <?php } ?>
      </div>
      
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>