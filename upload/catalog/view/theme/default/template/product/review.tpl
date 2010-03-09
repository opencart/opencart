<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<div class="content"><b><?php echo $review['author']; ?></b> | <img src="catalog/view/theme/default/image/stars_<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['stars']; ?>" /><br />
  <?php echo $review['date_added']; ?><br />
  <br />
  <?php echo $review['text']; ?></div>
<?php } ?>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
<div class="content"><?php echo $text_no_reviews; ?></div>
<?php } ?>
