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
        <div class="well"> <a href="<?php echo $link_settings; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-wrench fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_settings; ?></h4>
          </a> </div>
      </div>
      <?php if ( $validation == true) { ?>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $link_subscription; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-user fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_account; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $link_item_link; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-link fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_links; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $link_bulk_linking; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-list fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_bulk_linking; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $link_bulk_listing; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-copy fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_bulk_listing; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $link_stock_updates; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-sitemap fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_stock_updates; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $link_saved_listings; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-save fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_saved_listings; ?></h4>
          </a> </div>
      </div>
      <?php } else { ?>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="https://account.openbaypro.com/amazon/apiRegister/" target="_BLANK"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-star fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_register; ?></h4>
          </a> </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>