<div id="profile">
  <div class="pull-left">
    <?php if ($image) { ?>
    <a class="dropdown-toggle" data-toggle="dropdown"><img src="<?php echo $image; ?>" alt="<?php echo $username; ?>" title="<?php echo $username; ?>" /></a>
    <?php } else { ?>
    <a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user fa-lg"></i></a>
    <?php } ?>
  </div>
  <div><?php echo $username; ?><br />
    <small><?php echo $user_group; ?></small></div>
</div>
