<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <?php foreach ($filter_groups as $filter_group) { ?>
    <?php echo $filter_group['name']; ?>  
    <?php foreach ($filter_group['filter'] as $filter) { ?>
    <?php echo $filter['name']; ?>
    <?php } ?>
    <?php } ?>
  </div>
</div>
