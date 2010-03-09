<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle">
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <p><b><?php echo $text_my_account; ?></b></p>
    <ul>
      <li><a href="<?php echo str_replace('&', '&amp;', $information); ?>"><?php echo $text_information; ?></a></li>
      <li><a href="<?php echo str_replace('&', '&amp;', $password); ?>"><?php echo $text_password; ?></a></li>
      <li><a href="<?php echo str_replace('&', '&amp;', $address); ?>"><?php echo $text_address; ?></a></li>
    </ul>
    <p><b><?php echo $text_my_orders; ?></b></p>
    <ul>
      <li><a href="<?php echo str_replace('&', '&amp;', $history); ?>"><?php echo $text_history; ?></a></li>
      <li><a href="<?php echo str_replace('&', '&amp;', $download); ?>"><?php echo $text_download; ?></a></li>
    </ul>
    <p><b><?php echo $text_my_newsletter; ?></b></p>
    <ul>
      <li><a href="<?php echo str_replace('&', '&amp;', $newsletter); ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 