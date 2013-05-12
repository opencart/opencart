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
        <div class="buttons">
          <button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
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
                <div class="control-group">
                  <label class="control-label" for="input-title<?php echo $language['language_id']; ?>"><span class="required">*</span> <?php echo $entry_title; ?></label>
                  <div class="controls">
                    <input type="text" name="information_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="input-xxlarge" />
                    <?php if (isset($error_title[$language['language_id']])) { ?>
                    <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
                    <?php } ?>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="input-description"><span class="required">*</span> <?php echo $entry_description; ?></label>
                  <div class="controls">
                    <textarea name="information_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['description'] : ''; ?></textarea>
                    <?php if (isset($error_description[$language['language_id']])) { ?>
                    <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="tab-pane" id="tab-data">
            <div class="control-group">
              <div class="control-label"><?php echo $entry_store; ?></div>
              <div class="controls">
                <label class="checkbox">
                  <?php if (in_array(0, $information_store)) { ?>
                  <input type="checkbox" name="information_store[]" value="0" checked="checked" />
                  <?php echo $text_default; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="information_store[]" value="0" />
                  <?php echo $text_default; ?>
                  <?php } ?>
                </label>
                <?php foreach ($stores as $store) { ?>
                <label class="checkbox">
                  <?php if (in_array($store['store_id'], $information_store)) { ?>
                  <input type="checkbox" name="information_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                  <?php echo $store['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="information_store[]" value="<?php echo $store['store_id']; ?>" />
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
                <a data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><i class="icon-info-sign"></i></a> </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-bottom"><?php echo $entry_bottom; ?></label>
              <div class="controls">
                <label class="checkbox inline">
                  <?php if ($bottom) { ?>
                  <input type="checkbox" name="bottom" value="1" checked="checked" id="input-bottom" />
                  <?php } else { ?>
                  <input type="checkbox" name="bottom" value="1" id="input-bottom" />
                  <?php } ?>
                </label>
                <a data-toggle="tooltip" title="<?php echo $help_bottom; ?>"><i class="icon-info-sign"></i></a> </div>
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
            <div class="control-group">
              <label class="control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
              <div class="controls">
                <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
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
                  <td class="left"><select name="information_layout[0][layout_id]">
                      <option value=""></option>
                      <?php foreach ($layouts as $layout) { ?>
                      <?php if (isset($information_layout[0]) && $information_layout[0] == $layout['layout_id']) { ?>
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
                  <td class="left"><select name="information_layout[<?php echo $store['store_id']; ?>][layout_id]">
                      <option value=""></option>
                      <?php foreach ($layouts as $layout) { ?>
                      <?php if (isset($information_layout[$store['store_id']]) && $information_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
      </form>
    </div>
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
$('#language a:first').tab('show');
//--></script> 
<?php echo $footer; ?>