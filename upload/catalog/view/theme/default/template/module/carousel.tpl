<div id="carousel<?php echo $module; ?>" class="carousel slide" data-ride="carousel">
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
      <div class="row">
        <?php foreach ($banner as $item) { ?>
        <div class="col-sm-2">
          <div class="text-center">
            <?php if ($item['link']) { ?>
            <a href="<?php echo $item['link']; ?>" class=""><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" class="img-responsive" /></a>
            <?php } else { ?>
            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" class="img-responsive" />
            <?php } ?>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
  </div>
  <a href="#carousel<?php echo $module; ?>" class="left carousel-control" data-slide="prev"><i class="fa fa-chevron-left fa-5x"></i></a> <a href="#carousel<?php echo $module; ?>" class="right carousel-control" role="button" data-slide="next"><i class="fa fa-chevron-right fa-5x"></i></a></div>
<script type="text/javascript"><!--
$('#carousel<?php echo $module; ?> .carousel-indicators li:first, #carousel<?php echo $module; ?> .item:first').addClass('active');

$('#carousel<?php echo $module; ?>').carousel({
	interval: 2000,
	wrap: true
});

$(window).resize(function() {
	console.log($(window).width());	
});
--></script>