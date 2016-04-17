<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-2">
            <ul class="nav nav-pills nav-stacked">
              <li class="active"><a href="#tab-analytics" data-toggle="tab"><?php echo $tab_analytics; ?></a></li>
              <li><a href="#tab-captcha" data-toggle="tab"><?php echo $tab_captcha; ?></a></li>
              <li><a href="#tab-feed" data-toggle="tab"><?php echo $tab_feed; ?></a></li>
              <li><a href="#tab-fraud" data-toggle="tab"><?php echo $tab_fraud; ?></a></li>
              <li><a href="#tab-module" data-toggle="tab"><?php echo $tab_module; ?></a></li>
              <li><a href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
              <li><a href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li>
              <li><a href="#tab-theme" data-toggle="tab"><?php echo $tab_theme; ?></a></li>
              <li><a href="#tab-total" data-toggle="tab"><?php echo $tab_total; ?></a></li>
            </ul>
          </div>
          <div class="col-sm-10">
            <div class="tab-content">
              <div class="tab-pane active" id="tab-analytics"></div>
              <div class="tab-pane" id="tab-captcha"></div>
              <div class="tab-pane" id="tab-feed"></div>
              <div class="tab-pane" id="tab-fraud"></div>
              <div class="tab-pane" id="tab-module"></div>
              <div class="tab-pane" id="tab-payment"></div>
              <div class="tab-pane" id="tab-shipping"></div>
              <div class="tab-pane" id="tab-theme"></div>
              <div class="tab-pane" id="tab-total"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#tab-analytics').load('index.php?route=extension/module&token=<?php echo $token; ?>');

$('#tab-analytics').on('click', function() {
	$('#tab-analytics').load('index.php?route=extension/module&token=<?php echo $token; ?>');
});
//--></script> 
<?php echo $footer; ?> 