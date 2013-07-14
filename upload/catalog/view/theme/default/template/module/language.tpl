<?php if (count($languages) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div class="btn-group" id="language"> <a href="#" class="btn btn-link dropdown-toggle language-toggle" id="language-drop" role="button" data-toggle="dropdown"><span id="language-choice">
    <?php foreach ($languages as $language) { ?>
    <?php if ($language['code'] == $language_code) { ?>
    <img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>">
    <?php }} ?>
    </span> <span class="hidden-phone hidden-tablet"><?php echo $text_language; ?></span> <i class="icon-caret-down"></i></a>
    <ul class="dropdown-menu animated fadeIn" role="menu" aria-labelledby="language-drop" id="language-menu">
      <?php foreach ($languages as $language) { ?>
      <li role="presentation"><a href="#" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>'); $(this).parent().parent().parent().parent().submit();" role="menuitem" tabindex="-1" href="#"><img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
      <?php } ?>
      <input type="hidden" name="language_code" value="" />
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
    </ul>
  </div>
</form>
<?php } ?>