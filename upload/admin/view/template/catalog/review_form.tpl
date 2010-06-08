<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/review.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_author; ?></td>
          <td><input type="text" name="author" value="<?php echo $author?>" />
            <?php if ($error_author) { ?>
            <span class="error"><?php echo $error_author; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_product; ?></td>
          <td><select id="category" style="margin-bottom: 5px;" onchange="getProducts();">
              <option value="0"><?php echo $text_select; ?></option>
              <?php foreach ($categories as $category) { ?>
              <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
              <?php } ?>
            </select>
            <br />
            <select name="product_id" id="product">
              <?php if ($product) { ?>
              <option value="<?php echo $product_id; ?>"><?php echo $product; ?></option>
              <?php } else { ?>
              <option value="0"><?php echo $text_none; ?></option>
              <?php } ?>
            </select>
            <?php if ($error_product) { ?>
            <span class="error"><?php echo $error_product; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_text; ?></td>
          <td><textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
            <?php if ($error_text) { ?>
            <span class="error"><?php echo $error_text; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_rating; ?></td>
          <td><b class="rating"><?php echo $entry_bad; ?></b>&nbsp;
            <?php if ($rating == 1) { ?>
            <input type="radio" name="rating" value="1" checked />
            <?php } else { ?>
            <input type="radio" name="rating" value="1" />
            <?php } ?>
            &nbsp;
            <?php if ($rating == 2) { ?>
            <input type="radio" name="rating" value="2" checked />
            <?php } else { ?>
            <input type="radio" name="rating" value="2" />
            <?php } ?>
            &nbsp;
            <?php if ($rating == 3) { ?>
            <input type="radio" name="rating" value="3" checked />
            <?php } else { ?>
            <input type="radio" name="rating" value="3" />
            <?php } ?>
            &nbsp;
            <?php if ($rating == 4) { ?>
            <input type="radio" name="rating" value="4" checked />
            <?php } else { ?>
            <input type="radio" name="rating" value="4" />
            <?php } ?>
            &nbsp;
            <?php if ($rating == 5) { ?>
            <input type="radio" name="rating" value="5" checked />
            <?php } else { ?>
            <input type="radio" name="rating" value="5" />
            <?php } ?>
            &nbsp; <b class="rating"><?php echo $entry_good; ?></b>
            <?php if ($error_rating) { ?>
            <span class="error"><?php echo $error_rating; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="status">
              <?php if ($status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
function getProducts() {
	$('#product option').remove();
	
	$.ajax({
		url: 'index.php?route=catalog/review/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value'),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
			}
		}
	});
}
//--></script>
<?php echo $footer; ?>