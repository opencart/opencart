<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $text_dashboard; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="mi mi-check-circle">check_circle</i> <?php echo $success; ?></div>
    <?php } ?>
    <div class="row">
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_settings; ?>" id="settings-link"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-wrench mi-stack-1x">build</i> </span>
          <h4><?php echo $text_heading_settings; ?></h4>
          </a> </div>
      </div>
      <?php if ($validation == true){ ?>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_sync; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-cog mi-stack-1x">settings</i> </span>
          <h4><?php echo $text_heading_sync; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_subscribe; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-user mi-stack-1x">person</i> </span>
          <h4><?php echo $text_heading_subscription; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_usage; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-bar-chart-o mi-stack-1x">insert_chart</i> </span>
          <h4><?php echo $text_heading_usage; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_itemlink; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-link mi-stack-1x">link</i> </span>
          <h4><?php echo $text_heading_links; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_itemimport; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-cloud-download mi-stack-1x">cloud_download</i> </span>
          <h4><?php echo $text_heading_item_import; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_orderimport; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-download mi-stack-1x">file_download</i> </span>
          <h4><?php echo $text_heading_order_import; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_summary; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-bar-chart-o mi-stack-1x">insert_chart</i> </span>
          <h4><?php echo $text_heading_summary; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_profile; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-file-text mi-stack-1x">description</i> </span>
          <h4><?php echo $text_heading_profile; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_template; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-code mi-stack-1x">code</i> </span>
          <h4><?php echo $text_heading_template; ?></h4>
          </a> </div>
      </div>
      <?php } else { ?>
      <div class="col-md-3 text-center">
        <div class="well"><a href="https://account.openbaypro.com/ebay/apiRegister/" target="_BLANK"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-star mi-stack-1x">star</i> </span>
          <h4><?php echo $text_heading_register; ?></h4>
          </a>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>
