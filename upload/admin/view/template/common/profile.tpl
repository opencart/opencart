<div id="profile">
  <div>
    <?php if ($image) { ?>
    <img src="<?php echo $image; ?>" alt="<?php echo $firstname; ?> <?php echo $lastname; ?>" title="<?php echo $username; ?>" class="img-circle" />
    <?php } else { ?>
    <i class="fa fa-opencart"></i>
    <?php } ?>
  </div>
  <div>
    <h4><?php echo $firstname; ?> <?php echo $lastname; ?></h4>
    <small><?php echo $user_group; ?></small>
  </div>
</div>
