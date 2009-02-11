<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <div>
    <p><?php echo $welcome; ?></p>
  </div>
  <div class="heading"><?php echo $text_latest; ?></div>
  <table class="list">
    <?php for ($i = 0; $i < sizeof($products); $i = $i + 4) { ?>
    <tr>
      <?php for ($j = $i; $j < ($i + 4); $j++) { ?>
      <td width="25%"><?php if (isset($products[$j])) { ?>
        <a href="<?php echo $products[$j]['href']; ?>"><img src="<?php echo $products[$j]['thumb']; ?>" title="<?php echo $products[$j]['name']; ?>" alt="<?php echo $products[$j]['name']; ?>" /></a><br />
        <a href="<?php echo $products[$j]['href']; ?>"><?php echo $products[$j]['name']; ?></a><br />
        <span style="color: #999; font-size: 11px;"><?php echo $products[$j]['model']; ?></span><br />
        <span style="color: #900; font-weight: bold;"><?php echo $products[$j]['price']; ?></span><br />
        <?php if ($products[$i]['rating']) { ?>
        <img src="catalog/view/image/stars_<?php echo $products[$j]['rating'] . '.png'; ?>" alt="<?php echo $products[$j]['stars']; ?>" />
        <?php } ?>
        <?php } ?></td>
      <?php } ?>
    </tr>
    <?php } ?>
  </table>
</div>
<div class="bottom"></div>