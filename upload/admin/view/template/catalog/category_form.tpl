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
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
          <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <ul class="nav nav-tabs" id="language">
              <?php foreach ($languages as $language) { ?>
              <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
              <?php } ?>
            </ul>
            <div class="tab-content">
              <?php foreach ($languages as $language) { ?>
              <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                <div class="control-group error required">
                  <label class="control-label" for="input-name<?php echo $language['language_id']; ?>"><span class="required">*</span> <?php echo $entry_name; ?></label>
                  <div class="controls">
                    <input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="input-xxlarge" />
                    <?php if (isset($error_name[$language['language_id']])) { ?>
                    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                    <?php } ?>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                  <div class="controls">
                    <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                  <div class="controls">
                    <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                  <div class="controls">
                    <textarea name="category_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="tab-pane" id="tab-data">
            <div class="control-group">
              <label class="control-label" for="input-parent"><?php echo $entry_parent; ?></label>
              <div class="controls">
                <input type="text" name="path" value="<?php echo $path; ?>" placeholder="<?php echo $entry_parent; ?>" id="input-parent" />
                <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-filter"><?php echo $entry_filter; ?></label>
              <div class="controls">
                <input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" />
                <a data-toggle="tooltip" title="<?php echo $help_filter; ?>"><i class="icon-info-sign"></i></a> <br />
                <br />
                <div id="category-filter" class="well well-small scrollbox">
                  <?php foreach ($category_filters as $category_filter) { ?>
                  <div id="category-filter<?php echo $category_filter['filter_id']; ?>"><i class="icon-minus-sign"></i> <?php echo $category_filter['name']; ?>
                    <input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $entry_store; ?></div>
              <div class="controls">
                <label class="checkbox">
                  <?php if (in_array(0, $category_store)) { ?>
                  <input type="checkbox" name="category_store[]" value="0" checked="checked" />
                  <?php echo $text_default; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="category_store[]" value="0" />
                  <?php echo $text_default; ?>
                  <?php } ?>
                </label>
                <?php foreach ($stores as $store) { ?>
                <label class="checkbox">
                  <?php if (in_array($store['store_id'], $category_store)) { ?>
                  <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                  <?php echo $store['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                  <?php echo $store['name']; ?>
                  <?php } ?>
                </label>
                <?php } ?>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-keyword"><?php echo $entry_keyword; ?></label>
              <div class="controls">
                <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" />
                <a data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-name"><?php echo $entry_image; ?></label>
              <div class="controls">
                <ul class="thumbnails">
                  <li><a class="thumbnail" href="#"><img alt="" src="<?php echo $thumb; ?>"></a>
                    <ul class="thumbnail-option">
                      <li><a href="#" title="Edit"><span class="icon-edit"></span></a></li>
                      <li><a href="#" title="Delete"><span class="icon-trash"></span></a></li>
                    </ul>
                  </li>
                </ul>
                <div class="btn-group">
                  <button data-toggle="modal" data-target="#modal" class="btn btn-small"><i class="icon-edit"></i></button>
                  <button onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');" class="btn btn-small"><i class="icon-remove"></i></button>
                </div>
                <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-top"><?php echo $entry_top; ?></label>
              <div class="controls">
                <label class="checkbox inline">
                  <?php if ($top) { ?>
                  <input type="checkbox" name="top" value="1" checked="checked" id="input-top" />
                  <?php } else { ?>
                  <input type="checkbox" name="top" value="1" id="input-top" />
                  <?php } ?>
                </label>
                <a data-toggle="tooltip" data-original-title="<?php echo $help_top; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-column"><?php echo $entry_column; ?></label>
              <div class="controls">
                <input type="text" name="column" value="<?php echo $column; ?>" placeholder="<?php echo $entry_column; ?>" id="input-column" class="input-mini" />
                <a data-toggle="tooltip" title="<?php echo $help_column; ?>"><i class="icon-info-sign"></i></a></div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
              <div class="controls">
                <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
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
          </div>
          <div class="tab-pane" id="tab-design">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_store; ?></td>
                  <td class="left"><?php echo $entry_layout; ?></td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="left"><?php echo $text_default; ?></td>
                  <td class="left"><select name="category_layout[0][layout_id]">
                      <option value=""></option>
                      <?php foreach ($layouts as $layout) { ?>
                      <?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
                      <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select></td>
                </tr>
                <?php foreach ($stores as $store) { ?>
                <tr>
                  <td class="left"><?php echo $store['name']; ?></td>
                  <td class="left"><select name="category_layout[<?php echo $store['store_id']; ?>][layout_id]">
                      <option value=""></option>
                      <?php foreach ($layouts as $layout) { ?>
                      <?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
                      <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      </form>
    </div>
  </div>
</div>
<div id="modal" style="width: 60%; height: 60%;" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="modal-label"><?php echo $text_image_manager; ?></h3>
  </div>
  <div class="modal-body">
    <iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>" style="width: 90%; height: 90%;" frameborder="no" scrolling="auto"></iframe>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('input-description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'path\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				json.unshift({
					'category_id': 0,
					'name': '<?php echo $text_none; ?>'
				});
				
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'path\']').val(item['label']);
		$('input[name=\'parent_id\']').val(item['value']);
	}	
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['filter_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter\']').val('');
		
		$('#category-filter' + item['value']).remove();
		
		$('#category-filter').append('<div id="category-filter' + item['value'] + '"><i class="icon-minus-sign"></i> ' + item['label'] + '<input type="hidden" name="category_filter[]" value="' + item['value'] + '" /></div>');
	}	
});

$('#category-filter').delegate('.icon-minus-sign', 'click', function() {
	$(this).parent().remove();
});
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script> 
<?php echo $footer; ?>