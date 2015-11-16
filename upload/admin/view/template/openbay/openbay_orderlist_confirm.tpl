<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> </div>
      <h1><?php echo $text_confirm_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="alert alert-warning"><?php echo $text_confirm_change_text; ?>: <strong><?php echo $status_mapped[$change_order_status_id]; ?></strong></div>
    <form action="<?php echo $link_complete; ?>" method="post" enctype="multipart/form-data" id="form" class="form">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-center"><?php echo $column_order_id; ?></td>
            <td class="text-left"><?php echo $column_customer; ?></td>
            <td class="text-left"><?php echo $column_status; ?></td>
            <td class="text-left"><?php echo $column_channel; ?></td>
            <td class="text-left"><?php echo $column_date_added; ?></td>
            <td class="text-left"><?php echo $column_additional; ?></td>
            <td class="text-left"><?php echo $column_comments; ?></td>
            <td class="text-center"><?php echo $column_notify; ?>&nbsp;
              <input type="checkbox" name="notify_all" id="notify_all" value="1" onchange="notifyAll();" /></td>
          </tr>
        </thead>
        <tbody>
          <input type="hidden" name="order_status_id" value="<?php echo $change_order_status_id; ?>"/>
          <?php foreach ($orders as $order) { ?>
            <input type="hidden" name="order_id[]" value="<?php echo $order['order_id']; ?>"/>
            <input type="hidden" name="old_status[<?php echo $order['order_id']; ?>]" value="<?php echo $order['order_status_id']; ?>"/>
            <tr>
              <td class="text-center"><?php echo $order['order_id']; ?></td>
              <td class="text-left"><?php echo $order['customer']; ?></td>
              <td class="text-left"><?php echo $order['status']; ?></td>
              <td class="text-left">
                <input type="hidden" name="channel[<?php echo $order['order_id']; ?>]" value="<?php echo $order['channel']; ?>"/>
                <?php echo $order['channel']; ?>
              </td>
              <td class="text-left"><?php echo $order['date_added']; ?></td>
              <td class="text-left">
                <?php if ($order['channel'] == 'eBay') { ?>
                  <?php if ($change_order_status_id == $ebay_status_shipped_id) { ?>
                    <div class="form-group">
                      <label class="control-label"><?php echo $entry_carrier; ?></label>
                      <select name="carrier[<?php echo $order['order_id']; ?>]" class="form-control">
                        <?php foreach($market_options['ebay']['carriers'] as $carrier) { ?>
                          <option <?php echo ($carrier['description'] == $order['shipping_method'] ? ' selected' : ''); ?>><?php echo $carrier['description']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label"><?php echo $entry_tracking_no; ?></label>
                      <input type="text" name="tracking[<?php echo $order['order_id']; ?>]" value="" placeholder="<?php echo $entry_tracking_no; ?>" class="form-control" />
                    </div>
                  <?php } ?>
                <?php } ?>
                <?php if ($order['channel'] == 'Amazon EU') { ?>
                  <a href="openbay_orderlist_confirm.tpl"></a>
                  <?php if ($change_order_status_id == $openbay_amazon_order_status_shipped) { ?>
                    <div class="form-group">
                      <label class="control-label"><?php echo $entry_carrier; ?></label>
                      <select name="carrier[<?php echo $order['order_id']; ?>]" class="form-control amazon_carrier" id="amazon_carrier_<?php echo $order['order_id']; ?>">
                        <?php foreach($market_options['amazon']['carriers'] as $courier) { ?>
                        <?php echo '<option'.($courier == $market_options['amazon']['default_carrier'] ? ' selected' : '').'>'.$courier.'</option>'; ?>
                        <?php } ?>
                        <option value="other"><?php echo $text_other; ?></option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label"><?php echo $entry_other; ?></label>
                      <input type="text" name="carrier_other[<?php echo $order['order_id']; ?>]" value="" placeholder="<?php echo $entry_other; ?>" class="form-control" id="amazon_carrier_<?php echo $order['order_id']; ?>_other" />
                    </div>
                    <div class="form-group">
                      <label class="control-label"><?php echo $entry_tracking_no; ?></label>
                      <input type="text" name="tracking[<?php echo $order['order_id']; ?>]" value="" placeholder="<?php echo $entry_tracking_no; ?>" class="form-control" />
                    </div>
                  <?php } ?>
                <?php } ?>
                <?php if ($order['channel'] == 'Amazon US') { ?>
                  <?php if ($change_order_status_id == $openbay_amazonus_order_status_shipped) { ?>
                  <div class="form-group">
                    <label class="control-label"><?php echo $entry_carrier; ?></label>
                    <select name="carrier[<?php echo $order['order_id']; ?>]" class="form-control amazonus_carrier" id="amazonus_carrier_<?php echo $order['order_id']; ?>">
                      <?php foreach($market_options['amazonus']['carriers'] as $courier) { ?>
                        <?php echo '<option'.($courier == $market_options['amazonus']['default_carrier'] ? ' selected' : '').'>'.$courier.'</option>'; ?>
                      <?php } ?>
                      <option value="other"><?php echo $text_other; ?></option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label"><?php echo $entry_other; ?></label>
                    <input type="text" name="carrier_other[<?php echo $order['order_id']; ?>]" value="" placeholder="<?php echo $entry_other; ?>" class="form-control" id="amazonus_carrier_<?php echo $order['order_id']; ?>_other" />
                  </div>
                  <div class="form-group">
                    <label class="control-label"><?php echo $entry_tracking_no; ?></label>
                    <input type="text" name="tracking[<?php echo $order['order_id']; ?>]" value="" placeholder="<?php echo $entry_tracking_no; ?>" class="form-control" />
                  </div>
                  <?php } ?>
                <?php } ?>
              </td>
              <td class="text-left"><textarea name="comments[<?php echo $order['order_id']; ?>]" class="form-control" rows="3"></textarea></td>
              <td class="text-center"><input type="hidden" name="notify[<?php echo $order['order_id']; ?>]" value="0"/>
              <input type="checkbox" name="notify[<?php echo $order['order_id']; ?>]" class="notify_checkbox" value="1"/></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pull-right"> <a onclick="validate();" class="btn btn-primary"><?php echo $button_update; ?></a> </div>
  </div>
</div>
<script type="text/javascript"><!--
  function notifyAll() {
    var value_checked = $('#notify_all').prop('checked');

    if(value_checked == true) {
      $('.notify_checkbox').prop('checked', true);
    }else{
      $('.notify_checkbox').prop('checked', false);
    }
  }

  function validate() {
    var element_id;
    var error = false;
    var error_amazon_carrier = false;
    var error_amazonus_carrier = false;

    $.each($('.amazon_carrier'), function(k,v) {
      if($(this).val() == 'other') {
        element_id = $(this).attr("id");

        if($('#'+element_id+'_other').val() == '') {
          error = true;
          error_amazon_carrier = true;
          $('#'+element_id+'_other').css('border-color','#FF0000');
        }
      }
    });

    $.each($('.amazonus_carrier'), function(k,v) {
      if($(this).val() == 'other') {
        element_id = $(this).attr("id");

        if($('#'+element_id+'_other').val() == '') {
          error = true;
          error_amazonus_carrier = true;
          $('#'+element_id+'_other').css('border-color','#FF0000');
        }
      }
    });

    if(error_amazon_carrier == true) {
      alert('<?php echo $text_error_carrier_other; ?>');
    }

    if(error_amazonus_carrier == true) {
      alert('<?php echo $text_error_carrier_other; ?>');
    }

    if(error == false) {
      $('#form').submit();
    }else{
      return false;
    }
  }
//--></script>
<?php echo $footer; ?>