<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <div style="float: left; width: 50%; display: inline-block;"><?php echo $category; ?></div>
  <div style="float: right; width: 50%; display: inline-block;">
    <ul>
      <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
      <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
        <ul>
          <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
          <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
          <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
          <li><a href="<?php echo $history; ?>"><?php echo $text_history; ?></a></li>
          <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
        </ul>
      </li>
      <li><a href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a></li>
      <li><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></li>
      <li><a href="<?php echo $search; ?>"><?php echo $text_search; ?></a></li>
      <li><?php echo $text_information; ?>
        <ul>
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
        </ul>
      </li>
    </ul>
  </div>
</div>
<div class="bottom">&nbsp;</div>
