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
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-available" data-toggle="tab"><?php echo $tab_available; ?></a></li>
          <li><a href="#tab-downloaded" data-toggle="tab"><?php echo $tab_downloaded; ?></a></li>
          <li><a href="#tab-installer" data-toggle="tab"><?php echo $tab_installer; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-available"></div>
          <div class="tab-pane" id="tab-downloaded">
            <div class="panel-group" id="accordion">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-analytics" data-toggle="collapse" data-parent="#accordion"><?php echo $text_analytics; ?> (<?php echo $analytics_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-analytics" class="panel-collapse collapse">
                  <div class="panel-body">
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-captcha" data-toggle="collapse" data-parent="#accordion"><?php echo $text_captcha; ?> (<?php echo $captcha_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-captcha" class="panel-collapse collapse">
                  <div class="panel-body">
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-feed" data-toggle="collapse" data-parent="#accordion"><?php echo $text_feed; ?> (<?php echo $feed_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-feed" class="panel-collapse collapse">
                  <div class="panel-body">
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-fraud" data-toggle="collapse" data-parent="#accordion"><?php echo $text_fraud; ?> (<?php echo $fraud_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-fraud" class="panel-collapse collapse">
                  <div class="panel-body">
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-module" data-toggle="collapse" data-parent="#accordion"><?php echo $text_module; ?> (<?php echo $module_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-module" class="panel-collapse collapse">
                  <div class="panel-body">
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-menu" data-toggle="collapse" data-parent="#accordion"><?php echo $text_menu; ?> (<?php echo $menu_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-menu" class="panel-collapse collapse">
                  <div class="panel-body">
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
                          <?php if ($menus) { ?>
                          <?php foreach ($menus as $menu) { ?>
                          <tr>
                            <td class="text-left" colspan="2"><b><?php echo $menu['name']; ?></b></td>
                            <td class="text-right"><?php if (!$menu['installed']) { ?>
                              <a href="<?php echo $menu['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                              <?php } else { ?>
                              <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $menu['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                              <?php } ?></td>
                          </tr>
                          <?php if ($menu['installed']) { ?>
                          <?php foreach ($menu['store'] as $store) { ?>
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-payment" data-toggle="collapse" data-parent="#accordion"><?php echo $text_payment; ?> (<?php echo $payment_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-payment" class="panel-collapse collapse">
                  <div class="panel-body">
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-shipping" data-toggle="collapse" data-parent="#accordion"><?php echo $text_shipping; ?> (<?php echo $shipping_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-shipping" class="panel-collapse collapse">
                  <div class="panel-body">
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-theme" data-toggle="collapse" data-parent="#accordion"><?php echo $text_theme; ?> (<?php echo $theme_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-theme" class="panel-collapse collapse">
                  <div class="panel-body">
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><a href="#collapse-total" data-toggle="collapse" data-parent="#accordion"><?php echo $text_total; ?> (<?php echo $total_total; ?>) <i class="fa fa-caret-down fa-pull-right"></i></a></h4>
                </div>
                <div id="collapse-total" class="panel-collapse collapse">
                  <div class="panel-body">
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
          <div class="tab-pane" id="tab-installer">
            <form class="form-horizontal">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="button-upload"><span data-toggle="tooltip" title="<?php echo $help_upload; ?>"><?php echo $entry_upload; ?></span></label>
                <div class="col-sm-10">
                  <button type="button" id="button-upload" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                  <?php if ($error_warning) { ?>
                  <button type="button" id="button-clear" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></button>
                  <?php } else { ?>
                  <button type="button" id="button-clear" data-loading-text="<?php echo $text_loading; ?>" disabled="disabled" class="btn btn-danger"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></button>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_progress; ?></label>
                <div class="col-sm-10">
                  <div class="progress">
                    <div id="progress-bar" class="progress-bar" style="width: 0%;"></div>
                  </div>
                  <div id="progress-text"></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_overwrite; ?></label>
                <div class="col-sm-10">
                  <textarea rows="10" readonly id="overwrite" class="form-control"></textarea>
                  <br />
                  <button type="button" id="button-continue" class="btn btn-primary" disabled="disabled"><i class="fa fa-check"></i> <?php echo $button_continue; ?></button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
localStorage.setItem('extension-tab', 'list');
/*
$('#tab-analytics').load('index.php?route=extension/extension&token=<?php echo $token; ?>');

$('#tab-analytics').on('click', function() {
	$('#tab-analytics').load('index.php?route=extension/extension&token=<?php echo $token; ?>');
});
*/
//$('#tab-analytics').trigger();
//--></script> 
  <script type="text/javascript"><!--
var step = new Array();
var total = 0;

$('#button-upload').on('click', function() {
	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			// Reset everything
			$('.alert').remove();
			$('#progress-bar').css('width', '0%');
			$('#progress-bar').removeClass('progress-bar-danger progress-bar-success');
			$('#progress-text').html('');

			$.ajax({
				url: 'index.php?route=extension/extension/upload&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-upload').button('loading');
				},
				complete: function() {
					$('#button-upload').button('reset');
				},
				success: function(json) {
					if (json['error']) {
						$('#progress-bar').addClass('progress-bar-danger');
						$('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['step']) {
						step = json['step'];
						total = step.length;

						if (json['overwrite'].length) {
							html = '';

							for (i = 0; i < json['overwrite'].length; i++) {
								html += json['overwrite'][i] + "\n";
							}

							$('#overwrite').html(html);

							$('#button-continue').prop('disabled', false);
						} else {
							next();
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('#button-continue').on('click', function() {
	next();

	$('#button-continue').prop('disabled', true);
});

function next() {
	data = step.shift();

	if (data) {
		$('#progress-bar').css('width', (100 - (step.length / total) * 100) + '%');
		$('#progress-text').html('<span class="text-info">' + data['text'] + '</span>');

		$.ajax({
			url: data.url,
			type: 'post',
			dataType: 'json',
			data: 'path=' + data.path,
			success: function(json) {
				if (json['error']) {
					$('#progress-bar').addClass('progress-bar-danger');
					$('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');
					$('#button-clear').prop('disabled', false);
				}

				if (json['success']) {
					$('#progress-bar').addClass('progress-bar-success');
					$('#progress-text').html('<span class="text-success">' + json['success'] + '</span>');
				}

				if (!json['error'] && !json['success']) {
					next();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

$('#button-clear').bind('click', function() {
	$.ajax({
		url: 'index.php?route=extension/extension/clear&token=<?php echo $token; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-clear').button('loading');
		},
		complete: function() {
			$('#button-clear').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#button-clear').prop('disabled', true);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script></div>
<?php echo $footer; ?> 