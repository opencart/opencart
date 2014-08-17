<div id="profile">
  <div>
    <?php if ($image) { ?>
    <a class="dropdown-toggle" data-toggle="dropdown"><img src="<?php echo $image; ?>" alt="<?php echo $firstname; ?> <?php echo $lastname; ?>" title="<?php echo $username; ?>" /></a>
    <?php } else { ?>
    <a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user fa-2x"></i></a>
    <?php } ?>
  </div>
  <div>
    <h4><?php echo $firstname; ?> <?php echo $lastname; ?></h4>
    <small><?php echo $user_group; ?></small></div>
</div>
