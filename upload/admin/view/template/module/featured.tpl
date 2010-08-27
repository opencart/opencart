<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_limit; ?></td>
          <td><input type="text" name="featured_limit" value="<?php echo $featured_limit; ?>" size="1" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_position; ?></td>
          <td><select name="featured_position">
              <?php foreach ($positions as $position) { ?>
              <?php if ($featured_position == $position['position']) { ?>
              <option value="<?php echo $position['position']; ?>" selected="selected"><?php echo $position['title']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $position['position']; ?>"><?php echo $position['title']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="featured_status">
              <?php if ($featured_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="featured_sort_order" value="<?php echo $featured_sort_order; ?>" size="1" /></td>
        </tr>
		<tr>
      	  <td><?php echo $entry_product; ?></td>
          <td>
            <table>
              <tr>
                <td style="padding: 0;" colspan="3"><select id="category" style="margin-bottom: 5px;" onchange="getProducts();">
                  <?php foreach ($categories as $category) { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                  <?php } ?>
                </select></td>
              </tr>
              <tr>
                <td style="padding: 0;"><select multiple="multiple" id="product" size="10" style="width: 350px;">
                  </select></td>
                  <td style="vertical-align: middle;"><input type="button" value="--&gt;" onclick="addFeatured();" />
                  <br />
                  <input type="button" value="&lt;--" onclick="removeFeatured();" /></td>
                  <td style="padding: 0;"><select multiple="multiple" id="featured" size="10" style="width: 350px;">
                </select></td>
              </tr>
            </table>
            <div id="product_featured">
              <?php foreach ($product_featured as $featured_id) { ?>
              <input type="hidden" name="product_featured[]" value="<?php echo $featured_id; ?>" />
              <?php } ?>
            </div>
          </td>
        </tr>      
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
function addFeatured() {
	$('#product :selected').each(function() {
		$(this).remove();
		
		$('#featured option[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#featured').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
		
		$('#product_featured input[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#product_featured').append('<input type="hidden" name="product_featured[]" value="' + $(this).attr('value') + '" />');
	});
}

function removeFeatured() {
	$('#featured :selected').each(function() {
		$(this).remove();
		
		$('#product_featured input[value=\'' + $(this).attr('value') + '\']').remove();
	});
}

function getProducts() {
	$('#product option').remove();
	
	$.ajax({
		url: 'index.php?route=module/featured/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value'),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
			}
		}
	});
}

function getFeatured() {
	$('#featured option').remove();
	
	$.ajax({
		url: 'index.php?route=module/featured/featured&token=<?php echo $token; ?>',
		type: 'POST',
		dataType: 'json',
		data: $('#product_featured input'),
		success: function(data) {
			$('#product_featured input').remove();
			
			for (i = 0; i < data.length; i++) {
	 			$('#featured').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
				
				$('#product_featured').append('<input type="hidden" name="product_featured[]" value="' + data[i]['product_id'] + '" />');
			} 
		}
	});
}

getProducts();
getFeatured();
//--></script>
<?php echo $footer; ?>