<?php echo $header; ?>
<div class="container">
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<div class="row">
<?php echo $column_left; ?>
<?php if ($column_left) { ?>
<div class="span9">
<?php } elseif ($column_right) { ?>
<div class="span9">
<?php } else { ?>
<div class="span12">
<?php } ?>
<?php echo $content_top; ?>
<div class="row">
<?php if ($column_left) { ?>
<div class="span6">
  <?php } elseif ($column_right) { ?>
  <div class="span6">
    <?php } else { ?>
    <div class="span8">
      <?php } ?>
      
      <!-- Product image -->
      
      <?php if ($thumb || $images) { ?>
      <ul class="thumbnails">
        <?php if ($thumb) { ?>
        <li> <a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /> </a> </li>
        <?php } ?>
        <?php if ($images) { ?>
        <?php foreach ($images as $image) { ?>
        <li class="image-additional"> <a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /> </a> </li>
        <?php } ?>
        <?php } ?>
      </ul>
      <?php } ?>
      
      <!-- Tabs -->
      <div class="tabbable"> 
        
        <!-- The Tabs -->
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
          <?php if ($attribute_groups) { ?>
          <li><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
          <?php } ?>
          <?php if ($review_status) { ?>
          <li><a href="#tab-reviews" data-toggle="tab"><?php echo $tab_review; ?></a></li>
          <?php } ?>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content"> 
          
          <!-- Tab - Description -->
          <div class="tab-pane active" id="tab-description"> <?php echo $description; ?> </div>
          
          <!-- Tab - Specification -->
          <?php if ($attribute_groups) { ?>
          <div class="tab-pane" id="tab-specification">
            <table class="table table-bordered">
              <?php foreach ($attribute_groups as $attribute_group) { ?>
              <thead>
                <tr>
                  <td colspan="2"><?php echo $attribute_group['name']; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <tr>
                  <td><?php echo $attribute['name']; ?></td>
                  <td><?php echo $attribute['text']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
              <?php } ?>
            </table>
          </div>
          <?php } ?>
          
          <!-- Tab - Reviews -->
          <?php if ($review_status) { ?>
          <div class="tab-pane form-horizontal" id="tab-reviews">
            <div id="review"></div>
            <h2 id="review-title"><?php echo $text_write; ?></h2>
            <div class="form-group">
              <div class="col-sm-2 control-label"> <?php echo $entry_name; ?> </div>
              <div class="col-sm-10">
                <input type="text" name="name" value="" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-2 control-label"> <?php echo $entry_review; ?> </div>
              <div class="col-sm-10">
                <textarea name="text" style="resize: vertical; min-height: 160px;" class="input-block-level"></textarea>
                <div class="alert alert-form"><?php echo $text_note; ?></div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-2 control-label"> <?php echo $entry_rating; ?> </div>
              <div class="col-sm-10"> <span><?php echo $entry_bad; ?></span>&nbsp;
                <input type="radio" name="rating" value="1" />
                &nbsp;
                <input type="radio" name="rating" value="2" />
                &nbsp;
                <input type="radio" name="rating" value="3" />
                &nbsp;
                <input type="radio" name="rating" value="4" />
                &nbsp;
                <input type="radio" name="rating" value="5" />
                &nbsp;<span><?php echo $entry_good; ?></span> </div>
            </div>
            <div class="form-group">
              <div class="col-sm-2 control-label"> <?php echo $entry_captcha; ?> </div>
              <div class="col-sm-10">
                <input type="text" name="captcha" value="" />
                <br />
                <br />
                <img src="index.php?route=product/product/captcha" alt="" id="captcha" /> </div>
            </div>
            <div class="buttons">
              <div class="pull-left"> <a id="button-review" class="btn btn-primary"><?php echo $button_continue; ?></a> </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php if ($column_left) { ?>
    <div class="span3 product-info">
      <?php } elseif ($column_right) { ?>
      <div class="span3 product-info">
        <?php } else { ?>
        <div class="span4 product-info">
          <?php } ?>
          
          <!-- Wishlist / Compare buttons -->
          <div class="btn-group"> <a data-toggle="tooltip" class="btn square tooltip-item" title="<?php echo $button_wishlist; ?>" onclick="addToWishList('<?php echo $product_id; ?>');"><i class="icon-heart"></i></a> <a data-toggle="tooltip" class="btn square tooltip-item" title="<?php echo $button_compare; ?>" onclick="addToCompare('<?php echo $product_id; ?>');"><i class="icon-exchange"></i></a> </div>
          
          <!-- Product Name -->
          <h1><?php echo $heading_title; ?></h1>
          
          <!-- Product Info -->
          <ul class="list-unstyled">
            <?php if ($manufacturer) { ?>
            <li><?php echo $text_manufacturer; ?> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>
            <?php } ?>
            <li><?php echo $text_model; ?> <?php echo $model; ?></li>
            <?php if ($reward) { ?>
            <li><?php echo $text_reward; ?> <?php echo $reward; ?></li>
            <?php } ?>
            <li><?php echo $text_stock; ?> <?php echo $stock; ?></li>
          </ul>
          
          <!-- Price -->
          <?php if ($price) { ?>
          <ul class="list-unstyled">
            <!-- <li><?php echo $text_price; ?></li> -->
            <?php if (!$special) { ?>
            <li>
              <h2 class="price"><?php echo $price; ?></h2>
            </li>
            <?php } else { ?>
            <li class="line-through"><?php echo $price; ?></li>
            <li>
              <h2 class="price"><?php echo $special; ?></h2>
            </li>
            <?php } ?>
            <?php if ($tax) { ?>
            <li><?php echo $text_tax; ?> <?php echo $tax; ?></li>
            <?php } ?>
            <?php if ($points) { ?>
            <li><?php echo $text_points; ?> <?php echo $points; ?></li>
            <?php } ?>
            <?php if ($discounts) { ?>
            <li>
              <hr>
            </li>
            <?php foreach ($discounts as $discount) { ?>
            <li><?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?></li>
            <?php } ?>
            <?php } ?>
          </ul>
          <?php } ?>
          
          <!-- Options -->
          
          <?php if ($options) { ?>
          <hr>
          <h3><?php echo $text_option; ?></h3>
          <?php foreach ($options as $option) { ?>
          <?php if ($option['type'] == 'select') { ?>
          <div class="form-group">
            <ul class="unstyled option" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <li>
                <select class="input-block-level" name="option[<?php echo $option['product_option_id']; ?>]">
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($option['option_value'] as $option_value) { ?>
                  <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                  <?php if ($option_value['price']) { ?>
                  (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                  <?php } ?>
                  </option>
                  <?php } ?>
                </select>
              </li>
            </ul>
          </div>
          <?php } ?>
          <?php if ($option['type'] == 'radio') { ?>
          <div class="form-group">
            <ul class="unstyled option" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <?php foreach ($option['option_value'] as $option_value) { ?>
              <li>
                <label class="radio" for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                  <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
                  <?php if ($option_value['price']) { ?>
                  (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                  <?php } ?>
                </label>
              </li>
              <?php } ?>
            </ul>
          </div>
          <?php } ?>
          <?php if ($option['type'] == 'checkbox') { ?>
          <div class="form-group">
            <ul class="unstyled option" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <?php foreach ($option['option_value'] as $option_value) { ?>
              <li>
                <label class="checkbox" for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                  <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
                  <?php if ($option_value['price']) { ?>
                  (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                  <?php } ?>
                </label>
              </li>
              <?php } ?>
            </ul>
          </div>
          <?php } ?>
          <?php if ($option['type'] == 'image') { ?>
          <div class="form-group">
            <ul class="unstyled option option-image" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <?php foreach ($option['option_value'] as $option_value) { ?>
              <input class="pull-left" type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
              <label class="pull-left" for="option-value-<?php echo $option_value['product_option_value_id']; ?>"> <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /> </label>
              <label class="pull-left" for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                <?php if ($option_value['price']) { ?>
                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                <?php } ?>
              </label>
              <div class="clearfix"></div>
              <?php } ?>
            </ul>
          </div>
          <?php } ?>
          <?php if ($option['type'] == 'text') { ?>
          <div class="form-group">
            <ul class="unstyled option" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <input class="input-block-level" type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
            </ul>
          </div>
          <?php } ?>
          <?php if ($option['type'] == 'textarea') { ?>
          <div class="form-group">
            <ul class="unstyled option" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <textarea class="input-block-level" name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
            </ul>
          </div>
          <?php } ?>
          <?php if ($option['type'] == 'file') { ?>
          <div class="form-group">
            <ul class="unstyled option" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <input type="button" class="btn" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>">
              <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
            </ul>
          </div>
          <?php } ?>
          <?php if ($option['type'] == 'date') { ?>
          <div class="form-group">
            <ul class="unstyled option" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <input class="input-block-level date" type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
            </ul>
          </div>
          <?php } ?>
          <?php if ($option['type'] == 'datetime') { ?>
          <div class="form-group">
            <ul class="unstyled option" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <input class="input-block-level datetime" type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
            </ul>
          </div>
          <?php } ?>
          <?php if ($option['type'] == 'time') { ?>
          <div class="form-group">
            <ul class="unstyled option" id="option-<?php echo $option['product_option_id']; ?>">
              <li>
                <?php if ($option['required']) { ?>
                <div class="text-danger">*</div>
                <?php } ?>
                <?php echo $option['name']; ?> </li>
              <input class="input-block-level time" type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
            </ul>
          </div>
          <?php } ?>
          <?php } ?>
          <?php } ?>
          
          <!-- Cart -->
          <ul class="unstyled cart">
            <li><?php echo $text_qty; ?></li>
            <li>
              <input class="input-block-level" type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" />
            </li>
            <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
            <li>
              <input class="btn btn-primary btn-lg btn-block" type="button" value="<?php echo $button_cart; ?>" id="button-cart" />
            </li>
            <!-- <li><?php echo $text_or; ?></li> -->
            <?php if ($minimum > 1) { ?>
            <li class="alert"><?php echo $text_minimum; ?></li>
            <?php } ?>
          </ul>
          
          <!-- Review -->
          <?php if ($review_status) { ?>
          <div class="review">
            <div><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" /> <a class="review-button" onclick="$('a[href=\'#tab-reviews\']').trigger('click');"><?php echo $reviews; ?></a> <a class="review-button" onclick="$('a[href=\'#tab-reviews\']').trigger('click');"><?php echo $text_write; ?></a></div>
            <hr>
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style "> <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> <a class="addthis_button_tweet"></a> <a class="addthis_button_pinterest_pinit"></a> <a class="addthis_counter addthis_pill_style"></a> </div>
            <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script> 
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script> 
            <!-- AddThis Button END --> 
            
          </div>
          <?php } ?>
        </div>
        <!-- /span4 --> 
      </div>
      <!-- /row --> 
      
      <!-- Related products -->
      <?php if ($products) { ?>
      <h3>Related Products</h3>
      <div class="row">
        <?php foreach ($products as $product) { ?>
        <div class="span3">
          <div class="product-thumb transition">
            <?php if ($product['thumb']) { ?>
            <div class="image"> <a href="<?php echo $product['href']; ?>"> <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /> </a> </div>
            <?php } else { ?>
            <div class="image"> <a href="<?php echo $product['href']; ?>"> <img src="catalog/view/theme/default/image/placeholder.png" alt="<?php echo $product['name']; ?>" /> </a> </div>
            <?php } ?>
            <div class="caption">
              <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
              <p>Bacon ipsum dolor sit amet brisket flank t-bone rump ball tip venison.</p>
              <?php if ($product['price']) { ?>
              <p class="price">
                <?php if (!$product['special']) { ?>
                <?php echo $product['price']; ?>
                <?php } else { ?>
                <?php echo $product['price']; ?> <?php echo $product['special']; ?>
                <?php } ?>
              </p>
              <?php } ?>
              <?php if ($product['rating']) { ?>
              <img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" />
              <?php } ?>
            </div>
            <div class="button-group"> <a class="add-to-cart" onclick="addToCart('<?php echo $product['product_id']; ?>');"> <span class="hidden-tablet"><?php echo $button_cart; ?></span><span><i class="icon-shopping-cart visible-tablet"></i></span> </a> <a data-toggle="tooltip" class="tooltip-item" title="<?php echo $button_wishlist; ?>" onclick="addToWishList('<?php echo $product['product_id']; ?>');"><i class="icon-heart"></i></a> <a data-toggle="tooltip" class="tooltip-item" title="<?php echo $button_compare; ?>" onclick="addToCompare('<?php echo $product['product_id']; ?>');"><i class="icon-exchange"></i></a>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      
      <!-- Tags -->
      <?php if ($tags) { ?>
      <div class="tags"><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?> </div>
    <!-- /span12 or /span9 --> 
    
    <?php echo $column_right; ?> </div>
</div>
<script type="text/javascript"><!--
$('#button-cart').click(function() {
    $.ajax({
        url: 'index.php?route=checkout/cart/add',
        type: 'post',
        data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
        dataType: 'json',
        success: function(json) {
            $('.alert, .text-danger').remove();
            
            if (json['error']) {
                if (json['error']['option']) {
                    for (i in json['error']['option']) {
                        $('#option-' + i).after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                    }
                }
            } 
            
            if (json['success']) {
                $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    
                $('#cart-total').html(json['total']);
                
                $('html, body').animate({ scrollTop: 0 }, 'slow'); 
            }   
        }
    });
});
//--></script>
<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
    action: 'index.php?route=product/product/upload',
    name: 'file',
    autoSubmit: true,
    responseType: 'json',
    onSubmit: function(file, extension) {
        $('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
        $('#button-option-<?php echo $option['product_option_id']; ?>').prop('disabled', true);
    },
    onComplete: function(file, json) {
        $('#button-option-<?php echo $option['product_option_id']; ?>').prop('disabled', false);
        
        $('.alert-error').remove();
        
        if (json['success']) {
            alert(json['success']);
            
            $('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').prop('value', json['file']);
        }
        
        if (json['error']) {
            $('#option-<?php echo $option['product_option_id']; ?>').after('<div class="text-danger">' + json['error'] + '</div>');
        }
        
        $('.loading').remove(); 
    }
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#review .pagination a').click(function() {
    $('#review').fadeOut('slow');
        
    $('#review').load(this.href);
    
    $('#review').fadeIn('slow');
    
    return false;
});         

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function() {
    $.ajax({
        url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
        type: 'post',
        dataType: 'json',
        data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
        beforeSend: function() {
            $('.alert-success, .alert-warning').remove();
            $('#button-review').prop('disabled', true);
            $('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
        },
        complete: function() {
            $('#button-review').prop('disabled', false);
            $('.attention').remove();
        },
        success: function(data) {
            if (data['error']) {
                $('#review-title').after('<div class="alert alert-warning">' + data['error'] + '</div>');
            }
            
            if (data['success']) {
                $('#review-title').after('<div class="alert alert-success">' + data['success'] + '</div>');
                                
                $('input[name=\'name\']').val('');
                $('textarea[name=\'text\']').val('');
                $('input[name=\'rating\']:checked').prop('checked', '');
                $('input[name=\'captcha\']').val('');
            }
        }
    });
});
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
/*<script src="catalog/view/javascript/jquery/ui/jquery-ui-1.10.3.custom.min.js"></script> 
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.datetime').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'h:m'
    });
    $('.time').timepicker({timeFormat: 'h:m'});
*/
    $('.thumbnails').magnificPopup({
        delegate: 'li a', // child items selector, by clicking on it popup will open
        type: 'image',
        gallery: {
            enabled: true
        }
        // other options
    });

});
--></script> 
<?php echo $footer; ?>