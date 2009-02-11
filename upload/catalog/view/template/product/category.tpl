<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($categories) { ?>
  <?php foreach ($categories as $category) { ?>
  <div style="display: inline-block; float: left; text-align: center; width: 25%; margin-bottom: 15px;"><a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['thumb']; ?>" title="<?php echo $category['name']; ?>" alt="<?php echo $category['name']; ?>" style="margin-bottom: 3px;" /></a><br />
    <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></div>
  <?php } ?>
  <?php } ?>
  <?php if ($products) { ?>
  <div class="sort">
    <div class="div1">
      <select name="sort" onchange="location=this.value">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if (($sort . '-' . $order) == $sorts['value']) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="div2"><?php echo $text_sort; ?></div>
  </div>
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
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
</div>
<div class="bottom"></div>
