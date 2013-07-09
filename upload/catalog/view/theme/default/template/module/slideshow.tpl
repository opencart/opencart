<div id="slideshow<?php echo $module; ?>" class="flexslider">
    <ul class="slides">
        <?php foreach ($banners as $banner) { ?>
        <?php if ($banner['link']) { ?>
        <li>
            <a href="<?php echo $banner['link']; ?>">
                <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
            </a>
            <div class="caption transition"><a href="<?php echo $banner['link']; ?>"><?php echo $banner['title']; ?></a></div>
        </li>
        <?php } else { ?>
        <li>
            <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
            <div class="caption transition"><?php echo $banner['title']; ?></div>
        </li>
        <?php } ?>
        <?php } ?>
    </ul>
</div>

<script type="text/javascript"><!--
$(window).load(function() {
    $('#slideshow<?php echo $module; ?>').flexslider({
        animation: 'slide'
    });
});
--></script>