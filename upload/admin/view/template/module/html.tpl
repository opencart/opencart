<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-html" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-html" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="html_status" id="input-status" class="form-control">
                <?php if ($html_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-2">
              <ul class="nav nav-pills nav-stacked" id="module">
                <?php $module_row = 1; ?>
                <?php foreach ($html_modules as $html_module) { ?>
                <li><a href="#tab-module<?php echo $html_module['key']; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-module<?php echo $html_module['key']; ?>\']').parent().remove(); $('#tab-module<?php echo $html_module['key']; ?>').remove(); $('#module a:first').tab('show');"></i> <?php echo $tab_module . ' ' . $module_row; ?></a></li>
                <?php $module_row++; ?>
                <?php } ?>
                <li id="module-add"><a onclick="addModule();"><i class="fa fa-plus-circle"></i> <?php echo $button_module_add; ?></a></li>
              </ul>
            </div>
            <div class="col-sm-10">
              <div class="tab-content">
                <?php foreach ($html_modules as $html_module) { ?>
                <div class="tab-pane" id="tab-module<?php echo $html_module['key']; ?>">
                  <ul class="nav nav-tabs" id="language<?php echo $html_module['key']; ?>">
                    <?php foreach ($languages as $language) { ?>
                    <li><a href="#tab-module<?php echo $html_module['key']; ?>-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                    <?php } ?>
                  </ul>
                  <div class="tab-content">
                    <?php foreach ($languages as $language) { ?>
                    <div class="tab-pane" id="tab-module<?php echo $html_module['key']; ?>-language<?php echo $language['language_id']; ?>">
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-heading<?php echo $html_module['key']; ?>-language<?php echo $language['language_id']; ?>"><?php echo $entry_heading; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="html_module[<?php echo $html_module['key']; ?>][heading][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_heading; ?>" id="input-heading<?php echo $html_module['key']; ?>-language<?php echo $language['language_id']; ?>" value="<?php echo isset($html_module['heading'][$language['language_id']]) ? $html_module['heading'][$language['language_id']] : ''; ?>" class="form-control" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-description<?php echo $html_module['key']; ?>-language<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                        <div class="col-sm-10">
                          <textarea name="html_module[<?php echo $html_module['key']; ?>][description][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $html_module['key']; ?>-language<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($html_module['description'][$language['language_id']]) ? $html_module['description'][$language['language_id']] : ''; ?></textarea>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
<?php foreach ($html_modules as $html_module) { ?>
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $html_module['key']; ?>-language<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
<?php } ?>
<?php } ?>
//--></script> 
  <script type="text/javascript"><!--
  var module_row = <?php echo $module_row; ?>;
  
function addModule() {
	var token = Math.random().toString(36).substr(2);
	
	html  = '<div class="tab-pane" id="tab-module' + token + '">';
	html += '  <ul class="nav nav-tabs" id="language' + token + '">';
    <?php foreach ($languages as $language) { ?>
    html += '    <li><a href="#tab-module' + token + '-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>';
    <?php } ?>
	html += '  </ul>';

	html += '  <div class="tab-content">';

	<?php foreach ($languages as $language) { ?>
	html += '    <div class="tab-pane" id="tab-module' + token + '-language<?php echo $language['language_id']; ?>">';
	html += '      <div class="form-group">';
	html += '        <label class="col-sm-2 control-label" for="input-heading' + token + '-language<?php echo $language['language_id']; ?>"><?php echo $entry_heading; ?></label>';
	html += '        <div class="col-sm-10"><input type="text" name="html_module[' + token + '][heading][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_heading; ?>" id="input-heading' + token + '-language<?php echo $language['language_id']; ?>" value="" class="form-control"/></div>';
	html += '      </div>';
	html += '      <div class="form-group">';
	html += '        <label class="col-sm-2 control-label" for="input-description' + token + '-language<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>';
	html += '        <div class="col-sm-10"><textarea name="html_module[' + token + '][description][<?php echo $language['language_id']; ?>]" placeholder="<?php echo $entry_description; ?>" id="input-description' + token + '-language<?php echo $language['language_id']; ?>"></textarea></div>';
	html += '      </div>';
	html += '    </div>';
	<?php } ?>

	html += '  </div>';
	html += '</div>';

	$('.tab-content:first-child').prepend(html);

	<?php foreach ($languages as $language) { ?>
	$('#input-description' + token + '-language<?php echo $language['language_id']; ?>').summernote({
		height: 300
	});
	<?php } ?>

	$('#module-add').before('<li><a href="#tab-module' + token + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-module' + token + '\\\']\').parent().remove(); $(\'#tab-module' + token + '\').remove(); $(\'#module a:first\').tab(\'show\');"></i> <?php echo $tab_module; ?> ' + module_row + '</a></li>');

	$('#module a[href=\'#tab-module' + token + '\']').tab('show');

	$('#language' + token + ' li:first-child a').tab('show');

	module_row++;
}
//--></script> 
  <script type="text/javascript"><!--
$('#module li:first-child a').tab('show');
<?php foreach ($html_modules as $html_module) { ?>
$('#language<?php echo $html_module['key']; ?> li:first-child a').tab('show');
<?php } ?>
//--></script></div>
<?php echo $footer; ?>