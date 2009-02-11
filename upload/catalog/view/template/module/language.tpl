<div style="text-align: left; color: #999; margin-bottom: 4px;"><?php echo $entry_language; ?>
  <?php foreach ($languages as $language) { ?>
  <form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data">
    <input type="image" src="catalog/view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" style="position: relative; top: 4px;" />
    <input type="hidden" name="language" value="<?php echo $language['code']; ?>" />
  </form>
  <?php } ?>
</div>
