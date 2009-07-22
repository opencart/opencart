<div style="text-align: left; color: #999; margin-bottom: 4px;"><?php echo $entry_language; ?>
  <?php foreach ($languages as $language) { ?>
  <?php if ($language['status']) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div style="display: inline;">
      <input type="image" src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" style="position: relative; top: 4px;" />
      <input type="hidden" name="language_code" value="<?php echo $language['code']; ?>" />
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
    </div>
  </form>
  <?php } ?>
  <?php } ?>
</div>
