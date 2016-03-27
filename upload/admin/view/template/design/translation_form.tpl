<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-banner" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-translation" class="form-horizontal">
          <table id="translation" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_store; ?></td>
                <td class="text-left"><?php echo $entry_language; ?></td>
                <td class="text-center"><?php echo $entry_key; ?></td>
                <td class="text-right"><?php echo $entry_value; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $translation_row = 0; ?>
              <?php foreach ($translations as $translation) { ?>
              <tr id="translation-row<?php echo $translation_row; ?>">
                <td class="text-left"><select name="translation[<?php echo $translation_row; ?>][store_id]" class="form-control">
                    <?php foreach ($stores as $store) { ?>
                    <?php if ($store['store_id'] == $translation['store_id']) { ?>
                    <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select name="translation[<?php echo $translation_row; ?>][language_id]" class="form-control">
                    <?php foreach ($languages as $language) { ?>
                    <?php if ($language['language_id'] == $translation['language_id']) { ?>
                    <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select name="translation[<?php echo $translation_row; ?>][key]" class="form-control">
                    <?php foreach ($keys as $key) { ?>
                    <?php if ($key == $translation['key']) { ?>
                    <option value="<?php echo $key; ?>" selected="selected"><?php echo $key; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><input type="text" name="translation[<?php echo $translation_row; ?>][value]" value="<?php echo $translation['value']; ?>" placeholder="<?php echo $entry_value; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#translation-row<?php echo $translation_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $translation_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4"></td>
                <td class="text-left"><button type="button" onclick="addTranslation();" data-toggle="tooltip" title="<?php echo $button_translation_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
var translation_row = <?php echo $translation_row; ?>;

function addTranslation() {
	html  = '<tr id="translation-row' + translation_row + '">';
    html += '  <td class="text-left"><select name="translation[' + translation_row + '][store_id]" class="form-control">';
    <?php foreach ($stores as $store) { ?>
    html += '    <option value="<?php echo $store['store_id']; ?>"><?php echo addslashes($store['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';	
    html += '  <td class="text-left"><select name="translation[' + translation_row + '][language_id]" class="form-control">';
    <?php foreach ($languages as $language) { ?>
    html += '    <option value="<?php echo $language['language_id']; ?>"><?php echo addslashes($language['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';	
    
	html += '  <td class="text-left"><select name="translation[' + translation_row + '][language_id]" class="form-control">';
    <?php foreach ($languages as $language) { ?>
    html += '    <option value="<?php echo $language['language_id']; ?>"><?php echo addslashes($language['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';	
		
	html += '  <td class="text-left"><input type="text" name="translation[' + translation_row + '][value]" value="" placeholder="<?php echo $entry_value; ?>" class="form-control" /></td>';	
	html += '  <td class="text-left"><button type="button" onclick="$(\'#translation-row' + translation_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#translation tbody').append(html);
	
	translation_row++;
}
//--></script> 
</div>
<?php echo $footer; ?> 