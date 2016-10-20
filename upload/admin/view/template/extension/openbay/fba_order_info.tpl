<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
        <?php if ($fba_order_status == 1) { ?>
          <a href="<?php echo $resend_link; ?>" id="button-resend" data-toggle="tooltip" title="<?php echo $button_resend; ?>" class="btn btn-info" value=""><i class="fa fa-refresh"></i></a>
        <?php } ?>
        <?php if ($fba_order_status == 2) { ?>
          <a href="<?php echo $ship_link; ?>" id="button-ship" data-toggle="tooltip" title="<?php echo $button_ship; ?>" class="btn btn-info" value=""><i class="fa fa-truck"></i></a>
        <?php } ?>
        <?php if ($fba_order_status == 0 || $fba_order_status == 2) { ?>
          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $cancel_link; ?>' : false;" id="button-cancel" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger" value=""><i class="fa fa-times-circle"></i></a>
        <?php } ?>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid" id="main-body">
    <?php if ($error_warning) { ?>
      <?php foreach ($error_warning as $warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
    <?php } ?>
    <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <div class="panel-body">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
        <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-general">
          <div class="row">
            <div class="col-md-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $text_shipping_address; ?></h3>
                </div>
                <table class="table">
                  <tbody>
                  <tr>
                    <td><?php echo $shipping_address; ?></td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-md-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-user"></i> <?php echo $text_order_info; ?></h3>
                </div>
                <table class="table">
                  <tr>
                    <td><button data-toggle="tooltip" title="<?php echo $text_opencart_order; ?>" class="btn btn-info btn-xs"><i class="fa fa-shopping-cart fa-fw"></i></button></td>
                    <td><a href="<?php echo $order_link; ?>"><?php echo $order_id; ?></a></td>
                  </tr>
                  <tr>
                    <td style="width: 1%;">
                      <button data-toggle="tooltip" title="<?php echo $text_status; ?>" class="btn btn-info btn-xs"><i class="fa fa-cog fa-fw"></i></button>
                    </td>
                    <td>
                      <?php if ($fba_order_status == 0) { ?>
                      <label class="label label-info"><?php echo $status_options[$fba_order_status]; ?></label>
                      <?php } elseif ($fba_order_status == 1)  { ?>
                      <label class="label label-danger"><?php echo $status_options[$fba_order_status]; ?></label>
                      <?php } elseif ($fba_order_status == 2)  { ?>
                      <label class="label label-warning"><?php echo $status_options[$fba_order_status]; ?></label>
                      <?php } elseif ($fba_order_status == 3)  { ?>
                      <label class="label label-success"><?php echo $status_options[$fba_order_status]; ?></label>
                      <?php } elseif ($fba_order_status == 4)  { ?>
                      <label class="label label-danger"><?php echo $status_options[$fba_order_status]; ?></label>
                      <?php } ?>
                    </td>
                  </tr>
                  <?php if($fulfillment_id) { ?>
                  <tr>
                    <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_fulfillment_id; ?>" class="btn btn-info btn-xs"><i class="fa fa-truck fa-fw"></i></button></td>
                    <td><a href="<?php echo $fulfillment_link; ?>"><?php echo $fulfillment_id; ?></a></td>
                  </tr>
                  <?php } ?>
                </table>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_order; ?></h3>
            </div>
            <div class="panel-body">
              <table class="table table-bordered">
                <thead>
                <tr>
                  <td class="text-left"><?php echo $column_product; ?></td>
                  <td class="text-left"><?php echo $column_sku; ?></td>
                  <td class="text-right"><?php echo $column_quantity; ?></td>
                  <td class="text-right"><?php echo $column_fba; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                    <?php foreach ($product['option'] as $option) { ?>
                    <br />
                    <?php if ($option['type'] != 'file') { ?>
                    &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                    <?php } ?>
                    <?php } ?></td>
                  <td class="text-left">
                    <?php if (!empty($product['sku'])) { ?>
                    <?php echo $product['sku']; ?>
                    <?php } else { ?>
                    <span class="label label-danger"><?php echo $text_no_sku; ?></span>
                    <?php } ?>
                  </td>
                  <td class="text-right"><?php echo $product['quantity']; ?></td>
                  <td class="text-right">
                    <?php if ($product['fba'] == 1) { ?>
                    <span class="btn btn-success btn-sm"><i class="fa fa-check fa-fw"></i></span>
                    <?php } else { ?>
                    <span class="btn btn-danger btn-sm"><i class="fa fa-minus fa-fw"></i></span>
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-history">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><i class="fa fa-comment-o"></i> <?php echo $text_history; ?></h3>
            </div>
            <div class="panel-body">
              <table class="table table-bordered">
                <thead>
                <tr>
                  <td class="text-left"><?php echo $column_fulfillment_id; ?></td>
                  <td class="text-left"><?php echo $column_created; ?></td>
                  <td class="text-left"><?php echo $column_type; ?></td>
                  <td class="text-right"><?php echo $column_response_code; ?></td>
                  <td class="text-right"><?php echo $column_actions; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($fulfillments)) { ?>
                  <?php foreach ($fulfillments as $fulfillment) { ?>
                    <tr>
                      <td class="text-left"><?php echo $fulfillment['fba_order_fulfillment_id']; ?></td>
                      <td class="text-left"><?php echo $fulfillment['created']; ?></td>
                      <td class="text-left"><?php echo $type_options[$fulfillment['type']]; ?></td>
                      <td class="text-right"><label class="label <?php echo ($fulfillment['response_header_code'] == 200 || $fulfillment['response_header_code'] == 201) ? 'label-success' : 'label-danger'; ?>"><?php echo $fulfillment['response_header_code']; ?></label></td>
                      <td class="text-right">
                        <?php if (!empty($fulfillment['request_body'])) { ?><button data-toggle="tooltip" title="<?php echo $text_show_request; ?>" class="btn btn-info btn-sm" onclick="$('#request-row-<?php echo $fulfillment['fba_order_fulfillment_id']; ?>').toggle();"><i class="fa fa-mail-forward fa-fw"></i></button><?php } ?>
                        <?php if (!empty($fulfillment['response_body'])) { ?><button data-toggle="tooltip" title="<?php echo $text_show_response; ?>" class="btn btn-info btn-sm" onclick="$('#response-row-<?php echo $fulfillment['fba_order_fulfillment_id']; ?>').toggle();"><i class="fa fa-mail-reply fa-fw"></i></button><?php } ?>
                        <?php if (!empty($fulfillment['errors'])) { ?><button data-toggle="tooltip" title="<?php echo $text_show_errors; ?>" class="btn btn-danger btn-sm" onclick="$('#error-row-<?php echo $fulfillment['fba_order_fulfillment_id']; ?>').toggle();"><i class="fa fa-exclamation fa-fw"></i></button><?php } ?>
                      </td>
                    </tr>
                    <tr id="error-row-<?php echo $fulfillment['fba_order_fulfillment_id']; ?>" style="display:none;">
                      <td class="text-left" colspan="4">
                        <?php if (empty($fulfillment['errors'])) { ?>
                        <div class="alert alert-info" style="width:100%;"><?php echo $text_no_errors; ?></div>
                        <?php } ?>
                        <?php foreach($fulfillment['errors'] as $fulfillment_error) { ?>
                        <div class="alert alert-warning" style="width:100%;">(<?php echo $fulfillment_error['code']; ?>) <?php echo str_replace(array("\r\n", "\r", "\n"), '', $fulfillment_error['message']); ?></div>
                        <?php } ?>
                      </td>
                    </tr>
                    <tr id="request-row-<?php echo $fulfillment['fba_order_fulfillment_id']; ?>" style="display:none;">
                      <td class="text-left" colspan="4">
                        <pre><?php print_r($fulfillment['request_body']); ?></pre>
                      </td>
                    </tr>
                    <tr id="response-row-<?php echo $fulfillment['fba_order_fulfillment_id']; ?>" style="display:none;">
                      <td class="text-left" colspan="4">
                        <pre><?php print_r($fulfillment['response_body']); ?></pre>
                      </td>
                    </tr>
                  <?php } ?>
                <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="5"><?php echo $text_no_requests; ?></td>
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
<script type="text/javascript"><!--

$('#button-resend').click(function() {
  $('#button-resend').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
});

$('#button-ship').click(function() {
  $('#button-ship').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
});

$('#button-cancel').click(function() {
  $('#button-cancel').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
});

//--></script>
<?php echo $footer; ?>
