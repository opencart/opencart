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
              <li><a href="#tab-menu" data-toggle="tab"><?php echo $tab_menu; ?></a></li>
              <li><a href="#tab-total" data-toggle="tab"><?php echo $tab_total; ?></a></li>
            </ul>
          </div>
          <div class="col-sm-10">
            <div class="tab-content">
              <div class="tab-pane active" id="tab-analytics">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-left"><?php echo $column_status; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($analytics) { ?>
                      <?php foreach ($analytics as $analytic) { ?>
                      <tr>
                        <td class="text-left" colspan="2"><b><?php echo $analytic['name']; ?></b></td>
                        <td class="text-right"><?php if (!$analytic['installed']) { ?>
                          <a href="<?php echo $analytic['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $analytic['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php if ($analytic['installed']) { ?>
                      <?php foreach ($analytic['store'] as $store) { ?>
                      <tr>
                        <td class="text-left">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<?php echo $store['name']; ?></td>
                        <td class="text-left"><?php echo $store['status']; ?></td>
                        <td class="text-right"><a href="<?php echo $store['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                      </tr>
                      <?php } ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab-captcha">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-left"><?php echo $column_status; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($captchas) { ?>
                      <?php foreach ($captchas as $captcha) { ?>
                      <tr>
                        <td class="text-left"><?php echo $captcha['name']; ?></td>
                        <td class="text-left"><?php echo $captcha['status']; ?></td>
                        <td class="text-right"><?php if (!$captcha['installed']) { ?>
                          <a href="<?php echo $captcha['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $captcha['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?>
                          <?php if ($captcha['installed']) { ?>
                          <a href="<?php echo $captcha['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                          <?php } else { ?>
                          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab-feed">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-left"><?php echo $column_status; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($feeds) { ?>
                      <?php foreach ($feeds as $feed) { ?>
                      <tr>
                        <td class="text-left"><?php echo $feed['name']; ?></td>
                        <td class="text-left"><?php echo $feed['status']; ?></td>
                        <td class="text-right"><?php if (!$feed['installed']) { ?>
                          <a href="<?php echo $feed['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $feed['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?>
                          <?php if ($feed['installed']) { ?>
                          <a href="<?php echo $feed['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                          <?php } else { ?>
                          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab-fraud">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-left"><?php echo $column_status; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($frauds) { ?>
                      <?php foreach ($frauds as $fraud) { ?>
                      <tr>
                        <td class="text-left"><?php echo $fraud['name']; ?></td>
                        <td class="text-left"><?php echo $fraud['status']; ?></td>
                        <td class="text-right"><?php if (!$fraud['installed']) { ?>
                          <a href="<?php echo $fraud['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $fraud['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?>
                          <?php if ($fraud['installed']) { ?>
                          <a href="<?php echo $fraud['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                          <?php } else { ?>
                          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab-module">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($modules) { ?>
                      <?php foreach ($modules as $module) { ?>
                      <tr>
                        <td><b><?php echo $module['name']; ?></b></td>
                        <td class="text-right"><?php if (!$module['installed']) { ?>
                          <a href="<?php echo $module['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $module['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?>
                          <?php if ($module['installed']) { ?>
                          <?php if ($module['module']) { ?>
                          <a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                          <?php } else { ?>
                          <a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                          <?php } ?>
                          <?php } else { ?>
                          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
                          <?php } ?></td>
                      </tr>
                      <?php foreach ($module['module'] as $module) { ?>
                      <tr>
                        <td class="text-left">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<?php echo $module['name']; ?></td>
                        <td class="text-right"><a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $module['delete']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a> <a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                      </tr>
                      <?php } ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab-payment">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td></td>
                        <td class="text-left"><?php echo $column_status; ?></td>
                        <td class="text-right"><?php echo $column_sort_order; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($payments) { ?>
                      <?php foreach ($payments as $payment) { ?>
                      <tr>
                        <td class="text-left"><?php echo $payment['name']; ?></td>
                        <td class="text-center"><?php echo $payment['link']; ?></td>
                        <td class="text-left"><?php echo $payment['status']; ?></td>
                        <td class="text-right"><?php echo $payment['sort_order']; ?></td>
                        <td class="text-right"><?php if (!$payment['installed']) { ?>
                          <a href="<?php echo $payment['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $payment['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?>
                          <?php if ($payment['installed']) { ?>
                          <a href="<?php echo $payment['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                          <?php } else { ?>
                          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab-shipping">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-left"><?php echo $column_status; ?></td>
                        <td class="text-right"><?php echo $column_sort_order; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($shippings) { ?>
                      <?php foreach ($shippings as $shipping) { ?>
                      <tr>
                        <td class="text-left"><?php echo $shipping['name']; ?></td>
                        <td class="text-left"><?php echo $shipping['status']; ?></td>
                        <td class="text-right"><?php echo $shipping['sort_order']; ?></td>
                        <td class="text-right"><?php if (!$shipping['installed']) { ?>
                          <a href="<?php echo $shipping['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $shipping['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?>
                          <?php if ($shipping['installed']) { ?>
                          <a href="<?php echo $shipping['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                          <?php } else { ?>
                          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab-theme">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-left"><?php echo $column_status; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($themes) { ?>
                      <?php foreach ($themes as $theme) { ?>
                      <tr>
                        <td class="text-left" colspan="2"><b><?php echo $theme['name']; ?></b></td>
                        <td class="text-right"><?php if (!$theme['installed']) { ?>
                          <a href="<?php echo $theme['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $theme['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?></td>
                      </tr>
                      <?php if ($theme['installed']) { ?>
                      <?php foreach ($theme['store'] as $store) { ?>
                      <tr>
                        <td class="text-left">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<?php echo $store['name']; ?></td>
                        <td class="text-left"><?php echo $store['status']; ?></td>
                        <td class="text-right"><a href="<?php echo $store['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                      </tr>
                      <?php } ?>
                      <?php } ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab-menu">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-left"><?php echo $column_status; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($themes) { ?>
                      <?php foreach ($themes as $theme) { ?>
                      <tr>
                        <td class="text-left" colspan="2"><b><?php echo $theme['name']; ?></b></td>
                        <td class="text-right"><?php if (!$theme['installed']) { ?>
                          <a href="<?php echo $theme['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $extension['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?></td>
                      </tr>
                      <?php if ($theme['installed']) { ?>
                      <?php foreach ($theme['store'] as $store) { ?>
                      <tr>
                        <td class="text-left">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<?php echo $store['name']; ?></td>
                        <td class="text-left"><?php echo $store['status']; ?></td>
                        <td class="text-right"><a href="<?php echo $store['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                      </tr>
                      <?php } ?>
                      <?php } ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>              
              <div class="tab-pane" id="tab-total">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><?php echo $column_name; ?></td>
                        <td class="text-left"><?php echo $column_status; ?></td>
                        <td class="text-right"><?php echo $column_sort_order; ?></td>
                        <td class="text-right"><?php echo $column_action; ?></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($totals) { ?>
                      <?php foreach ($totals as $total) { ?>
                      <tr>
                        <td class="text-left"><?php echo $total['name']; ?></td>
                        <td class="text-left"><?php echo $total['status']; ?></td>
                        <td class="text-right"><?php echo $total['sort_order']; ?></td>
                        <td class="text-right"><?php if (!$total['installed']) { ?>
                          <a href="<?php echo $total['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                          <?php } else { ?>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $total['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                          <?php } ?>
                          <?php if ($total['installed']) { ?>
                          <a href="<?php echo $total['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                          <?php } else { ?>
                          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
localStorage.setItem('extension-tab', 'list');

$('#tab-analytics').load('index.php?route=extension/extension&token=<?php echo $token; ?>');

$('#tab-analytics').on('click', function() {
	$('#tab-analytics').load('index.php?route=extension/extension&token=<?php echo $token; ?>');
});

//$('#tab-analytics').trigger();
//--></script> 
<?php echo $footer; ?> 