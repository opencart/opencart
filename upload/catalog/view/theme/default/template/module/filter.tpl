<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <ul class="box-filter">
      <?php foreach ($filter_groups as $filter_group) { ?>
      <li><span id="filter-group<?php echo $filter_group['filter_group_id']; ?>"><?php echo $filter_group['name']; ?></span>
        <ul>
          <?php foreach ($filter_group['filter'] as $filter) { ?>
          <?php if (in_array($filter['filter_id'], $filter_category)) { ?>
          <li>
            <input type="checkbox" name="filter[]" value="<?php echo $filter['filter_id']; ?>" id="filter<?php echo $filter['filter_id']; ?>" checked="checked" />
            <label for="filter<?php echo $filter['filter_id']; ?>"><?php echo $filter['name']; ?></label>
          </li>
          <?php } else { ?>
          <li>
            <input type="checkbox" name="filter[]" value="<?php echo $filter['filter_id']; ?>" id="filter<?php echo $filter['filter_id']; ?>" />
            <label for="filter<?php echo $filter['filter_id']; ?>"><?php echo $filter['name']; ?></label>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
    <a id="button-filter" class="button"><?php echo $button_filter; ?></a>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').bind('click', function() {
	$.ajax({
		url: 'index.php?route=module/filter/filter',
		type: 'post',
		dataType: 'html',
		data: $('.box-filter input[type=\'checkbox\']:checked'),
		beforeSend: function() {
			//$('.success, .warning').remove();
			//$('#button-review').attr('disabled', true);
			//$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			//$('#button-review').attr('disabled', false);
			//$('.attention').remove();
		},
		success: function(html) {
			alert(html);
		}
	});
});
//--></script> 
