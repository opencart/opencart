<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><i class="icon-edit"></i></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_author; ?></label>
          <div class="controls">
            <input type="text" name="author" value="<?php echo $author; ?>" />
            <?php if ($error_author) { ?>
            <span class="error"><?php echo $error_author; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_product; ?></label>
          <div class="controls">
            <input type="text" name="product" value="<?php echo $product; ?>" />
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
            <?php if ($error_product) { ?>
            <span class="error"><?php echo $error_product; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_text; ?></label>
          <div class="controls">
            <textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
            <?php if ($error_text) { ?>
            <span class="error"><?php echo $error_text; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_rating; ?></label>
          <div class="controls"> <b class="rating"><?php echo $entry_bad; ?></b>&nbsp;
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
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_status; ?></label>
          <div class="controls">
            <select name="status">
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
        <div class="buttons"><a onclick="$('#form').submit();" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></a> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'product\']').val(ui.item.label);
		$('input[name=\'product_id\']').val(ui.item.value);
		
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script> 
<?php echo $footer; ?>