<div>
  <?php if ($keyword) { ?>
  <input type="text" name="keyword" value="<?php echo $keyword; ?>" id="filter_keyword" />
  <?php } else { ?>
  <input type="text" name="keyword" value="<?php echo $text_keywords; ?>" id="filter_keyword" onclick="this.value = ''" />
  <?php } ?>
  <a onclick="location = 'index.php?route=product/search&keyword=' + encodeURIComponent($('#filter_keyword').attr('value'));" class="button"><span><?php echo $button_search; ?></span></a>
</div>
