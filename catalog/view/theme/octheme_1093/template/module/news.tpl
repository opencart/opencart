<div class="panel panel-default">
  <div class="panel-heading"><?php echo $heading_title; ?></div>
  <div class="panel-body">
  <?php foreach ($all_news as $news) { ?>
	<div style="margin-bottom:10px; padding-bottom: 5px; border-bottom:1px solid #eee;">
	  <a href="<?php echo $news['view']; ?>"><?php echo $news['title']; ?></a><span style="float:right;"><?php echo $news['date_added']; ?></span><br />
	  <?php echo $news['description']; ?>
	</div>
  <?php } ?>
  </div>
</div>
