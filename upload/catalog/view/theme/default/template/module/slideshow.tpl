<div id="carousel<?php echo $module; ?>" class="carousel slide">
  <ol class="carousel-indicators">
    <?php $i = 0; ?>
    <?php foreach ($banners as $banner) { ?>
    <li data-target="#carousel<?php echo $module; ?>" data-slide-to="<?php echo $i; ?>"></li>
    <?php $i++; ?>
    <?php } ?>
  </ol>
  <div class="carousel-inner">
    <?php foreach ($banners as $banner) { ?>
    <div class="item">
      <?php if ($banner['link']) { ?>
      <a href="<?php echo $banner['link']; ?>"> <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /> </a>
      <?php } else { ?>
      <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
      <?php } ?>
      <div class="carousel-caption">
        <h4><?php echo $banner['title']; ?></h4>
      </div>
    </div>
    <?php } ?>
  </div>
  <a class="carousel-control left" href="#carousel<?php echo $module; ?>" data-slide="prev">&lsaquo;</a> <a class="carousel-control right" href="#carousel<?php echo $module; ?>" data-slide="next">&rsaquo;</a></div>
<script type="text/javascript"><!--
$('#carousel<?php echo $module; ?>').carousel({'number': 0});
--></script>