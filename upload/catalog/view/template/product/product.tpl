<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <div style="display: inline-block; margin-bottom: 30px;">
    <div style="float: left; text-align: center; width: 250px;"><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="thickbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" style="margin-bottom: 3px;" /></a><br />
      <span style="font-size: 11px;"><?php echo $text_enlarge; ?></span></div>
    <div style="float: right; margin-left: 10px; width: 296px;">
      <table width="100%">
        <tr>
          <td><b><?php echo $text_price; ?></b></td>
          <td><?php echo $price; ?></td>
        </tr>
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
          <td><a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></td>
        </tr>
        <?php } ?>
        <tr>
          <td><b><?php echo $text_average; ?></b></td>
          <td><?php if ($average) { ?>
            <img src="catalog/view/image/stars_<?php echo $average . '.png'; ?>" alt="<?php echo $text_stars; ?>" style="margin-top: 2px;" />
            <?php } else { ?>
            <?php echo $text_no_rating; ?>
            <?php } ?></td>
        </tr>
      </table>
      <br />
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="product">
        <?php if ($options) { ?>
        <b><?php echo $text_options; ?></b><br />
        <div style="background: #FBFAEA; border: 1px solid #EFEBAA; padding: 10px; margin-top: 3px; margin-bottom: 10px;">
          <table style="width: 100%;">
            <?php foreach ($options as $option) { ?>
            <tr>
              <td><?php echo $option['name']; ?>:</td>
              <td><select name="option[<?php echo $option['option_id']; ?>]">
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
        <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px;"><?php echo $text_qty; ?>
          <input type="text" name="quantity" size="3" value="1" />
          <a onclick="$('#product').submit();" class="button"><span><?php echo $button_add_to_cart; ?></span></a></div>
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
      </form>
    </div>
  </div>
  <div class="tabs"><a tab="#tab_description"><?php echo $tab_description; ?></a><a tab="#tab_image"><?php echo $tab_image; ?></a><a tab="#tab_review"><?php echo $tab_review; ?></a></div>
  <div id="tab_description" class="page"><?php echo $description; ?></div>
  <div id="tab_review" class="page">
    <div id="review"></div>
    <div class="heading" id="review_title"><?php echo $text_write; ?></div>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><b><?php echo $entry_name; ?></b><br />
      <input type="text" name="name" value="" />
      <br />
      <br />
      <b><?php echo $entry_review; ?></b>
      <textarea name="text" style="width: 99%;" rows="8"></textarea>
      <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
      <br />
      <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
      <input type="radio" name="rating" value="1" style="margin: 0; margin: 0;" />
      &nbsp;
      <input type="radio" name="rating" value="2" style="margin: 0; margin: 0;" />
      &nbsp;
      <input type="radio" name="rating" value="3" style="margin: 0; margin: 0;" />
      &nbsp;
      <input type="radio" name="rating" value="4" style="margin: 0; margin: 0;" />
      &nbsp;
      <input type="radio" name="rating" value="5" style="margin: 0; margin: 0;" />
      &nbsp; <span><?php echo $entry_good; ?></span><br />
      <br />
      <b><?php echo $entry_verification; ?></b><br />
      <input type="text" name="verification" value="" />
      <br />
      <img src="index.php?route=product/product/verification" id="verification" /></div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="review();" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
  </div>
  <div id="tab_image" class="page">
    <div style="display: inline-block;">
      <?php if ($images) { ?>
      <?php foreach ($images as $image) { ?>
      <div style="display: inline-block; float: left; text-align: center; margin-left: 5px; margin-right: 5px; margin-bottom: 10px;"><a href="<?php echo $image['popup']; ?>" title="<?php echo $image['title']; ?>" class="thickbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $image['title']; ?>" alt="<?php echo $image['title']; ?>" style="border: 1px solid #DDDDDD; margin-bottom: 3px;" /></a><br />
        <?php echo $image['title']; ?><br />
        <span style="font-size: 11px;"><?php echo $text_enlarge; ?></span></div>
      <?php } ?>
      <?php } ?>
    </div>
  </div>
</div>
<div class="bottom"></div>
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
		type: 'post',
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&verification=' + encodeURIComponent($('input[name=\'verification\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#review_button').attr('disabled', 'disabled');
			$('#review_title').after('<div class="wait"><img src="catalog/view/image/loading.gif" alt="" /> Please wait!</div>');
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
				$('input[name=\'verification\']').val('');
			}
		}
	});
}
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>