<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons">
          <button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-author"><span class="required">*</span> <?php echo $entry_author; ?></label>
          <div class="controls">
            <input type="text" name="author" value="<?php echo $author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-author" />
            <?php if ($error_author) { ?>
            <span class="error"><?php echo $error_author; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-product"><?php echo $entry_product; ?></label>
          <div class="controls">
            <input type="text" name="product" value="<?php echo $product; ?>" placeholder="<?php echo $entry_product; ?>" id="input-product" />
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
            <a data-toggle="tooltip" title="<?php echo $help_product; ?>"><i class="icon-info-sign"></i></a>
            <?php if ($error_product) { ?>
            <span class="error"><?php echo $error_product; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-text"><span class="required">*</span> <?php echo $entry_text; ?></label>
          <div class="controls">
            <textarea name="text" cols="60" rows="8" placeholder="<?php echo $entry_text; ?>" id="input-text" class="input-xxlarge"><?php echo $text; ?></textarea>
            <?php if ($error_text) { ?>
            <span class="error"><?php echo $error_text; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <div class="control-label" for="input-name"><?php echo $entry_rating; ?></div>
          <div class="controls">
            <label class="radio inline">
              <?php if ($rating == 1) { ?>
              <input type="radio" name="rating" value="1" checked="checked" />
              1
              <?php } else { ?>
              <input type="radio" name="rating" value="1" />
              1
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if ($rating == 2) { ?>
              <input type="radio" name="rating" value="2" checked="checked" />
              2
              <?php } else { ?>
              <input type="radio" name="rating" value="2" />
              2
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if ($rating == 3) { ?>
              <input type="radio" name="rating" value="3" checked="checked" />
              3
              <?php } else { ?>
              <input type="radio" name="rating" value="3" />
              3
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if ($rating == 4) { ?>
              <input type="radio" name="rating" value="4" checked="checked" />
              4
              <?php } else { ?>
              <input type="radio" name="rating" value="4" />
              4
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if ($rating == 5) { ?>
              <input type="radio" name="rating" value="5" checked="checked" />
              5
              <?php } else { ?>
              <input type="radio" name="rating" value="5" />
              5
              <?php } ?>
            </label>
            <?php if ($error_rating) { ?>
            <span class="error"><?php echo $error_rating; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
          <div class="controls">
            <select name="status" id="input-status">
              <?php if ($status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'product\']').val(item['label']);
		$('input[name=\'product_id\']').val(item['value']);		
	}	
});
//--></script> 
<?php echo $footer; ?>