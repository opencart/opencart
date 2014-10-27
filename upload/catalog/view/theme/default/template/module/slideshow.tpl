<div id="slideshow<?php echo $module; ?>" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <?php $i = 0; ?>
    <?php foreach ($banners as $banner) { ?>
    <li data-target="#slideshow<?php echo $module; ?>" data-slide-to="<?php echo $i; ?>"></li>
    <?php $i++; ?>
    <?php } ?>
  </ol>
  <div class="carousel-inner">
    <?php foreach ($banners as $banner) { ?>
    <div class="item">
      <?php if ($banner['link']) { ?>
      <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
      <?php } else { ?>
      <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
      <?php } ?>
    </div>
    <?php } ?>
  </div>
  <a href="#slideshow<?php echo $module; ?>" class="left carousel-control" data-slide="prev"><i class="fa fa-chevron-left fa-5x"></i></a> <a href="#slideshow<?php echo $module; ?>" class="right carousel-control" role="button" data-slide="next"><i class="fa fa-chevron-right fa-5x"></i></a></div>
<script type="text/javascript"><!--
$('#slideshow<?php echo $module; ?> .carousel-indicators li:first, #slideshow<?php echo $module; ?> .item:first').addClass('active');

$('#slideshow<?php echo $module; ?>').carousel({
	interval: 2000,
	wrap: true
});
--></script>