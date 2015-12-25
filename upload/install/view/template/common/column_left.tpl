<ul class="list-group">
  <?php if ($route == 'install/step_1') { ?>
  <li class="list-group-item"><b><?php echo $text_license; ?></b></li>
  <?php } else { ?>
  <li class="list-group-item"><?php echo $text_license; ?></li>
  <?php } ?>
  <?php if ($route == 'install/step_2') { ?>
  <li class="list-group-item"><b><?php echo $text_installation; ?></b></li>
  <?php } else { ?>
  <li class="list-group-item"><?php echo $text_installation; ?></li>
  <?php } ?>
  <?php if ($route == 'install/step_3') { ?>
  <li class="list-group-item"><b><?php echo $text_configuration; ?></b></li>
  <?php } else { ?>
  <li class="list-group-item"><?php echo $text_configuration; ?></li>
  <?php } ?>
  <li class="list-group-item"><?php echo $text_finished; ?></li>
</ul>
