<div class="panel panel-default">
  <div class="panel-heading"><?php echo $heading_title; ?></div>
  <?php foreach ($filter_groups as $filter_group) { ?>
  <div class="list-group">
    <a href="" class="list-group-item"><i class="fa fa-plus-square-o"></i> <?php echo $filter_group['name']; ?></a>
    
    <div id="filter-group<?php echo $filter_group['filter_group_id']; ?>">
      
      <?php foreach ($filter_group['filter'] as $filter) { ?>
      <div class="list-group-item">
        <?php if (in_array($filter['filter_id'], $filter_category)) { ?>
        <label class="checkbox-inline"><input type="checkbox" value="<?php echo $filter['filter_id']; ?>" checked="checked" /><?php echo $filter['name']; ?></label>
        <?php } else { ?>
        <label class="checkbox-inline"><input type="checkbox" value="<?php echo $filter['filter_id']; ?>" /> <?php echo $filter['name']; ?></label>
        <?php } ?>
      </div>
      <?php } ?>
      
    </div>
  </div>
  <?php } ?>
  <div class="panel-footer text-right">
    <button type="button" id="button-filter" class="btn btn-primary"><?php echo $button_filter; ?></button>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	filter = [];
	
	$('.box-filter input[type=\'checkbox\']:checked').each(function(element) {
		filter.push(this.value);
	});
	
	location = '<?php echo $action; ?>&filter=' + filter.join(',');
});
//--></script> 
