<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-code"><span class="required">*</span> <?php echo $entry_code; ?></label>
          <div class="controls">
            <textarea name="google_talk_code" cols="40" rows="5" id="input-code"><?php echo $google_talk_code; ?></textarea>

            
            <a data-toggle="tooltip" title="<?php echo $help_code; ?>"><i class="icon-info-sign"></i></a>
            
            <?php if ($error_code) { ?>
            <span class="error"><?php echo $error_code; ?></span>
            <?php } ?>
          </div>
        </div>
        <table id="module" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
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
              <td class="left"><select name="google_talk_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="google_talk_module[<?php echo $module_row; ?>][position]">
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
              <td class="left"><select name="google_talk_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="google_talk_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
            </tr>
            <?php $module_row++; ?>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td class="left"><a onclick="addModule();" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tr id="module-row' + module_row + '">';
	html += '  <td class="left"><select name="google_talk_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '    <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '  </select></td>';
	html += '  <td class="left"><select name="google_talk_module[' + module_row + '][position]">';
	html += '    <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '    <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '    <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '  </select></td>';
	html += '  <td class="left"><select name="google_talk_module[' + module_row + '][status]">';
    html += '    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '    <option value="0"><?php echo $text_disabled; ?></option>';
    html += '  </select></td>';
	html += '  <td class="right"><input type="text" name="google_talk_module[' + module_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" /></td>';
	html += '  <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
	html += '</tr>';
	
	$('#module tbody').append(html);
	
	module_row++;
}
//--></script> 
<?php echo $footer; ?>