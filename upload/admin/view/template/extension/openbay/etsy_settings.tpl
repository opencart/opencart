<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-etsy-settings" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" onclick="validateForm(); return false;"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
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
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($account_info != false) { ?>
      <?php if ($account_info['header_code'] == 200) { ?>
        <div class="alert alert-success"><i class="fa fa-check"></i> <?php echo $text_account_ok; ?></div>
      <?php } else { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_account_info; ?> (<?php echo $account_info['header_code']; ?>)</div>
      <?php } ?>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-etsy-settings" class="form-horizontal">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_api_info; ?></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-general">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="etsy_status"><?php echo $text_status; ?></label>
            <div class="col-sm-10">
              <select name="etsy_status" id="etsy_status" class="form-control ftpsetting">
                <?php if ($etsy_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="etsy_token"><?php echo $entry_token; ?></label>
            <div class="col-sm-10">
              <input type="text" name="etsy_token" value="<?php echo $etsy_token; ?>" placeholder="<?php echo $entry_token; ?>" id="etsy_token" class="form-control credentials" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="etsy_enc1"><?php echo $entry_enc1; ?></label>
            <div class="col-sm-10">
              <input type="text" name="etsy_enc1" value="<?php echo $etsy_enc1; ?>" placeholder="<?php echo $entry_enc1; ?>" id="etsy_enc1" class="form-control credentials" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="etsy_enc2"><?php echo $entry_enc2; ?></label>
            <div class="col-sm-10">
              <input type="text" name="etsy_enc2" value="<?php echo $etsy_enc2; ?>" placeholder="<?php echo $entry_enc2; ?>" id="etsy_enc2" class="form-control credentials" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_api_other; ?></label>
            <div class="col-sm-10">
              <p><a href="https://account.openbaypro.com/etsy/apiRegister/" target="_BLANK"><i class="fa fa-link"></i> <?php echo $text_token_register; ?></a></p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="etsy_address_format"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_address_format; ?>"><?php echo $entry_address_format; ?></span></label>
            <div class="col-sm-10">
              <textarea name="etsy_address_format" class="form-control" rows="3" id="etsy_address_format"><?php echo $etsy_address_format; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="etsy_order_status_new"><?php echo $entry_import_def_id; ?></label>
            <div class="col-sm-10">
              <select name="etsy_order_status_new" id="etsy_order_status_new" class="form-control">
                <?php if (empty($etsy_order_status_new)) { $etsy_order_status_new = 1; } ?>
                <?php foreach ($order_statuses as $status) { ?>
                <?php echo'<option value="'.$status['order_status_id'].'"'.($etsy_order_status_new == $status['order_status_id'] ? ' selected=selected' :'').'>'.$status['name'].'</option>'; ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="etsy_order_status_paid"><?php echo $entry_import_paid_id; ?></label>
            <div class="col-sm-10">
              <select name="etsy_order_status_paid" id="etsy_order_status_paid" class="form-control">
                <?php if (empty($etsy_order_status_paid)) { $etsy_order_status_paid = 2; } ?>
                <?php foreach ($order_statuses as $status) { ?>
                <?php echo'<option value="'.$status['order_status_id'].'"'.($etsy_order_status_paid == $status['order_status_id'] ? ' selected=selected' :'').'>'.$status['name'].'</option>'; ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="etsy_order_status_shipped"><?php echo $entry_import_shipped_id; ?></label>
            <div class="col-sm-10">
              <select name="etsy_order_status_shipped" id="etsy_order_status_shipped" class="form-control">
                <?php if (empty($etsy_order_status_shipped)) { $etsy_order_status_shipped = 3; } ?>
                <?php foreach ($order_statuses as $status) { ?>
                <?php echo'<option value="'.$status['order_status_id'].'"'.($etsy_order_status_shipped == $status['order_status_id'] ? ' selected=selected' :'').'>'.$status['name'].'</option>'; ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="button-import"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_pull_orders; ?>"><?php echo $text_pull_orders; ?></span></label>
            <div class="col-sm-10"> <a class="btn btn-primary" id="button-import"><i class="fa fa-refresh"></i></a> </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="button-settings"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_sync_settings; ?>"><?php echo $text_sync_settings; ?></span></label>
            <div class="col-sm-10"> <a class="btn btn-primary" id="button-settings"><i class="fa fa-refresh"></i></a> </div>
          </div>
        </div>
      </div>
    </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  function validateForm() {
      $('#form-etsy-settings').submit();
  }

  $('.credentials').change(function() {
    checkCredentials();
  });

  $(document).ready(function() {
    checkCredentials();
  });

  $('#button-import').bind('click', function() {
    $.ajax({
      url: 'index.php?route=openbay/etsy/getorders&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#button-import').removeClass('btn-success').removeClass('btn-danger').addClass('btn-primary').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'post',
      dataType: 'json',
      success: function(json) {
        if (json.header_code == 200) {
          $('#button-import').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');
          alert('<?php echo $text_orders_imported; ?>');
        } else {
          $('#button-import').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $text_failed; ?>').removeAttr('disabled');
          alert(json.data.error + '(' + json.data.code + ')');
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#button-import').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $text_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#button-settings').bind('click', function() {
    $.ajax({
      url: 'index.php?route=openbay/etsy/settingsupdate&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#button-settings').removeClass('btn-success').removeClass('btn-danger').addClass('btn-primary').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'get',
      dataType: 'json',
      success: function(json) {
        if (json.header_code == 200) {
          $('#button-settings').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');
        } else {
          $('#button-settings').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $text_failed; ?>').removeAttr('disabled');
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#button-settings').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $text_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });
//--></script>
<?php echo $footer; ?>