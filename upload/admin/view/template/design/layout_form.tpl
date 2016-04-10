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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-layout" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <fieldset>
            <legend>Drag and drop the modules</legend>
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
          </fieldset>
          <fieldset>
            <legend>Drag and drop the modules</legend>
            <div class="row">
              <div class="col-sm-3">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title"><?php echo $text_module; ?></h4>
                  </div>
                  <div class="panel-body">
                    <ul id="modules" class="list-unstyled">
                      <?php foreach ($extensions as $extension) { ?>
                      <li>
                        <h4><?php echo $extension['name']; ?></h4>
                      </li>
                      <?php if (!$extension['module']) { ?>
                      <li>
                        <div class="alert alert-info"><?php echo $extension['name']; ?><br />
                          <a href="<?php echo $extension['edit']; ?>" target="_blank" class="alert-link"><small>Edit</small></a> | <a href="#" class="alert-link"><small>Remove</small></a></div>
                      </li>
                      <?php } else { ?>
                      <?php foreach ($extension['module'] as $module) { ?>
                      <li>
                        <div class="alert alert-info"><?php echo $module['name']; ?><br />
                          <a href="<?php echo $module['edit']; ?>" target="_blank" class="alert-link">Edit</a> | <a href="#" class="alert-link">Remove</a>
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
              </div>
              <div class="col-sm-9">
                <?php $module_row = 0; ?>
                <div class="row">
                  <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title"><?php echo $text_column_left; ?></h4>
                      </div>
                      <div class="panel-body">
                        <ul id="layout-column-left" class="list-unstyled">
                          <?php foreach ($layout_modules as $layout_module) { ?>
                          <?php if ($layout_module['position'] == 'column_left') { ?>
                          <li>
                            <div class="alert alert-info"><?php echo $layout_module['name']; ?><br />
                              <a href="<?php echo $layout_module['edit']; ?>" target="_blank" class="alert-link">Edit</a> | <a href="#" class="alert-link">Remove</a>
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][code]" value="<?php echo $layout_module['code']; ?>" />
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][position]" value="<?php echo $layout_module['position']; ?>" />
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" />
                            </div>
                          </li>
                          <?php } ?>
                          <?php $module_row++; ?>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title"><?php echo $text_content_top; ?></h4>
                      </div>
                      <div class="panel-body">
                        <ul id="layout-content-top" class="list-unstyled">
                          <?php foreach ($layout_modules as $layout_module) { ?>
                          <?php if ($layout_module['position'] == 'content_top') { ?>
                          <li>
                            <div class="alert alert-info"><?php echo $layout_module['name']; ?><br />
                              <a href="<?php echo $layout_module['edit']; ?>" target="_blank" class="alert-link">Edit</a> | <a href="#" class="alert-link">Remove</a>
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][code]" value="<?php echo $layout_module['code']; ?>" />
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][position]" value="<?php echo $layout_module['position']; ?>" />
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" />
                            </div>
                          </li>
                          <?php } ?>
                          <?php $module_row++; ?>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4 class="panel-title"><?php echo $text_content_bottom; ?></h4>
                      </div>
                      <div class="panel-body">
                        <ul id="layout-content-bottom" class="list-unstyled">
                          <?php foreach ($layout_modules as $layout_module) { ?>
                          <?php if ($layout_module['position'] == 'content_bottom') { ?>
                          <li>
                            <div class="alert alert-info"><?php echo $layout_module['name']; ?><br />
                              <a href="<?php echo $layout_module['edit']; ?>" target="_blank" class="alert-link">Edit</a> | <a href="#" class="alert-link">Remove</a>
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][code]" value="<?php echo $layout_module['code']; ?>" />
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][position]" value="<?php echo $layout_module['position']; ?>" />
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" />
                            </div>
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
                      <div class="panel-heading">
                        <h4 class="panel-title"><?php echo $text_column_right; ?></h4>
                      </div>
                      <div class="panel-body">
                        <ul id="layout-column-right" class="list-unstyled">
                          <?php foreach ($layout_modules as $layout_module) { ?>
                          <?php if ($layout_module['position'] == 'column_right') { ?>
                          <li>
                            <div class="alert alert-info"><?php echo $layout_module['name']; ?><br />
                              <a href="<?php echo $layout_module['edit']; ?>" target="_blank" class="alert-link">Edit</a> | <a href="#" class="alert-link">Remove</a>
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][code]" value="<?php echo $layout_module['code']; ?>" />
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][position]" value="<?php echo $layout_module['position']; ?>" />
                              <input type="hidden" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" />
                            </div>
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
          </fieldset>
        </form>
      </div>
    </div>
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
	helper: 'clone',
	revert: 'invalid',
	opacity: 0.35,
	zIndex: 100,
	cursor: 'arrow',
	start: function(e, ui) {
	
	},
	drag: function(e, ui) {
		$(ui.helper).width($(e.target).width());
		$(ui.helper).height($(e.target).height());	
	},
	stop: function(e, ui) {
		$(ui.helper).removeClass('active');
		$(ui.helper).removeAttr('style');
				
		if ($(ui.helper).parent().attr('id') == 'layout-column-left') {
			$('').each('', function() {
				
			});
		}
		
		if ($(ui.helper).parent().attr('id') == 'layout-column-right') {
			
		}	
		
		if ($(ui.helper).parent().attr('id') == 'layout-content-top') {
			
		}
		
		if ($(ui.helper).parent().attr('id') == 'layout-content-bottom') {
			
		}	
	}	
});

$('#layout-column-left, #layout-column-right, #layout-content-top, #layout-content-bottom').sortable({
	forcePlaceholderSize: true,
	opacity: 0.35,
	zIndex: 100,
	start: function(e, ui) {
		
	},
	stop: function(e, ui) {
		
	}		
});

$('#layout-column-left, #layout-column-right, #layout-content-top, #layout-content-bottom').delegate('li button', 'click', function() {
	$(this).parent().parent().remove();
});
//--></script> 
</div>
<?php echo $footer; ?>