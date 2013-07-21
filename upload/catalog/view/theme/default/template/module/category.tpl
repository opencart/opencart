<div class="sidebar">
  <ul class="nav nav-tabs nav-stacked">
    <?php foreach ($categories as $category) { ?>
    <?php if ($category['category_id'] == $category_id) { ?>
    <li class="active"><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
      <?php } else { ?>
    <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
      <?php } ?>
      <?php if ($category['children']) { ?>
      <ul class="nav nav-list">
        <?php foreach ($category['children'] as $child) { ?>
        <li>
          <?php if ($child['category_id'] == $child_id) { ?>
          <a href="<?php echo $child['href']; ?>" class="active"><?php echo $child['name']; ?></a>
          <?php } else { ?>
          <a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
</div>
