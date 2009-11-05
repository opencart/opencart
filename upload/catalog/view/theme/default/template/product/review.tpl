<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><b><?php echo $review['author']; ?></b> | <img src="catalog/view/theme/default/image/stars_<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['stars']; ?>" /><br />
  <?php echo $review['date_added']; ?><br />
  <br />
  <?php echo $review['text']; ?></div>
<?php } ?>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $text_no_reviews; ?></div>
<?php } ?>
