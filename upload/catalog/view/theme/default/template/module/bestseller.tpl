<div class="box">
  <div class="top" style="background: url('catalog/view/theme/default/image/icon_bestsellers.png') 8px 8px no-repeat; padding-left: 30px;"><?php echo $heading_title; ?></div>
  <div class="middle">
    <table cellpadding="2" cellspacing="0" style="width: 100%;">
      <?php foreach ($products as $product) { ?>
      <tr>
        <td valign="top" width="1"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" /></a></td>
        <td valign="top"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br />
          <?php if (!$product['special']) { ?>
          <span style="font-size: 11px; color: #900;"><?php echo $product['price']; ?></span>
          <?php } else { ?>
          <span style="font-size: 11px; color: #900; text-decoration: line-through;"><?php echo $product['price']; ?></span> <span style="font-size: 11px; color: #F00;"><?php echo $product['special']; ?></span>
          <?php } ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div class="bottom"></div>
</div>
