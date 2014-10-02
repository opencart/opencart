<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-puzzle-piece fa-lg"></i> <?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
        <p>The eBay display module allows you to display products from your eBay account directly on your website.</p>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
          <div class="col-sm-10">
            <input type="text" name="ebaydisplay_module_username" value="<?php echo $ebaydisplay_module_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-keywords"><?php echo $entry_keywords; ?></label>
          <div class="col-sm-10">
            <input type="text" name="ebaydisplay_module_keywords" value="<?php echo $ebaydisplay_module_keywords; ?>" placeholder="<?php echo $entry_keywords; ?>" id="input-keywords" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
          <div class="col-sm-10">
            <select name="ebaydisplay_module_description" id="input-description" class="form-control">
              <?php if ($ebaydisplay_module_description) { ?>
              <option value="1" selected="selected"><?php echo $text_yes; ?></option>
              <option value="0"><?php echo $text_no; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_yes; ?></option>
              <option value="0" selected="selected"><?php echo $text_no; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
          <div class="col-sm-10">
            <input type="text" name="ebaydisplay_module_limit" value="<?php echo $ebaydisplay_module_limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-sort"><?php echo $entry_sort_order; ?></label>
          <div class="col-sm-10">
            <select name="ebaydisplay_module_sort" id="input-sort" class="form-control">
              <option value="StartTimeNewest" <?php echo ($ebaydisplay_module_sort == 'StartTimeNewest' ? 'selected' : ''); ?>><?php echo $text_start_newest; ?></option>
              <option value="random" <?php echo ($ebaydisplay_module_sort == 'random' ? 'selected' : ''); ?>><?php echo $text_start_random; ?></option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-site"><?php echo $entry_site; ?></label>
          <div class="col-sm-10">
            <select name="ebaydisplay_module_site" id="input-site" class="form-control">
              <?php foreach($ebay_sites as $id => $site) { ?>
              <option value="<?php echo $id; ?>" <?php echo $id == $ebaydisplay_module_site ? ' selected' : ''; ?>><?php echo $site; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <table id="module" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-left"><?php echo $entry_limit; ?></th>
              <th class="text-left"><?php echo $entry_image; ?></th>
              <th class="text-left"><?php echo $entry_layout; ?></th>
              <th class="text-left"><?php echo $entry_position; ?></th>
              <th class="text-left"><?php echo $entry_status; ?></th>
              <th class="text-right"><?php echo $entry_sort_order; ?></th>
              <th></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="text-left"><input type="text" name="ebaydisplay_module[<?php echo $module_row; ?>][limit]" value="<?php echo $module['limit']; ?>" size="1" class="form-control" /></td>
              <td class="text-left"><input type="text" name="ebaydisplay_module[<?php echo $module_row; ?>][image_width]" value="<?php echo $module['image_width']; ?>" size="3" class="form-control" />
                <input type="text" name="ebaydisplay_module[<?php echo $module_row; ?>][image_height]" value="<?php echo $module['image_height']; ?>" size="3" class="form-control"/>
                <?php if (isset($error_image[$module_row])) { ?>
                <span class="error"><?php echo $error_image[$module_row]; ?></span>
                <?php } ?></td>
              <td class="text-left"><select name="ebaydisplay_module[<?php echo $module_row; ?>][layout_id]" class="form-control">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="text-left"><select name="ebaydisplay_module[<?php echo $module_row; ?>][position]" class="form-control">
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
              <td class="text-left"><select name="ebaydisplay_module[<?php echo $module_row; ?>][status]" class="form-control">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="text-right"><input type="text" name="ebaydisplay_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" class="form-control" /></td>
              <td class="text-left"><button type="button" onclick="$('#module-row<?php echo $module_row; ?>').remove();"class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="6"></td>
              <td class="text-left"><button type="button" onclick="addModule();"class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_module_add; ?></button></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
  <script type="text/javascript"><!--
    var module_row = <?php echo $module_row; ?>;

    function addModule() {
        html  = '<tbody id="module-row' + module_row + '">';
        html += '  <tr>';
        html += '    <td class="text-left"><input type="text" name="ebaydisplay_module[' + module_row + '][limit]" value="5" size="1" class="form-control" /></td>';
        html += '    <td class="text-left"><input type="text" name="ebaydisplay_module[' + module_row + '][image_width]" value="80" size="3" class="form-control" /> <input type="text" name="ebaydisplay_module[' + module_row + '][image_height]" value="80" size="3" class="form-control" /></td>';
        html += '    <td class="text-left"><select name="ebaydisplay_module[' + module_row + '][layout_id]" class="form-control">';
    <?php foreach ($layouts as $layout) { ?>
            html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
        <?php } ?>
        html += '    </select></td>';
        html += '    <td class="text-left"><select name="ebaydisplay_module[' + module_row + '][position]" class="form-control">';
        html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
        html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
        html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
        html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
        html += '    </select></td>';
        html += '    <td class="text-left"><select name="ebaydisplay_module[' + module_row + '][status]" class="form-control">';
        html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
        html += '      <option value="0"><?php echo $text_disabled; ?></option>';
        html += '    </select></td>';
        html += '    <td class="text-right"><input type="text" name="ebaydisplay_module[' + module_row + '][sort_order]" value="" size="3" class="form-control" /></td>';
        html += '    <td class="text-left"><a onclick="$(\'#module-row' + module_row + '\').remove();"class="btn btn-primary"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#module tfoot').before(html);

        module_row++;
    }
    //--></script></div>
<?php echo $footer; ?>