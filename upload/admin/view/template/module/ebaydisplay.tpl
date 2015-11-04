<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
        <p><?php echo $text_about; ?></p>
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
          <label class="col-sm-2 control-label" for="input-sort"><?php echo $entry_sort; ?></label>
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
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="ebaydisplay_status" id="input-status" class="form-control">
                <?php if ($ebaydisplay_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <table id="module" class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-right">#</td>
              <td class="text-left"><?php echo $entry_limit; ?></td>
              <td class="text-left"><?php echo $entry_image; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
          <?php $module_row = 1; ?>
          <?php foreach ($ebaydisplay_modules as $module) { ?>
            <tr id="module-row<?php echo $module['key']; ?>">
              <td class="text-right"><?php echo $module_row; ?></td>
              <td class="text-left"><input type="text" name="ebaydisplay_module[<?php echo $module['key']; ?>][limit]" value="<?php echo $module['limit']; ?>" size="1" class="form-control" /></td>
              <td class="text-left">
                <input type="text" name="ebaydisplay_module[<?php echo $module['key']; ?>][width]" value="<?php echo $module['width']; ?>" size="3" class="form-control" />
                <input type="text" name="ebaydisplay_module[<?php echo $module['key']; ?>][height]" value="<?php echo $module['height']; ?>" size="3" class="form-control"/>
                <?php if (isset($error_image[$module_row])) { ?>
                <span class="error"><?php echo $error_image[$module_row]; ?></span>
                <?php } ?>
              </td>
              <td class="text-left"><button type="button" onclick="$('#module-row<?php echo $module_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
          <?php $module_row++; ?>
          <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="text-left"><button type="button" onclick="addModule();" data-toggle="tooltip" title="<?php echo $button_module_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
            </tr>
          </tfoot>
        </table>
      </form>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript"><!--
    var module_row = <?php echo $module_row; ?>;

    function addModule() {
      var token = Math.random().toString(36).substr(2);

      html  = '<tr id="module-row' + token + '">';
      html += '  <td class="text-right">' + ($('tbody tr').length + 1) + '</td>';
      html += '  <td class="text-left"><input type="text" name="ebaydisplay_module[' + token + '][limit]" value="5" placeholder="<?php echo $entry_limit; ?>" class="form-control" /></td>';
      html += '  <td class="text-left"><input type="text" name="ebaydisplay_module[' + token + '][width]" value="200" placeholder="<?php echo $entry_width; ?>" class="form-control" /> <input type="text" name="ebaydisplay_module[' + token + '][height]" value="200" placeholder="<?php echo $entry_height; ?>" class="form-control" /></td>';
      html += '  <td class="text-left"><button type="button" onclick="$(\'#module-row' + token + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
      html += '</tr>';

      $('#module tbody').append(html);
    }
    //--></script></div>
<?php echo $footer; ?>