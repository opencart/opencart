<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <?php foreach ($filter_groups as $filter_group) { ?>
    <div class="box-filter-group"><?php echo $filter_group['name']; ?></div> 
    <?php foreach ($filter_group['filter'] as $filter) { ?>
	<?php if (in_array($filter['filter_id'], $filter_category)) { ?>
    <div class="box-filter"><input type="checkbox" name="filter[]" value="<?php echo $filter['filter_id']; ?>" id="filter<?php echo $filter['filter_id']; ?>" checked="checked" /> <label for="filter<?php echo $filter['filter_id']; ?>"><?php echo $filter['name']; ?></label></div>
    <?php } else { ?>
    <div class="box-filter"><input type="checkbox" name="filter[]" value="<?php echo $filter['filter_id']; ?>" id="filter<?php echo $filter['filter_id']; ?>" /> <label for="filter<?php echo $filter['filter_id']; ?>"><?php echo $filter['name']; ?></label></div>
	<?php } ?>
	<?php } ?>
    <?php } ?>
  </div>
</div>
<script type="text/javascript"><!--

//--></script>
