<?php if (count($languages) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div class="btn-group">
    
    
    <button class="btn btn-default navbar-btn dropdown-toggle" data-toggle="dropdown">
    
    <span>
    
    
    <?php foreach ($languages as $language) { ?>
    <?php if ($language['code'] == $language_code) { ?>
    <img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>">
    <?php } ?>
    <?php } ?>
    
    </span> 
    
    <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_language; ?></span> <i class="icon-caret-down"></i>
    
    </button>
    
    
    <ul class="dropdown-menu">
      <?php foreach ($languages as $language) { ?>
      <li><a onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>'); $(this).parent().parent().parent().parent().submit();"><img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  
  <input type="hidden" name="language_code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />  
</form>
<?php } ?>