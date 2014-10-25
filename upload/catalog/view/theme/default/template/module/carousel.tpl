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
$('#carousel<?php echo $module; ?>').flexslider({
	animation: 'slide',
	itemWidth: <?php echo $width; ?>,
	itemMargin: 100,
	minItems: 2,
    maxItems: 4
});

$(window).on('resize', function() {
	setTimeout(function() {
		$('#carousel<?php echo $module; ?>').data('flexslider').resize();
	}, 1000);
});
--></script>


<script type="text/javascript"><!--
$('#carousel<?php echo $module; ?> .carousel-indicators li:first, #carousel<?php echo $module; ?> .item:first').addClass('active');

$('#carousel<?php echo $module; ?>').carousel({
	interval: 2000,
	wrap: true
});
--></script>