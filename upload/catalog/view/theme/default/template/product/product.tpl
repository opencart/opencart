<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle">
    <div style="width: 100%; margin-bottom: 30px;">
      <table style="width: 100%; border-collapse: collapse;">
        <tr>
          <td style="text-align: center; width: 250px; vertical-align: top;"><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="thickbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" style="margin-bottom: 3px;" /></a><br />
            <span style="font-size: 11px;"><?php echo $text_enlarge; ?></span></td>
          <td style="padding-left: 15px; width: 296px; vertical-align: top;"><table width="100%">
              <?php if ($display_price) { ?>
              <tr>
                <td><b><?php echo $text_price; ?></b></td>
                <td><?php if (!$special) { ?>
                  <?php echo $price; ?>
                  <?php } else { ?>
                  <span style="text-decoration: line-through;"><?php echo $price; ?></span> <span style="color: #F00;"><?php echo $special; ?></span>
                  <?php } ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><b><?php echo $text_availability; ?></b></td>
                <td><?php echo $stock; ?></td>
              </tr>
              <tr>
                <td><b><?php echo $text_model; ?></b></td>
                <td><?php echo $model; ?></td>
              </tr>
              <?php if ($manufacturer) { ?>
              <tr>
                <td><b><?php echo $text_manufacturer; ?></b></td>
                <td><a href="<?php echo str_replace('&', '&amp;', $manufacturers); ?>"><?php echo $manufacturer; ?></a></td>
              </tr>
              <?php } ?>
              <tr>
                <td><b><?php echo $text_average; ?></b></td>
                <td><?php if ($average) { ?>
                  <img src="catalog/view/theme/default/image/stars_<?php echo $average . '.png'; ?>" alt="<?php echo $text_stars; ?>" style="margin-top: 2px;" />
                  <?php } else { ?>
                  <?php echo $text_no_rating; ?>
                  <?php } ?></td>
              </tr>
            </table>
            <br />
            <?php if ($display_price) { ?>
            <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="product">
              <?php if ($options) { ?>
              <b><?php echo $text_options; ?></b><br />
              <div style="background: #FFFFCC; border: 1px solid #FFCC33; padding: 10px; margin-top: 2px; margin-bottom: 15px;">
                <table style="width: 100%;">
                  <?php foreach ($options as $option) { ?>
                  <tr>
                    <td><?php echo $option['name']; ?>:<br />
                      <select name="option[<?php echo $option['option_id']; ?>]">
                        <?php foreach ($option['option_value'] as $option_value) { ?>
                        <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?>
                        <?php if ($option_value['price']) { ?>
                        <?php echo $option_value['prefix']; ?><?php echo $option_value['price']; ?>
                        <?php } ?>
                        </option>
                        <?php } ?>
                      </select></td>
                  </tr>
                  <?php } ?>
                </table>
              </div>
              <?php } ?>
              <?php if ($display_price) { ?>
              <?php if ($discounts) { ?>
              <b><?php echo $text_discount; ?></b><br />
              <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-top: 2px; margin-bottom: 15px;">
                <table style="width: 100%;">
                  <tr>
                    <td style="text-align: right;"><b><?php echo $text_order_quantity; ?></b></td>
                    <td style="text-align: right;"><b><?php echo $text_price_per_item; ?></b></td>
                  </tr>
                  <?php foreach ($discounts as $discount) { ?>
                  <tr>
                    <td style="text-align: right;"><?php echo $discount['quantity']; ?></td>
                    <td style="text-align: right;"><?php echo $discount['price']; ?></td>
                  </tr>
                  <?php } ?>
                </table>
              </div>
              <?php } ?>
              <?php } ?>
              <div class="content"><?php echo $text_qty; ?>
                <input type="text" name="quantity" size="3" value="1" />
                <a onclick="$('#product').submit();" id="add_to_cart" class="button"><span><?php echo $button_add_to_cart; ?></span></a></div>
              <div><input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $redirect); ?>" /></div>
            </form>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <div class="tabs"><a tab="#tab_description"><?php echo $tab_description; ?></a><a tab="#tab_image"><?php echo $tab_image; ?></a><a tab="#tab_review"><?php echo $tab_review; ?></a><a tab="#tab_related"><?php echo $tab_related; ?></a></div>
    <div id="tab_description" class="tab_page"><?php echo $description; ?></div>
    <div id="tab_review" class="tab_page">
      <div id="review"></div>
      <div class="heading" id="review_title"><?php echo $text_write; ?></div>
      <div class="content"><b><?php echo $entry_name; ?></b><br />
        <input type="text" name="name" value="" />
        <br />
        <br />
        <b><?php echo $entry_review; ?></b>
        <textarea name="text" style="width: 98%;" rows="8"></textarea>
        <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
        <br />
        <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
        <input type="radio" name="rating" value="1" style="margin: 0;" />
        &nbsp;
        <input type="radio" name="rating" value="2" style="margin: 0;" />
        &nbsp;
        <input type="radio" name="rating" value="3" style="margin: 0;" />
        &nbsp;
        <input type="radio" name="rating" value="4" style="margin: 0;" />
        &nbsp;
        <input type="radio" name="rating" value="5" style="margin: 0;" />
        &nbsp; <span><?php echo $entry_good; ?></span><br />
        <br />
        <b><?php echo $entry_captcha; ?></b><br />
        <input type="text" name="captcha" value="" />
        <br />
        <img src="index.php?route=product/product/captcha" id="captcha" /></div>
      <div class="buttons">
        <table>
          <tr>
            <td align="right"><a onclick="review();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
    </div>
    <div id="tab_image" class="tab_page">
      <?php if ($images) { ?>
      <div style="display: inline-block;">
        <?php foreach ($images as $image) { ?>
        <div style="display: inline-block; float: left; text-align: center; margin-left: 5px; margin-right: 5px; margin-bottom: 10px;"><a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="thickbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" style="border: 1px solid #DDDDDD; margin-bottom: 3px;" /></a><br />
          <span style="font-size: 11px;"><?php echo $text_enlarge; ?></span></div>
        <?php } ?>
      </div>
      <?php } else { ?>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $text_no_images; ?></div>
      <?php } ?>
    </div>
    <div id="tab_related" class="tab_page">
      <?php if ($products) { ?>
      <table class="list">
        <?php for ($i = 0; $i < sizeof($products); $i = $i + 4) { ?>
        <tr>
          <?php for ($j = $i; $j < ($i + 4); $j++) { ?>
          <td width="25%"><?php if (isset($products[$j])) { ?>
            <a href="<?php echo str_replace('&', '&amp;', $products[$j]['href']); ?>"><img src="<?php echo $products[$j]['thumb']; ?>" title="<?php echo $products[$j]['name']; ?>" alt="<?php echo $products[$j]['name']; ?>" /></a><br />
            <a href="<?php echo str_replace('&', '&amp;', $products[$j]['href']); ?>"><?php echo $products[$j]['name']; ?></a><br />
            <span style="color: #999; font-size: 11px;"><?php echo $products[$j]['model']; ?></span><br />
            <?php if ($display_price) { ?>
            <?php if (!$products[$j]['special']) { ?>
            <span style="color: #900; font-weight: bold;"><?php echo $products[$j]['price']; ?></span><br />
            <?php } else { ?>
            <span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $products[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $products[$j]['special']; ?></span>
            <?php } ?>
            <?php } ?>
            <?php if ($products[$j]['rating']) { ?>
            <img src="catalog/view/theme/default/image/stars_<?php echo $products[$j]['rating'] . '.png'; ?>" alt="<?php echo $products[$j]['stars']; ?>" />
            <?php } ?>
            <?php } ?></td>
          <?php } ?>
        </tr>
        <?php } ?>
      </table>
      <?php } else { ?>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $text_no_related; ?></div>
      <?php } ?>
    </div>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').slideUp('slow');
		
	$('#review').load(this.href);
	
	$('#review').slideDown('slow');
	
	return false;
});			

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

function review() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#review_button').attr('disabled', 'disabled');
			$('#review_title').after('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#review_button').attr('disabled', '');
			$('.wait').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#review_title').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#review_title').after('<div class="success">' + data.success + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
}
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; ?> 