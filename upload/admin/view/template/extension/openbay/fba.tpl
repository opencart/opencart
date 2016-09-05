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
        <div class="well"> <a href="<?php echo $link_settings; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-wrench mi-stack-1x">build</i> </span>
          <h4><?php echo $text_heading_settings; ?></h4>
          </a> </div>
      </div>
      <?php if ($validation == true) { ?>
        <div class="col-md-3 text-center">
          <div class="well"> <a href="<?php echo $link_account; ?>" target="_blank"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-user mi-stack-1x">person</i> </span>
            <h4><?php echo $text_heading_account; ?></h4>
          </a> </div>
        </div>
        <div class="col-md-3 text-center">
          <div class="well"> <a href="<?php echo $link_fulfillments; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-truck mi-stack-1x">local_shipping</i> </span>
            <h4><?php echo $text_heading_fulfillments; ?></h4>
          </a> </div>
        </div>
        <div class="col-md-3 text-center">
          <div class="well"> <a href="<?php echo $link_orders; ?>"> <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-truck mi-stack-1x">local_shipping</i> </span>
            <h4><?php echo $text_heading_orders; ?></h4>
          </a> </div>
        </div>
      <?php } else { ?>
      <div class="col-md-3 text-center">
        <div class="well">
          <a href="https://account.openbaypro.com/fba/apiregister/" target="_BLANK">
            <span class="mi-stack mi-3x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-star mi-stack-1x">star</i> </span>
            <h4><?php echo $text_heading_register; ?></h4>
          </a>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>
