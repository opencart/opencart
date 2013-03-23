<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_group; ?></label>
          <div class="controls">
            <?php foreach ($languages as $language) { ?>
            <input type="text" name="filter_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter_group_description[$language['language_id']]) ? $filter_group_description[$language['language_id']]['name'] : ''; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
            <?php if (isset($error_name[$language['language_id']])) { ?>
            <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
            <?php } ?>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_sort_order; ?></label>
          <div class="controls">
            <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" />
          </div>
        </div>
        <table id="filter" class="table">
          <thead>
            <tr>
              <td class="left"><span class="required">*</span> <?php echo $entry_name ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php $filter_row = 0; ?>
            <?php foreach ($filters as $filter) { ?>
            <tr id="filter-row<?php echo $filter_row; ?>">
              <td class="left"><input type="hidden" name="filter[<?php echo $filter_row; ?>][filter_id]" value="<?php echo $filter['filter_id']; ?>" />
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="filter[<?php echo $filter_row; ?>][filter_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter['filter_description'][$language['language_id']]) ? $filter['filter_description'][$language['language_id']]['name'] : ''; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php if (isset($error_filter[$filter_row][$language['language_id']])) { ?>
                <span class="error"><?php echo $error_filter[$filter_row][$language['language_id']]; ?></span>
                <?php } ?>
                <?php } ?></td>
              <td class="right"><input type="text" name="filter[<?php echo $filter_row; ?>][sort_order]" value="<?php echo $filter['sort_order']; ?>" size="1" /></td>
              <td class="left"><a onclick="$('#filter-row<?php echo $filter_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
            </tr>
            <?php $filter_row++; ?>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left"><a onclick="addFilterRow();" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_filter; ?></a></td>
            </tr>
          </tfoot>
        </table>
        <div class="buttons"><a onclick="$('#form').submit();" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></a> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var filter_row = <?php echo $filter_row; ?>;

function addFilterRow() {
	html  = '<tr id="filter-row' + filter_row + '">';	
    html += '  <td class="left"><input type="hidden" name="filter[' + filter_row + '][filter_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '  <input type="text" name="filter[' + filter_row + '][filter_description][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '  </td>';
	html += '  <td class="right"><input type="text" name="filter[' + filter_row + '][sort_order]" value="" size="1" /></td>';
	html += '  <td class="left"><a onclick="$(\'#filter-row' + filter_row + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
	html += '</tr>';	
	
	$('#filter tbody').append(html);
	
	filter_row++;
}
//--></script> 
<?php echo $footer; ?> 