<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-layout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-layout" class="form-horizontal">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
        </div>
        <div class="panel-body">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <table id="route" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_store; ?></td>
                <td class="text-left"><?php echo $entry_route; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $route_row = 0; ?>
              <?php foreach ($layout_routes as $layout_route) { ?>
              <tr id="route-row<?php echo $route_row; ?>">
                <td class="text-left"><select name="layout_route[<?php echo $route_row; ?>][store_id]" class="form-control">
                    <option value="0"><?php echo $text_default; ?></option>
                    <?php foreach ($stores as $store) { ?>
                    <?php if ($store['store_id'] == $layout_route['store_id']) { ?>
                    <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><input type="text" name="layout_route[<?php echo $route_row; ?>][route]" value="<?php echo $layout_route['route']; ?>" placeholder="<?php echo $entry_route; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#route-row<?php echo $route_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $route_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="text-left"><button type="button" onclick="addRoute();" data-toggle="tooltip" title="<?php echo $button_route_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_module; ?></h3>
            </div>
            <ul id="modules" class="list-group">
              <?php foreach ($extensions as $extension) { ?>
              <?php if (!$extension['module']) { ?>
              <li class="list-group-item"><i class="fa fa-arrows fa-fw"></i> <?php echo $extension['name']; ?>
                <div class="pull-right"><a href="<?php echo $extension['edit']; ?>" target="_blank"><i class="fa fa-pencil fa-fw"></i></a> <i class="fa fa-trash fa-fw"></i></div>
              </li>
              <?php } else { ?>
              <li class="list-group-item"><strong><?php echo $extension['name']; ?></strong></li>
              <?php foreach ($extension['module'] as $module) { ?>
              <li class="list-group-item"><i class="fa fa-arrows fa-fw"></i> <?php echo $module['name']; ?>
                <div class="pull-right"><a href="<?php echo $module['edit']; ?>" target="_blank"><i class="fa fa-pencil fa-fw"></i></a>
                  <i class="fa fa-trash fa-fw"></i>
                  <input type="hidden" name="code" value="<?php echo $module['code']; ?>" />
                  <input type="hidden" name="position" value="" />
                  <input type="hidden" name="sort_order" value="" />
                </div>
              </li>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </div>
        <div class="col-sm-9">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_layout; ?></h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <?php $module_row = 0; ?>
                <div class="col-lg-3 col-md-3 col-sm-6">
                  <div class="panel panel-default">
                    <div class="panel-heading"><?php echo $text_column_left; ?></div>
                    <ul id="layout-column-left" class="list-group">
                      <?php foreach ($layout_modules as $layout_module) { ?>
                      <?php if ($layout_module['position'] == 'column_left') { ?>
                      <li class="list-group-item"><i class="fa fa-arrows fa-fw"></i> <?php echo $layout_module['name']; ?>
                        <div class="pull-right"><i class="fa fa-pencil fa-fw"></i> <i class="fa fa-trash fa-fw"></i></div>
                        <input type="hidden" name="layout_module[<?php echo $module_row; ?>][code]" value="<?php echo $layout_module['code']; ?>" />
                        <input type="hidden" name="layout_module[<?php echo $module_row; ?>][position]" value="<?php echo $layout_module['position']; ?>" />
                        <input type="hidden" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" />
                      </li>
                      <?php } ?>
                      <?php $module_row++; ?>
                      <?php } ?>
                    </ul>
                  </div>
                </div>
                <div id="layout-content" class="col-lg-6 col-md-6 col-sm-12">
                  <div class="col-xs-12">
                    <div class="panel panel-default">
                      <div class="panel-heading"><?php echo $text_content_top; ?></div>
                      <ul id="layout-content-top" class="list-group">
                        <?php foreach ($layout_modules as $layout_module) { ?>
                        <?php if ($layout_module['position'] == 'content_top') { ?>
                        <li class="list-group-item"><i class="fa fa-arrows fa-fw"></i> <?php echo $layout_module['name']; ?>
                          <div class="pull-right"><i class="fa fa-pencil fa-fw"></i> <i class="fa fa-trash fa-fw"></i></div>
                          <input type="hidden" name="layout_module[<?php echo $module_row; ?>][code]" value="<?php echo $layout_module['code']; ?>" />
                          <input type="hidden" name="layout_module[<?php echo $module_row; ?>][position]" value="<?php echo $layout_module['position']; ?>" />
                          <input type="hidden" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" />
                        </li>
                        <?php } ?>
                        <?php $module_row++; ?>
                        <?php } ?>
                      </ul>
                    </div>
                  </div>
                  <div  class="col-xs-12">
                    <div class="panel panel-default">
                      <div class="panel-heading"><?php echo $text_content_bottom; ?></div>
                      <ul id="layout-content-bottom" class="list-group">
                        <?php foreach ($layout_modules as $layout_module) { ?>
                        <?php if ($layout_module['position'] == 'content_bottom') { ?>
                        <li class="list-group-item"><i class="fa fa-arrows fa-fw"></i> <?php echo $layout_module['name']; ?>
                          <div class="pull-right"><i class="fa fa-pencil fa-fw"></i> <i class="fa fa-trash fa-fw"></i></div>
                          <input type="hidden" name="layout_module[<?php echo $module_row; ?>][code]" value="<?php echo $layout_module['code']; ?>" />
                          <input type="hidden" name="layout_module[<?php echo $module_row; ?>][position]" value="<?php echo $layout_module['position']; ?>" />
                          <input type="hidden" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" />
                        </li>
                        <?php } ?>
                        <?php $module_row++; ?>
                        <?php } ?>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                  <div class="panel panel-default">
                    <div class="panel-heading"><?php echo $text_column_right; ?></div>
                    <ul id="layout-column-right" class="list-group">
                      <?php foreach ($layout_modules as $layout_module) { ?>
                      <?php if ($layout_module['position'] == 'column_right') { ?>
                      <li class="list-group-item"><i class="fa fa-arrows fa-fw"></i> <?php echo $layout_module['name']; ?>
                        <div class="pull-right"><i class="fa fa-pencil fa-fw"></i> <i class="fa fa-trash fa-fw"></i></div>
                        <input type="hidden" name="layout_module[<?php echo $module_row; ?>][code]" value="<?php echo $layout_module['code']; ?>" />
                        <input type="hidden" name="layout_module[<?php echo $module_row; ?>][position]" value="<?php echo $layout_module['position']; ?>" />
                        <input type="hidden" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" />
                      </li>
                      <?php } ?>
                      <?php $module_row++; ?>
                      <?php } ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br />
      <table id="module" class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $entry_module; ?></td>
            <td class="text-left"><?php echo $entry_position; ?></td>
            <td class="text-right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php $module_row = 0; ?>
          <?php foreach ($layout_modules as $layout_module) { ?>
          <tr id="module-row<?php echo $module_row; ?>">
            <td class="text-left"><select name="layout_module[<?php echo $module_row; ?>][code]" class="form-control">
                <?php foreach ($extensions as $extension) { ?>
                <?php if (!$extension['module']) { ?>
                <?php if ($extension['code'] == $layout_module['code']) { ?>
                <option value="<?php echo $extension['code']; ?>" selected="selected"><?php echo $extension['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $extension['code']; ?>"><?php echo $extension['name']; ?></option>
                <?php } ?>
                <?php } else { ?>
                <optgroup label="<?php echo $extension['name']; ?>">
                <?php foreach ($extension['module'] as $module) { ?>
                <?php if ($module['code'] == $layout_module['code']) { ?>
                <option value="<?php echo $module['code']; ?>" selected="selected"><?php echo $module['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $module['code']; ?>"><?php echo $module['name']; ?></option>
                <?php } ?>
                <?php } ?>
                </optgroup>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="text-left"><select name="layout_module[<?php echo $module_row; ?>][position]" class="form-control">
                <?php if ($layout_module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>
                <?php if ($layout_module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>
                <?php if ($layout_module['position'] == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($layout_module['position'] == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select></td>
            <td class="text-right"><input type="text" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
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
  <script type="text/javascript"><!--
var route_row = <?php echo $route_row; ?>;

function addRoute() {
	html  = '<tr id="route-row' + route_row + '">';
	html += '  <td class="text-left"><select name="layout_route[' + route_row + '][store_id]" class="form-control">';
	html += '  <option value="0"><?php echo $text_default; ?></option>';
	<?php foreach ($stores as $store) { ?>
	html += '<option value="<?php echo $store['store_id']; ?>"><?php echo addslashes($store['name']); ?></option>';
	<?php } ?>   
	html += '  </select></td>';
	html += '  <td class="text-left"><input type="text" name="layout_route[' + route_row + '][route]" value="" placeholder="<?php echo $entry_route; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#route-row' + route_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#route tbody').append(html);
	
	route_row++;
}

$('#modules li').draggable({
	connectToSortable: '#layout-column-left, #layout-column-right, #layout-content-top, #layout-content-bottom',
	handle: '.fa-arrows',
	helper: 'clone',
	revert: 'invalid',
	opacity: 0.35,
	start: function(e, element) {
		$(element.helper).addClass('active');
	},
	drag: function(e, element) {
		$(element.helper).width($(e.target).width());
		$(element.helper).height($(e.target).height());	
	},
	stop: function(e, element) {
		$(element.helper).removeClass('active');
		$(element.helper).removeAttr('style');
	}	
});

$('#layout-column-left, #layout-column-right, #layout-content-top, #layout-content-bottom').sortable({
	forcePlaceholderSize: true,
	handle: '.fa-arrows'
});

$('#layout-column-left, #layout-column-right, #layout-content-top, #layout-content-bottom').delegate('li .fa-trash', 'click', function() {
	$(this).parent().parent().remove();
});
//--></script></div>
<?php echo $footer; ?>