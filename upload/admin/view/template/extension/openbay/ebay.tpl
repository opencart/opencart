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
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
    <?php } ?>
    <div class="row">
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_settings; ?>" id="settings-link"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-wrench fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_settings; ?></h4>
          </a> </div>
      </div>
      <?php if ($validation == true){ ?>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_sync; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-cog fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_sync; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_subscribe; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-user fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_subscription; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_usage; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-bar-chart-o fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_usage; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_itemlink; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-link fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_links; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_itemimport; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-cloud-download fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_item_import; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_orderimport; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-download fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_order_import; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_summary; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-bar-chart-o fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_summary; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_profile; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-file-text fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_profile; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_template; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-code fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_template; ?></h4>
          </a> </div>
      </div>
      <?php }else{ ?>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="https://account.openbaypro.com/ebay/apiRegister/" target="_BLANK"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-star fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_register; ?></h4>
          </a> </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>