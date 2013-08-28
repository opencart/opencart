<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <div id="content" class="span12"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="search-content">
        <div class="form-inline search-inline">
          <?php if ($search) { ?>
          <input class="input-medium" type="text" name="search" value="<?php echo $search; ?>" />
          <?php } else { ?>
          <input class="input-medium" type="text" name="search" value="<?php echo $search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
          <?php } ?>
          <select name="category_id">
            <option value="0"><?php echo $text_category; ?></option>
            <?php foreach ($categories as $category_1) { ?>
            <?php if ($category_1['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <?php if ($category_2['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_2['children'] as $category_3) { ?>
            <?php if ($category_3['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="form-inline search-inline">
          <label class="checkbox" for="sub_category">
            <?php if ($sub_category) { ?>
            <input type="checkbox" name="sub_category" value="1" id="sub_category" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="sub_category" value="1" id="sub_category" />
            <?php } ?>
            <?php echo $text_sub_category; ?> </label>
          <label class="checkbox" for="description">
            <?php if ($description) { ?>
            <input type="checkbox" name="description" value="1" id="description" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="description" value="1" id="description" />
            <?php } ?>
            <?php echo $entry_description; ?> </label>
        </div>
        <div class="buttons">
          <div class="pull-right">
            <input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn" />
          </div>
        </div>
      </div>
      <?php if ($products) { ?>
      <div class="product-compare"><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></div>
      <div class="product-filter">
        <div class="display pull-left">
          <div class="btn-group"><a id="list-view" class="btn square tooltip-item" data-toggle="tooltip" title="<?php echo $button_list_view; ?>"><i class="icon-th-list"></i></a> <a id="grid-view" class="btn square tooltip-item" data-toggle="tooltip" title="<?php echo $button_grid_view; ?>"><i class="icon-th"></i></a></div>
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
      <div class="product-items layout-row-4 product-grid">
        <?php foreach ($products as $product) { ?>
        <div class="span3"> 
          <!-- Product thumb -->
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
            <div class="button-group"> <a class="add-to-cart" onclick="addToCart('<?php echo $product['product_id']; ?>');"> <span class="hidden-tablet"><?php echo $button_cart; ?></span><span><i class="icon-shopping-cart visible-tablet"></i></span> </a> <a data-toggle="tooltip" class="tooltip-item" title="<?php echo $button_wishlist; ?>" onclick="addToWishList('<?php echo $product['product_id']; ?>');"><i class="icon-heart"></i></a> <a data-toggle="tooltip" class="tooltip-item" title="<?php echo $button_compare; ?>" onclick="addToCompare('<?php echo $product['product_id']; ?>');"><i class="icon-exchange"></i></a>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="pagination"> <?php echo $pagination; ?> </div>
      <?php } else { ?>
      <div class="content"><?php echo $text_empty; ?></div>
      <?php } ?>
      <?php echo $content_bottom; ?> </div>
    <?php echo $column_right; ?> </div>
</div>
<?php echo $footer; ?> 
<script type="text/javascript"><!--
    $('#content input[name=\'search\']').keydown(function(e) {
    	if (e.keyCode == 13) {
    		$('#button-search').trigger('click');
    	}
    });

    $('select[name=\'category_id\']').change(function() {
    	if (this.value == '0') {
    		$('input[name=\'sub_category\']').prop('disabled', 'disabled');
    		$('input[name=\'sub_category\']').removeProp('checked');
    	} else {
    		$('input[name=\'sub_category\']').removeProp('disabled');
    	}
    });

    $('select[name=\'category_id\']').trigger('change');

    $('#button-search').click(function() {
    	url = 'index.php?route=product/search';
    	
    	var search = $('#content input[name=\'search\']').prop('value');
    	
    	if (search) {
    		url += '&search=' + encodeURIComponent(search);
    	}

    	var category_id = $('#content select[name=\'category_id\']').prop('value');
    	
    	if (category_id > 0) {
    		url += '&category_id=' + encodeURIComponent(category_id);
    	}
    	
    	var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');
    	
    	if (sub_category) {
    		url += '&sub_category=true';
    	}
    		
    	var filter_description = $('#content input[name=\'description\']:checked').prop('value');
    	
    	if (filter_description) {
    		url += '&description=true';
    	}

    	location = url;
    });
--></script>