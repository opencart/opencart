<div class="panel panel-default">
  <div class="panel-heading"><?php echo $heading_title; ?></div>
  <ul class="box-filter">
    <?php foreach ($filter_groups as $filter_group) { ?>
    <li><span id="filter-group<?php echo $filter_group['filter_group_id']; ?>"><?php echo $filter_group['name']; ?></span>
      <ul>
        <?php foreach ($filter_group['filter'] as $filter) { ?>
        <?php if (in_array($filter['filter_id'], $filter_category)) { ?>
        <li>
          <input type="checkbox" value="<?php echo $filter['filter_id']; ?>" id="filter<?php echo $filter['filter_id']; ?>" checked="checked" />
          <label for="filter<?php echo $filter['filter_id']; ?>"><?php echo $filter['name']; ?></label>
        </li>
        <?php } else { ?>
        <li>
          <input type="checkbox" value="<?php echo $filter['filter_id']; ?>" id="filter<?php echo $filter['filter_id']; ?>" />
          <label for="filter<?php echo $filter['filter_id']; ?>"><?php echo $filter['name']; ?></label>
        </li>
        <?php } ?>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>
  </ul>
  <a id="button-filter" class="button"><?php echo $button_filter; ?></a> </div>
<script type="text/javascript"><!--
$('#button-filter').bind('click', function() {
	filter = [];
	
	$('.box-filter input[type=\'checkbox\']:checked').each(function(element) {
		filter.push(this.value);
	});
	
	location = '<?php echo $action; ?>&filter=' + filter.join(',');
});
//--></script> 
