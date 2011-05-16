<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">
      <ul>
        <?php foreach ($categories as $category_1) { ?>
        <li>
          <?php if ($category_1['children']) { ?>
          <img src="catalog/view/theme/default/image/arrow-right.png" alt="" />
          <?php } ?>
          &nbsp;&nbsp;&nbsp;<a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
          <?php if ($category_1['children']) { ?>
          <ul>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <li><a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a></li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.box-category ul img').bind('click', function() {
	if ($(this).css('display') == 'block') {
		$(this).parent().find('img').attr('src', 'catalog/view/theme/default/image/arrow-down.png');
	} else {
		$(this).parent().find('img').attr('src', 'catalog/view/theme/default/image/arrow-right.png');
	}
		
	$(this).parent().find('ul').slideToggle('fast', function() {

	});
});
/*
$('.box-category .cart-heading').bind('click', function() {
	if ($(this).hasClass('active')) {
		$(this).removeClass('active');
	} else {
		$(this).addClass('active');
	}
		
	$(this).parent().find('.cart-content').slideToggle('slow');
});
*/
//--></script>