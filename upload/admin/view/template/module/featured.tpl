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
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="input-product"><?php echo $entry_product; ?></label>
          <div class="controls">
            <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" />
            <a data-toggle="tooltip" title="<?php echo $help_product; ?>"><i class="icon-info-sign"></i></a>
            <br />
            <div id="featured-product" class="well well-small scrollbox">
              <?php foreach ($products as $product) { ?>
              <div id="featured-product<?php echo $product['product_id']; ?>"><i class="icon-minus-sign"></i> <?php echo $product['name']; ?>
                <input type="hidden" value="<?php echo $product['product_id']; ?>" />
              </div>
              <?php } ?>
            </div>
            <input type="hidden" name="featured_product" value="<?php echo $featured_product; ?>" />
          </div>
        </div>
        <table id="module" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_limit; ?></td>
              <td class="left"><?php echo $entry_image; ?></td>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php $module_row = 0; ?>
            <?php foreach ($modules as $module) { ?>
            <tr id="module-row<?php echo $module_row; ?>">
              <td class="left"><input type="text" name="featured_module[<?php echo $module_row; ?>][limit]" value="<?php echo $module['limit']; ?>" placeholder="<?php echo $entry_limit; ?>" class="input-mini" /></td>
              <td class="left"><input type="text" name="featured_module[<?php echo $module_row; ?>][image_width]" value="<?php echo $module['image_width']; ?>" placeholder="<?php echo $entry_width; ?>" class="input-mini" />
                <input type="text" name="featured_module[<?php echo $module_row; ?>][image_height]" value="<?php echo $module['image_height']; ?>" placeholder="<?php echo $entry_height; ?>" class="input-mini" />
                <?php if (isset($error_image[$module_row])) { ?>
                <span class="error"><?php echo $error_image[$module_row]; ?></span>
                <?php } ?></td>
              <td class="left"><select name="featured_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="featured_module[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="featured_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="featured_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
            </tr>
            <?php $module_row++; ?>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6"></td>
              <td class="left"><a onclick="addModule();" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
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
		$('#featured-product' + item['value']).remove();
		
		$('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="icon-minus-sign"></i> ' + item['label'] + '<input type="hidden" value="' + item['value'] + '" /></div>');	
	
		data = $.map($('#featured-product input'), function(element){
			return $(element).attr('value');
		});
						
		$('input[name=\'featured_product\']').attr('value', data.join());	
	}	
});

$('#featured-product').delegate('.icon-minus-sign', 'click', function() {
	$(this).parent().remove();

	data = $.map($('#featured-product input'), function(element){
		return $(element).attr('value');
	});
					
	$('input[name=\'featured_product\']').attr('value', data.join());	
});
//--></script> 
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tr id="module-row' + module_row + '">';
	html += '  <td class="left"><input type="text" name="featured_module[' + module_row + '][limit]" value="5" placeholder="<?php echo $entry_limit; ?>" class="input-mini" /></td>';
	html += '  <td class="left"><input type="text" name="featured_module[' + module_row + '][image_width]" value="80" placeholder="<?php echo $entry_width; ?>" class="input-mini" /> <input type="text" name="featured_module[' + module_row + '][image_height]" value="80" placeholder="<?php echo $entry_height; ?>" class="input-mini" /></td>';	
	html += '  <td class="left"><select name="featured_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '    <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '  </select></td>';
	html += '  <td class="left"><select name="featured_module[' + module_row + '][position]">';
	html += '    <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '    <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '    <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '  </select></td>';
	html += '  <td class="left"><select name="featured_module[' + module_row + '][status]">';
    html += '    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '    <option value="0"><?php echo $text_disabled; ?></option>';
    html += '  </select></td>';
	html += '  <td class="right"><input type="text" name="featured_module[' + module_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" /></td>';
	html += '  <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	
	$('#module tbody').append(html);
	
	module_row++;
}
//--></script> 
<?php echo $footer; ?>