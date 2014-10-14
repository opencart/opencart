<div id="slideshow<?php echo $module; ?>" class="flexslider">
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
$('#slideshow<?php echo $module; ?>').flexslider({
	animation: 'slide',
	animationLoop: true,
	itemWidth: <?php echo $width; ?>
});
--></script>