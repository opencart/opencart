<div class="responsive-slider-parallax" data-spy="responsive-slider" data-autoplay="true">
      <div class="slides-container" data-group="slides">
        <ul>
        <?php foreach ($banners as $banner) { ?>
            <li>

                <div class="slide-body" data-group="slide">
                    <?php if ($banner['link']) { ?>
                        <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="" class="img-responsive" /></a>
                    <div class="caption header" data-animate="slideAppearRightToLeft" data-delay="500" data-length="300">
                            <h2><a href="<?php echo $banner['link']; ?>"><?php echo $banner['title']; ?></a></h2>
                                <div class="caption sub" data-animate="slideAppearLeftToRight" data-delay="800" data-length="300"><a href="<?php echo $banner['link']; ?>"><?php echo $text_shop; ?></a></div>
                        </div>
                    <?php } else { ?>
                        <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" />
                    <div class="caption header" data-animate="slideAppearRightToLeft" data-delay="500" data-length="300">
                            <h2><?php echo $banner['title']; ?></h2>
                        </div>
                    <?php } ?>

                </div>

  	    	</li><?php } ?>
  	    	</ul>
        </div>
        <a class="slider-control left" href="#" data-jump="prev"><i class="fa fa-chevron-left fa-5x"></i></a>
        <a class="slider-control right" href="#" data-jump="next"><i class="fa fa-chevron-right fa-5x"></i></a>
    </div>

<script src="catalog/view/javascript/parallax/js/responsive-slider.js"></script>