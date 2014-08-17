<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-pp-login" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error['error_warning'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pp-login" class="form-horizontal">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="entry-client_id"><?php echo $entry_client_id; ?></label>
        <div class="col-sm-10">
          <input type="text" name="pp_login_client_id" value="<?php echo $pp_login_client_id; ?>" placeholder="<?php echo $entry_client_id; ?>" id="entry-client_id" class="form-control"/>
          <?php if ($error_client_id) { ?>
          <div class="text-danger"><?php echo $error_client_id; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="entry-secret"><?php echo $entry_secret; ?></label>
        <div class="col-sm-10">
          <input type="text" name="pp_login_secret" value="<?php echo $pp_login_secret; ?>" placeholder="<?php echo $entry_secret; ?>" id="entry-secret" class="form-control"/>
          <?php if ($error_secret) { ?>
          <div class="text-danger"><?php echo $error_secret; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="entry-sandbox"><span data-toggle="tooltip" title="<?php echo $help_sandbox; ?>"><?php echo $entry_sandbox; ?></span></label>
        <div class="col-sm-10">
          <select name="pp_login_sandbox" id="entry-sandbox" class="form-control">
            <?php if ($pp_login_sandbox) { ?>
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
        <label class="col-sm-2 control-label" for="input-loggin"><span data-toggle="tooltip" title="<?php echo $help_debug_logging; ?>"><?php echo $entry_logging; ?></span></label>
        <div class="col-sm-10">
          <select name="pp_login_logging" id="input-logging" class="form-control">
            <?php if ($pp_login_logging) { ?>
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
        <label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="<?php echo $help_customer_group; ?>"><?php echo $entry_customer_group; ?></span></label>
        <div class="col-sm-10">
          <select name="pp_login_customer_group_id" id="input-customer-group" class="form-control">
            <?php foreach ($customer_groups as $customer_group) { ?>
            <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-button_colour"><?php echo $entry_button; ?></label>
        <div class="col-sm-10">
          <select name="pp_login_button_colour" id="input-button_colour" class="form-control">
            <?php if ($pp_login_button_colour == 'blue') { ?>
            <option value="blue" selected="selected"><?php echo $text_blue_button; ?></option>
            <option value="grey"><?php echo $text_grey_button; ?></option>
            <?php } else { ?>
            <option value="blue"><?php echo $text_blue_button; ?></option>
            <option value="grey" selected="selected"><?php echo $text_grey_button; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-seamless"><span data-toggle="tooltip" title="<?php echo $help_seamless; ?>"><?php echo $entry_seamless; ?></span></label>
        <div class="col-sm-10">
          <select name="pp_login_seamless" id="input-logging" class="form-control">
            <?php if ($pp_login_seamless) { ?>
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
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_locale; ?>"><?php echo $entry_locale; ?></span></label>
        <div class="col-sm-10">
          <?php foreach ($languages as $language) { ?>
          <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
            <select name="pp_login_locale[<?php echo $language['language_id']; ?>]" class="form-control">
              <?php foreach ($locales as $locale) { ?>
              <?php if (isset($pp_login_locale[$language['language_id']]) && $pp_login_locale[$language['language_id']] == $locale['value']) { ?>
              <option value="<?php echo $locale['value']; ?>" selected="selected"><?php echo $locale['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $locale['value']; ?>"><?php echo $locale['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_return_url; ?>"><?php echo $entry_return_url; ?></span></label>
        <div class="col-sm-10">
          <input type="text" readonly="readonly" id="return-url" value="<?php echo $pp_login_return_url; ?>" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10">
          <select name="pp_login_status" id="input-status" class="form-control">
            <?php if ($pp_login_status) { ?>
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
            <td class="text-left"><?php echo $entry_layout; ?></td>
            <td class="text-left"><?php echo $entry_position; ?></td>
            <td class="text-left"><?php echo $entry_status; ?></td>
            <td class="text-right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tr id="module-row<?php echo $module_row; ?>">
            <td class="text-left"><select name="pp_login_module[<?php echo $module_row; ?>][layout_id]" class="form-control">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="text-left"><select name="pp_login_module[<?php echo $module_row; ?>][position]" class="form-control">
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
            <td class="text-left"><select name="pp_login_module[<?php echo $module_row; ?>][status]" class="form-control">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="text-right"><input type="text" name="pp_login_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
            <td class="text-left"><button type="button" onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></td>
          </tr>
          <?php $module_row++; ?>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="4"></td>
            <td class="text-left"><button type="button" onclick="addModule();" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_module_add; ?></button></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tr id="module-row' + module_row + '">';
	html += '  <td class="text-left"><select name="pp_login_module[' + module_row + '][layout_id]" class="form-control">';
	<?php foreach ($layouts as $layout) { ?>
	html += '    <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '  </select></td>';
	html += '  <td class="text-left"><select name="pp_login_module[' + module_row + '][position]" class="form-control">';
	html += '    <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '    <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '    <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '  </select></td>';
	html += '  <td class="text-left"><select name="pp_login_module[' + module_row + '][status]" class="form-control">';
    html += '    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '    <option value="0"><?php echo $text_disabled; ?></option>';
    html += '  </select></td>';
	html += '  <td class="text-right"><input type="text" name="pp_login_module[' + module_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#module-row' + module_row + '\').remove();" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_remove; ?></button></td>';
	html += '</tr>';
	
	$('#module tbody').append(html);
	
	module_row++;
}
//--></script> 
<?php echo $footer; ?>