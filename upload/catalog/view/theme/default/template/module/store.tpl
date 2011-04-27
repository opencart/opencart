<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content" style="text-align: center;">
    <p><?php echo $text_store; ?></p>
    <select name="store" onchange="location = this.value">
      <?php foreach ($stores as $store) { ?>
      <?php if ($store['store_id'] == $store_id) { ?>
      <option value="<?php echo $store['url']; ?>" selected="selected"><?php echo $store['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $store['url']; ?>"><?php echo $store['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
  </div>
</div>
