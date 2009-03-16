<div class="box">
  <div class="top" style="background: url('catalog/view/theme/default/image/icon_information.png') 8px 8px no-repeat; padding-left: 30px;"><?php echo $heading_title; ?></div>
  <div id="information" class="middle">
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
  <div class="bottom"></div>
</div>