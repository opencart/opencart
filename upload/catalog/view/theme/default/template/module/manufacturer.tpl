<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content" id="manufacturer<?php echo $module; ?>">
    <ul class="jcarousel-skin-tango">
      <?php foreach ($manufacturers as $manufacturer) { ?>
      <li><a href="<?php echo $manufacturer['href']; ?>"><img src="<?php echo $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>" title="<?php echo $manufacturer['name']; ?>" /></a></li>
      <?php } ?>
    </ul>
  </div>
</div>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.jcarousel.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/carousel.css" />
<link rel="stylesheet" type="text/css" href="index.php?route=module/manufacturer/css&module=<?php echo $module; ?>" />
<script type="text/javascript"><!--
$('#manufacturer<?php echo $module; ?> ul').jcarousel({
	vertical: <?php echo ($axis == 'horizontal' ? 'false' : 'true'); ?>,
	visible: <?php echo $limit; ?>,
	scroll: <?php echo $scroll; ?>
});
//--></script>