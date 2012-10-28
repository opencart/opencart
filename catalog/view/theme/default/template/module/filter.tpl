<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-filter">
      <ul>
        <?php foreach ($filters as $filter) { ?>
        <li>

          <a href="<?php echo $filter['href']; ?>"><?php echo $filter['name']; ?></a>

          <?php if ($filter['filter_value']) { ?>
          <ul>
            <?php foreach ($filter['filter_value'] as $filter_value) { ?>
            <li>
              <?php if ($filter_value['filter_id'] == $$filter_value_id) { ?>
              <a href="<?php echo $filter_value['href']; ?>" class="active"><?php echo $$filter_value['name']; ?></a>
              <?php } else { ?>
              <a href="<?php echo $filter_value['href']; ?>"><?php echo $$filter_value['name']; ?></a>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
