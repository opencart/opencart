<div class="box">
  <div class="top"><img src="catalog/view/theme/default/image/brands.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle" style="text-align: center;">
    <select onchange="location = this.value">
      <option value=""><?php echo $text_select; ?></option>
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
      <option value="<?php echo str_replace('&', '&amp;', $manufacturer['href']); ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo str_replace('&', '&amp;', $manufacturer['href']); ?>"><?php echo $manufacturer['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
