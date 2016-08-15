<?php echo $header; ?>

<div class="container">
  <?php echo $content_top; ?>
  <!--Welcome...-->
  <div class="welcome text-center">
    <h3><?php echo $text_welcome; ?></h3>
    <p><?php echo $text_content; ?></p>
    <ul class="list-unstyled">
      <li>
        <a class="img-circle">
          <img class="img-circle" src="catalog/view/theme/default/image/EF.png" />
          <span><?php echo $text_text_environmental_friendly; ?></span>
        </a>
      </li>
      <li>
        <a class="img-circle">
          <img class="img-circle" src="catalog/view/theme/default/image/Saving.png" />
          <span><?php echo $text_saving; ?></span>
        </a>
      </li>
      <li>
        <a class="img-circle">
          <img class="img-circle" src="catalog/view/theme/default/image/Dimmalbe.png" />
          <span><?php echo $text_dimmalbe; ?></span>
        </a>
      </li>
    </ul>
  </div>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
  <script>
    $(function () {

      $('.welcome > ul > li > a span').hide();
      $('.welcome > ul > li > a').hover(function () {
        $(this).find("img").hide();
        $(this).find("span").show();
      }, function () {
        $(this).find("img").show();
        $(this).find("span").hide();
      });
    });
  </script>
</div>
<?php echo $footer; ?>
