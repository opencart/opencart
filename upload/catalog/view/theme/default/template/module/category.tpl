<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">
      <ul>
        <?php foreach ($categories as $category_1) { ?>
        <li><a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
          <?php if ($category_1['children']) { ?>
          <?php if (count($category_1['children']) <= 12) { ?>
          <div>
            <ul>
              <?php foreach ($category_1['children'] as $category_2) { ?>
              <li><a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <?php } else { ?>
          <div>
            <?php for ($i = 0; $i < count($category_1['children']);) { ?>
            <ul>
              <?php $j = $i + ceil(count($category_1['children']) / 4); ?>
              <?php for (; $i < $j; $i++) { ?>
              <?php if (isset($category_1['children'][$i])) { ?>
              <li><a href="<?php echo $category_1['children'][$i]['href']; ?>"><?php echo $category_1['children'][$i]['name']; ?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
            <?php } ?>
          </div>
          <?php } ?>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
