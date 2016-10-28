<?php echo $header; ?>

<?php  if ($ccttype==0) { ?>
<div class="container product-bg" ></div>
<?php  } elseif ($ccttype==1) { ?>
<div class="container product-bg-cold" ></div>
<?php  } else  { ?>
<div class="container product-bg-candle" ></div>
<?php  }  ?>

<div class="container">
  <?php /*>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <*/?>
  <div class="row">
    <?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <?php echo $content_top; ?>
      <div class="row">
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-8'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <div class="row product_detail">
            <div class="col-md-6">
              <?php if ($thumb || $images) { ?>
              <?php if(!$mobile) { ?>
              <a id="zoom" class="cloud-zoom" rel="adjustX:10, adjustY:-4"    href="<?php echo $images[0]['large']; ?>"  title="<?php echo $heading_title; ?>">
                <img src="<?php echo $images[0]['popup']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
              <?php } else { ?>
              <a class="cloud-zoom" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
              <?php } ?>


              <ul class="thumbnails">
                <?php if ($images) { ?>
                <?php foreach ($images as $image) { ?>
                <li class="image-additional">
                  <?php if(!$mobile) { ?>
                  <a  rel ="<?php echo('useZoom:' .'\'' .'zoom' .'\'' .', smallImage:'.'\''. $image['popup'].''.'\'' ) ?> " class="cloud-zoom-gallery "  href="<?php echo $image['large']; ?>"    title="<?php echo $heading_title; ?>" >
                    <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
                  <?php } else { ?>
                  <a class="thumbnail" data-img="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" onclick="fn_switch_image(this, true);"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
                  <?php } ?>
                </li>
                <?php } ?>
                <?php } ?>
              </ul>

              <?php } ?>
            </div>
            <div class="col-md-6">
              <?php /*
           <div class="btn-group">
                   <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i></button>
                   <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i></button>
               </div>
            */ ?>
              <h3 class="product-import">
                <?php echo $heading_title.'  pack (6 units)'; ?>
              </h3>
              <ul class="list-unstyled">
                <?php if ($manufacturer) { ?>
                <li>
                  <?php echo $text_manufacturer; ?>
                  <a href="<?php echo $manufacturers; ?>">
                    <?php echo $manufacturer; ?>
                  </a>
                </li>
                <?php } ?>
                <li>
                  <?php echo $text_model; ?>
                  <?php echo $model; ?>
                </li>
                <?php /*>
                   <?php if ($reward) { ?>
                      <li><?php echo $text_reward; ?> <?php echo $reward; ?></li>
                      <?php } ?>
                < */ ?>
                <li>
                  <?php echo $text_stock; ?><span><?php echo $stock; ?></span> </li>
              </ul>
            </div>
          </div>
         <?php /*>
           <?php if ($attribute_groups) { ?>
            <li><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
            <?php } ?>
        < */?>
          <div class="product_description">
            <?php echo $description; ?>
          </div>
         <?php /*
         <?php if ($attribute_groups) { ?>
            <div class="tab-pane" id="tab-specification">
              <table class="table table-bordered">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                  <tr>
                    <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
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
        */ ?>
        </div>
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <?php if ($price) { ?>
          <ul class="list-unstyled">
            <?php if (!$special) { ?>
            <li>
              <h2>
                <span class="text-danger"><b><?php echo $price . ' for 6 bulbs'; ?></b></span>
              </h2>
              <h3>
              <b><span class="product-import"><?php echo $text_per_bulb; ?></span></b>
              </h3>
            </li>
            <?php } else { ?>
            <li>
              <h3><span class="text-decorationbig"><?php echo $price; ?></span></h3>
            </li>
            <li>
              <h2>
                <span class="text-danger" "><b><?php echo $special . ' for 6 bulbs'; ?></b></span>
              </h2>
              <h3>
              <b><span class="product-import"><?php echo $text_per_bulb; ?></span></b>
                </h3>
            </li>
            <?php } ?>

            <?php /*
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
            <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
            <?php } ?>
            <?php } ?>
            <*/?>

          </ul>
          <?php } ?>
          <div id="product">
            <?php if ($options) { ?>
            <hr>
            <h3>
              <?php echo $text_option; ?>
            </h3>
            <?php foreach ($options as $option) { ?>
            <?php if ($option['type'] == 'select') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>"
                class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                <?php if ($option_value['price']) { ?>
                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                <?php } ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'radio') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="radio">
                  <label>
                    <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                    <?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'checkbox') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                    <?php if ($option_value['image']) { ?>
                    <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
                    <?php } ?>
                    <?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'image') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="radio">
                  <label>
                    <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                    <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> <?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'text') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>"
                id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'textarea') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>"
                id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'file') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>"
                class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
              <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>"
              />
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'date') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <div class="input-group date">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD"
                  id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'datetime') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <div class="input-group datetime">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm"
                  id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'time') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <div class="input-group time">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm"
                  id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php if ($recurrings) { ?>
            <hr>
            <h3>
              <?php echo $text_payment_recurring ?>
            </h3>
            <div class="form-group required">
              <select name="recurring_id" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($recurrings as $recurring) { ?>
                <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>
                <?php } ?>
              </select>
              <div class="help-block" id="recurring-description"></div>
            </div>
            <?php } ?>
            <div class="form-group">
              <label class="control-label" for="input-quantity"><b><?php echo $entry_qty; ?></b></label>
              <select name="quantity" class="form-control">
                    <option value="1">1 pack (6 bulbs)</option>
                    <option value="2">2 pack (12 bulbs)</option>
                    <option value="3">3 pack (18 bulbs)</option>
                    <option value="4">4 pack (24 bulbs)</option>
                    <option value="5">5 pack (30 bulbs)</option>
                </select>

               <?php /*>
              <input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" />
              <div class="input-group spinner" data-trigger="spinner">
                <input type="text" name="quantity" class="form-control text-center" value="1" data-rule="quantity" data-max="5">
                <div class="input-group-addon">
                  <a href="javascript:;" type="submit" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>
                  <a href="javascript:;" type="submit" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>
                </div>
              </div>
              <*/?>

              <!--energy conservation-->
              <?php if($energy_price > 0) { ?>
              <p>
                <?php if($energy) { ?>
                <input type="checkbox" name="energy_price" value="1" checked="checked" /> <?php echo $text_energy_conservation; ?>
                <?php } else { ?>
                <input type="checkbox" name="energy_price" value="0" /> <?php echo $text_energy_conservation; ?>
                <?php } ?>
              </p>
              <?php } else { ?>
              <br />
              <?php } ?>
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-cart-add btn-lg btn-block">
                  <i class="fa fa-shopping-cart"></i><?php echo $button_cart; ?>
              </button>
            </div>
            <?php if ($minimum > 1) { ?>
            <div class="alert alert-info"><i class="fa fa-info-circle"></i>
              <?php echo $text_minimum; ?>
            </div>
            <?php } ?>
          </div>
          <?php if ($review_status) { ?>
          <div class="rating">

            <?php /*>
              <p>
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($rating < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } ?>
              <?php } ?>
              <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews; ?></a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a></p>
            <*/?>

            <hr>
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style" data-url="<?php echo $share; ?>">
              <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
              <a class="addthis_button_tweet"></a>
              <a class="addthis_button_pinterest_pinit"></a>
              <a class="addthis_counter addthis_pill_style"></a>
            </div>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script>
            <!-- AddThis Button END -->
          </div>
          <?php } ?>

          <ul class="nav nav-tabs">
            <?php if ($review_status) { ?>
            <li class="active">
              <a href="#tab-read-review" data-toggle="tab">
                <?php echo $tab_review; ?>
              </a>
            </li>
            <li>
              <a href="#tab-write-review" data-toggle="tab">
                <?php echo $text_write; ?>
              </a>
            </li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-read-review">
              <div id="review"></div>
            </div>
            <?php if ($review_status) { ?>
            <div class="tab-pane" id="tab-write-review">
              <form class="form-horizontal" id="form-review">
                <h2>
                  <?php echo $text_write; ?>
                </h2>
                <?php if ($review_guest) { ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                    <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                    <div class="help-block">
                      <?php echo $text_note; ?>
                    </div>
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label"><?php echo $entry_rating; ?></label>
                    <?php echo $entry_bad; ?>
                    <a href="javascript:void(0);" name="rating" data-value="1"><span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span></a>
                    <a href="javascript:void(0);" name="rating" data-value="2"><span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span></a>
                    <a href="javascript:void(0);" name="rating" data-value="3"><span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span></a>
                    <a href="javascript:void(0);" name="rating" data-value="4"><span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span></a>
                    <a href="javascript:void(0);" name="rating" data-value="5"><span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span></a>
                    <?php echo $entry_good; ?>
                  </div>
                </div>
                <?php echo $captcha; ?>
                <div class="buttons clearfix">
                  <div class="pull-right">
                    <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                  </div>
                </div>
                <?php } else { ?>
                <?php echo $text_login; ?>
                <?php } ?>
              </form>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <?php if ($products) { ?>
    <h3>
      <?php echo $text_related; ?>
    </h3>
    <div class="row">
      <?php $i = 0; ?>
      <?php foreach ($products as $product) { ?>
      <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-lg-4 col-md-4 col-sm-6 col-xs-12'; ?>
      <?php } else { ?>
      <?php $class = 'col-lg-3 col-md-3 col-sm-6 col-xs-12'; ?>
      <?php } ?>
      <div class="<?php echo $class; ?>">
        <div class="product-thumb transition">
          <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
          <div class="caption">
            <h4>
              <a href="<?php echo $product['href']; ?>">
                <?php echo $product['name']; ?>
              </a>
            </h4>
            <p>
              <?php echo $product['description']; ?>
            </p>
            <?php if ($product['rating']) { ?>
            <div class="rating">
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($product['rating'] < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } ?>
              <?php } ?>
            </div>
            <?php } ?>
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
          </div>
          <div class="button-group">
            <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span> <i class="fa fa-shopping-cart"></i></button>
            <?php /*>
              <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
              <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
             < */ ?>
          </div>
        </div>
      </div>
      <?php if (($column_left && $column_right) && ($i % 2 == 0)) { ?>
      <div class="clearfix visible-md visible-sm"></div>
      <?php } elseif (($column_left || $column_right) && ($i % 3 == 0)) { ?>
      <div class="clearfix visible-md"></div>
      <?php } elseif ($i % 4 == 0) { ?>
      <div class="clearfix visible-md"></div>
      <?php } ?>
      <?php $i++; ?>
      <?php } ?>
    </div>
    <?php } ?>
    <?php if ($tags) { ?>
    <p>
      <?php echo $text_tags; ?>
      <?php for ($i = 0; $i < count($tags); $i++) { ?>
      <?php if ($i < (count($tags) - 1)) { ?>
      <a href="<?php echo $tags[$i]['href']; ?>">
        <?php echo $tags[$i]['tag']; ?>
      </a>,
      <?php } else { ?>
      <a href="<?php echo $tags[$i]['href']; ?>">
        <?php echo $tags[$i]['tag']; ?>
      </a>
      <?php } ?>
      <?php } ?>
    </p>
    <?php } ?>
    <?php echo $content_bottom; ?>
  </div>
  <?php echo $column_right; ?>
</div>
</div>
<script type="text/javascript"><!--
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {
				//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                $('#top').parent().before('<div id="cart_add" class="modal fade">' +
                        '<div class="modal-dialog modal-cart cart-success">' +
                        '<div class="modal-content">' +
                        '<div class="modal-body"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>' +
                        '</div></div></div>');

                $('#cart_add').modal('show');

				$('#cart > button').html('<i class="fa fa-shopping-cart"></i> ' + json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
    e.preventDefault();

    $('#review').load(this.href);

});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

  // review
$('#form-review a[name="rating"]').on('click',function(){
  // empty star
    $('#form-review a[name="rating"] i[class*="fa-star"]').removeClass('fa-star');
    $('#form-review a[name="rating"] i').addClass('fa-star-o');
    // fill star
    var index = $(this).data('value');
    for(var i = 0;i< index;i++)
    {
      var $star = $($('#form-review a[name="rating"]')[i]);
      $star.find('i').removeClass("fa-star-o");
      $star.find('i').addClass("fa-star");
    }
});

  function fn_switch_image(obj, mobile) {
    var a = mobile;
    var imgPath = $(obj).data('img');
    $('a.cloud-zoom > img').attr("src", imgPath);
    if(!mobile) {
      $('a.cloud-zoom').attr('href', imgPath);
      $('.mousetrap').remove();
      $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
    }
  }

  $('#button-review').on('click', function() {
    var data = $("#form-review").serialize() + '&rating=' +  $('#form-review').find('a[name="rating"] i:not([class*="fa-star-o"])').length;

	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('#form-review').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				$('#form-review').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
			}
		}
	});
});

$(document).ready(function() {
  $('input[type="checkbox"][name="energy_price"]').on('click', function () {
    var action = '<?php echo $action; ?>';
    action = action.replace(/&amp;/g, '&');
    location = action + '&energy=' + $('input[type="checkbox"][name="energy_price"]').prop("checked");
  });
});
//--></script>
<?php echo $footer; ?>