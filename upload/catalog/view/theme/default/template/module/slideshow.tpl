<div id="slideshow<?php echo $module; ?>" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <?php $i = 0; ?>
    <?php if (count($banners) > 1) { ?>
    <?php foreach ($banners as $banner) { ?>
    <li data-target="#carousel<?php echo $module; ?>" data-slide-to="<?php echo $i; ?>"></li>
    <?php $i++; ?>
    <?php } ?>
    <?php } ?>
  </ol>
  <div class="carousel-inner">
    <?php foreach ($banners as $banner) { ?>
    <div class="item">
      <?php if ($banner['link']) { ?>
      <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
      <?php } else { ?>
      <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
      <?php } ?>
      <div class="carousel-caption">
        <p><?php echo $banner['title']; ?></p>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<script type="text/javascript"><!--
$('#slideshow<?php echo $module; ?> .carousel-indicators li:first, .carousel-inner .item:first').addClass('active');

$('#slideshow<?php echo $module; ?>').carousel({
	interval: 3000,
	wrap: true
});
--></script>