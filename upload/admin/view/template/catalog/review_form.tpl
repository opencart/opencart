<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-review" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-review" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-author"><?php echo $entry_author; ?></label>
          <div class="col-sm-10">
            <input type="text" name="author" value="<?php echo $author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-author" class="form-control" />
            <?php if ($error_author) { ?>
            <div class="text-danger"><?php echo $error_author; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
          <div class="col-sm-10">
            <input type="text" name="product" value="<?php echo $product; ?>" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
            <span class="help-block"><?php echo $help_product; ?></span>
            <?php if ($error_product) { ?>
            <div class="text-danger"><?php echo $error_product; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-text"><?php echo $entry_text; ?></label>
          <div class="col-sm-10">
            <textarea name="text" cols="60" rows="8" placeholder="<?php echo $entry_text; ?>" id="input-text" class="form-control"><?php echo $text; ?></textarea>
            <?php if ($error_text) { ?>
            <div class="text-danger"><?php echo $error_text; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_rating; ?></label>
          <div class="col-sm-10">
            <label class="radio-inline">
              <?php if ($rating == 1) { ?>
              <input type="radio" name="rating" value="1" checked="checked" />
              1
              <?php } else { ?>
              <input type="radio" name="rating" value="1" />
              1
              <?php } ?>
            </label>
            <label class="radio-inline">
              <?php if ($rating == 2) { ?>
              <input type="radio" name="rating" value="2" checked="checked" />
              2
              <?php } else { ?>
              <input type="radio" name="rating" value="2" />
              2
              <?php } ?>
            </label>
            <label class="radio-inline">
              <?php if ($rating == 3) { ?>
              <input type="radio" name="rating" value="3" checked="checked" />
              3
              <?php } else { ?>
              <input type="radio" name="rating" value="3" />
              3
              <?php } ?>
            </label>
            <label class="radio-inline">
              <?php if ($rating == 4) { ?>
              <input type="radio" name="rating" value="4" checked="checked" />
              4
              <?php } else { ?>
              <input type="radio" name="rating" value="4" />
              4
              <?php } ?>
            </label>
            <label class="radio-inline">
              <?php if ($rating == 5) { ?>
              <input type="radio" name="rating" value="5" checked="checked" />
              5
              <?php } else { ?>
              <input type="radio" name="rating" value="5" />
              5
              <?php } ?>
            </label>
            <?php if ($error_rating) { ?>
            <div class="text-danger"><?php echo $error_rating; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
          <div class="col-sm-10">
            <select name="status" id="input-status" class="form-control">
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