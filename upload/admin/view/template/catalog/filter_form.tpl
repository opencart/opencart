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
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-edit icon-large"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <button type="submit" form="form-filter" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-filter" class="form-horizontal">
      <div class="control-group required">
        <label class="control-label"><?php echo $entry_group; ?></label>
        <div class="controls">
          <?php foreach ($languages as $language) { ?>
          <input type="text" name="filter_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter_group_description[$language['language_id']]) ? $filter_group_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_group; ?>" />
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
          <?php if (isset($error_name[$language['language_id']])) { ?>
          <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
          <?php } ?>
          <?php } ?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="controls">
          <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
        </div>
      </div>
      <table id="filter" class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <td class="left required"><?php echo $entry_name ?></td>
            <td class="text-right"><?php echo $entry_sort_order; ?></td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php $filter_row = 0; ?>
          <?php foreach ($filters as $filter) { ?>
          <tr id="filter-row<?php echo $filter_row; ?>">
            <td class="text-left"><input type="hidden" name="filter[<?php echo $filter_row; ?>][filter_id]" value="<?php echo $filter['filter_id']; ?>" />
              <?php foreach ($languages as $language) { ?>
              <input type="text" name="filter[<?php echo $filter_row; ?>][filter_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter['filter_description'][$language['language_id']]) ? $filter['filter_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_filter[$filter_row][$language['language_id']])) { ?>
              <span class="error"><?php echo $error_filter[$filter_row][$language['language_id']]; ?></span>
              <?php } ?>
              <?php } ?></td>
            <td class="text-right"><input type="text" name="filter[<?php echo $filter_row; ?>][sort_order]" value="<?php echo $filter['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" /></td>
            <td class="text-left"><a onclick="$('#filter-row<?php echo $filter_row; ?>').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>
          </tr>
          <?php $filter_row++; ?>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2"></td>
            <td class="text-left"><a onclick="addFilterRow();" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_filter; ?></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var filter_row = <?php echo $filter_row; ?>;

function addFilterRow() {
	html  = '<tr id="filter-row' + filter_row + '">';	
    html += '  <td class="text-left"><input type="hidden" name="filter[' + filter_row + '][filter_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '  <input type="text" name="filter[' + filter_row + '][filter_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_name ?>" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '  </td>';
	html += '  <td class="text-right"><input type="text" name="filter[' + filter_row + '][sort_order]" value="" value="" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" /></td>';
	html += '  <td class="text-left"><a onclick="$(\'#filter-row' + filter_row + '\').remove();" class="btn"><i class="icon-minus-sign"></i> <?php echo $button_remove; ?></a></td>';
	html += '</tr>';	
	
	$('#filter tbody').append(html);
	
	filter_row++;
}
//--></script> 
<?php echo $footer; ?> 