<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="language">
  <ul class="list-group">
    <li class="list-group-item">
      <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Language <span class="caret"></span></button>
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

	$('#language input[name=\'code\']').attr('value', $(this).attr('href'));

	$('#language').submit();
});
--></script>