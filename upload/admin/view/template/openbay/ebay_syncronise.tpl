<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-cog fa-lg fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <?php if ($validation == true) { ?>
        <p><?php echo $text_sync_desc; ?></p>
        <form id="form-ebay-sync" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="sync-cats"><span data-toggle="tooltip" title="<?php echo $help_sync_categories; ?>"><?php echo $entry_sync_categories; ?></span></label>
            <div class="col-sm-10">
              <a class="btn btn-primary" id="sync-cats"><?php echo $button_sync; ?></a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="sync-shop-cats"><span data-toggle="tooltip" title="<?php echo $help_sync_shop; ?>"><?php echo $entry_sync_shop; ?></span></label>
            <div class="col-sm-10">
              <a class="btn btn-primary" id="sync-shop-cats"><?php echo $button_sync; ?></a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="load-settings"><span data-toggle="tooltip" title="<?php echo $help_sync_setting; ?>"><?php echo $entry_sync_setting; ?></span></label>
            <div class="col-sm-10">
              <a class="btn btn-primary" id="load-settings"><?php echo $button_sync; ?></a>
            </div>
          </div>
        </form>
      <?php }else{ ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_validation; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
    </div>
</div>

<script type="text/javascript"><!--
  $('#sync-cats').bind('click', function() {
    $.ajax({
      url: 'index.php?route=openbay/ebay/loadcategories&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#sync-cats').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
        alert('<?php echo $text_ajax_ebay_categories; ?>');
      },
      type: 'post',
      dataType: 'json',
      success: function(json) {
        $('#sync-cats').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');
        alert(json.msg);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#sync-cats').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $error_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#load-settings').bind('click', function() {
    $.ajax({
      url: 'index.php?route=openbay/ebay/loadSettings&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#load-settings').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'post',
      dataType: 'json',
      success: function(json) {
        $('#load-settings').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');

        if (json.error == false){
            alert('<?php echo $text_ajax_setting_import; ?>');
        }else{
            alert('<?php echo $error_settings; ?>');
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#load-settings').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $error_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#sync-shop-cats').bind('click', function() {
    $.ajax({
      url: 'index.php?route=openbay/ebay/loadsellerstore&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#sync-shop-cats').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'post',
      dataType: 'json',
      success: function(json) {
        $('#sync-shop-cats').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');

        if (json.error == 'false'){
          alert('<?php echo $text_ajax_cat_import; ?>');
        }else{
          alert(json.msg);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#sync-shop-cats').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $error_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });
//--></script>
<?php echo $footer; ?>