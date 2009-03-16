<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <?php foreach ($languages as $language) { ?>
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo @$category_description[$language['language_id']]['name']; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <br />
          <?php if (@$error_name[$language['language_id']]) { ?>
          <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
          <?php } ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td><?php echo $entry_category; ?></td>
        <td><select name="parent_id">
            <option value="0"><?php echo $text_none; ?></option>
            <?php foreach ($categories as $category) { ?>
            <?php if ($category['category_id'] == $parent_id) { ?>
            <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_image; ?></td>
        <td><input type="file" id="upload" />
          <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" /></td>
      </tr>
      <tr>
        <td></td>
        <td><img src="<?php echo $preview; ?>" alt="" id="preview" style="border: 1px solid #EEEEEE;" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript" src="view/javascript/jquery/jquery.ocupload-1.1.2.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() { 
	$('#upload').upload({
		name:    'image',
		method:  'post',
		enctype: 'multipart/form-data',
		action:  'index.php?route=catalog/image',
		autoSubmit: false,
		onSubmit: function() {
			$('#upload').after('<img src="view/image/loading.gif" id="loading" />');
		},
		onComplete: function(data) {
			var json = eval('(' + data + ')');

			if (json.error) {
				alert(json.error);
			} else {
				$('#preview').attr('src', json.src);

				$('#image').attr('value', json.file);
			}
			$('#loading').remove();
		},
		onSelect: function() {}
	});	
});	
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
