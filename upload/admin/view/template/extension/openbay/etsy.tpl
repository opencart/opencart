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
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="row">
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_settings; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-wrench fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_settings; ?></h4>
          </a> </div>
      </div>
      <?php if ($validation == true){ ?>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_products; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-link fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_products; ?></h4>
          </a> </div>
      </div>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="<?php echo $links_listings; ?>"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-tag fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_listings; ?></h4>
          </a> </div>
      </div>
      <?php } else { ?>
      <div class="col-md-3 text-center">
        <div class="well"> <a href="https://account.openbaypro.com/etsy/apiRegister/" target="_BLANK"> <span class="fa-stack fa-3x"> <i class="fa fa-square-o fa-stack-2x"></i> <i class="fa fa-star fa-stack-1x"></i> </span>
          <h4><?php echo $text_heading_register; ?></h4>
          </a> </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>