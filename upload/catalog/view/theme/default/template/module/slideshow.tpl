<div id="carousel<?php echo $module; ?>" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <?php $i = 0; ?>
    <?php if (count($banners) > 1) { ?>
    <?php foreach ($banners as $banner) { ?>
    <?php if ($i == 0) { ?>
    <?php $class = 'active'; ?>
    <?php } else { ?>
    <?php $class = ''; ?>
    <?php } ?>
    <li data-target="#carousel<?php echo $module; ?>" data-slide-to="<?php echo $i; ?>" class="<?php echo $class; ?>"></li>
    <?php $i++; ?>
    <?php } ?>
    <?php } ?>
  </ol>
  <div class="carousel-inner">
    <?php $i = 0; ?>
    <?php foreach ($banners as $banner) { ?>
    <?php if ($i == 0) { ?>
    <?php $class = 'active'; ?>
    <?php } else { ?>
    <?php $class = ''; ?>
    <?php } ?>
    <div class="item <?php echo $class; ?>">
      <?php if ($banner['link']) { ?>
      <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
      <?php } else { ?>
      <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
      <?php } ?>
      <div class="carousel-caption">
        <h3><?php echo $banner['title']; ?></h3>
      </div>
    </div>
    <?php $i++; ?>
    <?php } ?>
  </div>
  <?php if (count($banners) > 1) { ?>
  <a class="left carousel-control" href="#carousel<?php echo $module; ?>" data-slide="prev"><i class="icon-prev"></i></a> <a class="right carousel-control" href="#carousel<?php echo $module; ?>" data-slide="next"><i class="icon-next"></i></a>
  <?php } ?>
</div>
<script type="text/javascript"><!--
$('#carousel<?php echo $module; ?>').carousel({
	wrap: true
});

$('#carousel<?php echo $module; ?>').carousel(0);
--></script>