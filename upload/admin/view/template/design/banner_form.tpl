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
        <button type="submit" form="form-banner" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
          <div class="col-sm-10">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
            <?php if ($error_name) { ?>
            <div class="text-danger"><?php echo $error_name; ?></div>
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
        <table id="images" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $entry_title; ?></td>
              <td class="text-left"><?php echo $entry_link; ?></td>
              <td class="text-left"><?php echo $entry_image; ?></td>
              <td class="text-right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php $image_row = 0; ?>
            <?php foreach ($banner_images as $banner_image) { ?>
            <tr id="image-row<?php echo $image_row; ?>">
              <td class="text-left"><?php foreach ($languages as $language) { ?>
                <div class="input-group pull-left"> <span class="input-group-addon"> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> </span>
                  <input type="text" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
                </div>
                <?php if (isset($error_banner_image[$image_row][$language['language_id']])) { ?>
                <div class="text-danger"><?php echo $error_banner_image[$image_row][$language['language_id']]; ?></div>
                <?php } ?>
                <?php } ?></td>
              <td class="text-left"><input type="text" name="banner_image[<?php echo $image_row; ?>][link]" value="<?php echo $banner_image['link']; ?>" placeholder="<?php echo $entry_link; ?>" class="form-control" /></td>
              <td class="text-left"><div class="image"><img src="<?php echo $banner_image['thumb']; ?>" alt="" class="img-thumbnail" />
                  <input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" />
                  <div class="image-option"><a href="#" title="<?php echo $button_edit; ?>" data-toggle="modal" data-target="#modal"><span class="icon-pencil"></span></a> <a href="#" title="<?php echo $button_clear; ?>" onclick="$(this).parent().parent().find('img').attr('src', '<?php echo $no_image; ?>'); $(this).parent().parent().find('input').attr('value', ''); return false;"><span class="icon-trash"></span></a></div>
                </div></td>
              <td class="text-right"><input type="text" name="banner_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $banner_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
              <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="btn btn-danger"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></button></td>
            </tr>
            <?php $image_row++; ?>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td class="text-left"><button type="button" onclick="addImage();" class="btn btn-primary"><i class="icon-plus-sign"></i> <?php echo $button_add_banner; ?></button></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
	html  = '<tr id="image-row' + image_row + '">';
    html += '  <td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '    <div class="input-group">';
	html += '      <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="" placeholder="<?php echo $entry_title; ?>" class="form-control" />';
    html += '    </div>';
	<?php } ?>
	html += '  </td>';	
	html += '  <td class="text-left"><input type="text" name="banner_image[' + image_row + '][link]" value="" placeholder="<?php echo $entry_link; ?>" class="form-control" /></td>';	
	html += '  <td class="text-left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" class="img-thumbnail" /><input type="hidden" name="banner_image[' + image_row + '][image]" value="" /><div class="image-option"><a href="#" title="<?php echo $button_edit; ?>" data-toggle="modal" data-target="#modal"><span class="icon-pencil"></span></a> <a href="#" title="<?php echo $button_clear; ?>" onclick="$(this).parent().parent().find(\'img\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(this).parent().parent().find(\'input\').attr(\'value\', \'\');"><span class="icon-trash"></span></a></div></div></td>';
	html += '  <td class="text-right"><input type="text" name="banner_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" class="btn btn-danger"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></button></td>';
	html += '</tr>';
	
	$('#images tbody').append(html);
	
	image_row++;
}
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
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
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
<?php echo $footer; ?>