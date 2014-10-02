<div id="carousel<?php echo $module; ?>" class="flexslider carousel">
  <ul class="slides">
    <?php foreach ($banners as $banner) { ?>
    <?php if ($banner['link']) { ?>
    <li><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a></li>
    <?php } else { ?>
    <li><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></li>
    <?php } ?>
    <?php } ?>
  </ul>
</div>
<script type="text/javascript"><!--
$(window).load(function() {
$('#carousel<?php echo $module; ?>').flexslider({
	animation: 'slide',
	itemWidth: 130,
	itemMargin: 100,
	minItems: 2,
    maxItems: 4
});});
--></script>