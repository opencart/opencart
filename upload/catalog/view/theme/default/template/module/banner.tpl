<div id="banner<?php echo $module; ?>" class="flexslider">
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
$('#banner<?php echo $module; ?>').flexslider({
	animation: 'fade',
	animationLoop: true,
	itemWidth: <?php echo $width; ?>,
	itemMargin: 5,
	controlNav: false,
	directionNav: false,
	reverse: <?php echo ($direction == 'rtl' ? 'true' : 'false'); ?>
});
--></script>