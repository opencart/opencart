<div id="carousel<?php echo $module; ?>" class="flexslider carousel">
    <ul class="slides">
        <?php foreach ($banners as $banner) { ?>
        <li>
            <a href="<?php echo $banner['link']; ?>">
                <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" />
            </a>
        </li>
        <?php } ?>
    </ul>
</div>

<script type="text/javascript"><!--
    $(window).load(function() {
        $('#carousel<?php echo $module; ?>').flexslider({
            animation: 'slide',
            itemWidth: 130,
            itemMargin: 0,
            minItems: 0,
            maxItems: 0,
            move: 0
        });
    });
--></script>