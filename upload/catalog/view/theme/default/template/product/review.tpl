<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<div class="review-list">
  <div class="review-title">
    <div class="author pull-left"> <strong><?php echo $review['author']; ?></strong> <?php echo $text_on; ?> <?php echo $review['date_added']; ?> </div>
    <div class="rating pull-right"> <img src="catalog/view/theme/default/image/stars-<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['reviews']; ?>" /> </div>
    <div class="clearfix"></div>
  </div>
  <p class="text"><?php echo $review['text']; ?></p>
</div>
<?php } ?>
<div class="text-right"><?php echo $pagination; ?></div>
<?php } else { ?>
<p><?php echo $text_no_reviews; ?></p>
<?php } ?>
