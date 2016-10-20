<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_sync; ?></h3>
      </div>
      <div class="panel-body">
        <?php if ($validation == true) { ?>
          <p><?php echo $text_sync_desc; ?></p>
          <form id="form-ebay-sync" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="update-categories"><span data-toggle="tooltip" title="<?php echo $help_sync_categories; ?>"><?php echo $entry_sync_categories; ?></span></label>
              <div class="col-sm-10">
                <a class="btn btn-primary" id="update-categories"><?php echo $button_update; ?></a>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="update-store"><span data-toggle="tooltip" title="<?php echo $help_sync_shop; ?>"><?php echo $entry_sync_shop; ?></span></label>
              <div class="col-sm-10">
                <a class="btn btn-primary" id="update-store"><?php echo $button_update; ?></a>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="update-settings"><span data-toggle="tooltip" title="<?php echo $help_sync_setting; ?>"><?php echo $entry_sync_setting; ?></span></label>
              <div class="col-sm-10">
                <a class="btn btn-primary" id="update-settings"><?php echo $button_update; ?></a>
              </div>
            </div>
          </form>
        <?php } else { ?>
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_validation; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  $('#update-categories').bind('click', function() {
    $.ajax({
      url: 'index.php?route=extension/openbay/ebay/updatecategories&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#update-categories').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
        alert('<?php echo $text_ebay_categories; ?>');
      },
      type: 'post',
      dataType: 'json',
      success: function(json) {
        $('#update-categories').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');
        alert(json.msg);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#update-categories').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $error_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#update-settings').bind('click', function() {
    $.ajax({
      url: 'index.php?route=extension/openbay/ebay/updatesettings&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#update-settings').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'post',
      dataType: 'json',
      success: function(json) {
        $('#update-settings').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');

        if (json.error == false){
            alert('<?php echo $text_setting_import; ?>');
        }else{
            alert('<?php echo $error_settings; ?>');
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#update-settings').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $error_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#update-store').bind('click', function() {
    $.ajax({
      url: 'index.php?route=extension/openbay/ebay/updatestore&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#update-store').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'post',
      dataType: 'json',
      success: function(json) {
        $('#update-store').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');

        if (json.error == 'false'){
          alert('<?php echo $text_category_import; ?>');
        }else{
          alert(json.msg);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#update-store').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $error_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });
//--></script>
<?php echo $footer; ?>