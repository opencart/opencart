<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="filter_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter_description[$language['language_id']]) ? $filter_description[$language['language_id']]['name'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
          </tr>
        </table>
        <table id="filter-value" class="list">
          <thead>
            <tr>
              <td class="left"><span class="required">*</span> <?php echo $entry_value; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $filter_value_row = 0; ?>
          <?php foreach ($filter_values as $filter_value) { ?>
          <tbody id="filter-value-row<?php echo $filter_value_row; ?>">
            <tr>
              <td class="left"><input type="hidden" name="filter_value[<?php echo $filter_value_row; ?>][filter_value_id]" value="<?php echo $filter_value['filter_value_id']; ?>" />
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="filter_value[<?php echo $filter_value_row; ?>][filter_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter_value['filter_value_description'][$language['language_id']]) ? $filter_value['filter_value_description'][$language['language_id']]['name'] : ''; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php if (isset($error_filter_value[$filter_value_row][$language['language_id']])) { ?>
                <span class="error"><?php echo $error_filter_value[$filter_value_row][$language['language_id']]; ?></span>
                <?php } ?>
                <?php } ?></td>
              <td class="right"><input type="text" name="filter_value[<?php echo $filter_value_row; ?>][sort_order]" value="<?php echo $filter_value['sort_order']; ?>" size="1" /></td>
              <td class="left"><a onclick="$('#filter-value-row<?php echo $filter_value_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $filter_value_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left"><a onclick="addFilterValue();" class="button"><?php echo $button_add_filter_value; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var filter_value_row = <?php echo $filter_value_row; ?>;

function addFilterValue() {
	html  = '<tbody id="filter-value-row' + filter_value_row + '">';
	html += '<tr>';	
    html += '<td class="left"><input type="hidden" name="filter_value[' + filter_value_row + '][filter_value_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="filter_value[' + filter_value_row + '][filter_value_description][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '</td>';
	html += '<td class="right"><input type="text" name="filter_value[' + filter_value_row + '][sort_order]" value="" size="1" /></td>';
	html += '<td class="left"><a onclick="$(\'#filter-value-row' + filter_value_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '</tr>';	
    html += '</tbody>';
	
	$('#filter-value tfoot').before(html);
	
	filter_value_row++;
}
//--></script> 
<?php echo $footer; ?>