<ul class="list-group">
  <?php if (substr($route, 0, 8) != 'upgrade/') { ?>
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
  <?php } else { ?>
  <?php if ($route == 'upgrade/upgrade') { ?>
  <li class="list-group-item"><b><?php echo $text_upgrade; ?></b></li>
  <?php } else { ?>
  <li class="list-group-item"><?php echo $text_upgrade; ?></li>
  <?php } ?>
  <?php if ($route == 'upgrade/upgrade/success') { ?>
  <li class="list-group-item"><b><?php echo $text_finished; ?></b></li>
  <?php } else { ?>
  <li class="list-group-item"><?php echo $text_finished; ?></li>
  <?php } ?>
  <?php } ?>
</ul>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="language">
  <ul class="list-group">
    <li class="list-group-item">
      <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><?php echo $text_language; ?> <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <?php foreach ($languages as $language) { ?>
          <li><a href="<?php echo $language; ?>"><img src="language/<?php echo $language; ?>/<?php echo $language; ?>.png" /></a></li>
          <?php } ?>
        </ul>
      </div>
    </li>
  </ul>
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
<script type="text/javascript"><!--
// Language
$('#language a').on('click', function(e) {
	e.preventDefault();

	$('#language input[name=\'code\']').val($(this).attr('href'));

	$('#language').submit();
});
--></script>
