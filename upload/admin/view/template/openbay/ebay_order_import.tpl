<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $text_heading; ?></h1>
    </div>
    <div class="panel-body">
      <?php if ($validation === true) { ?>
        <form id="form-ebay-import" class="form-horizontal">
          <p><?php echo $text_sync_pull_notice; ?></p>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="button-import"><?php echo $entry_pull_orders; ?></label>
            <div class="col-sm-10">
              <a class="btn btn-primary" id="button-import"><?php echo $button_pull_orders; ?></a>
            </div>
          </div>
        </form>
      <?php }else{ ?>
        <div class="alert alert-danger">
          <i class="fa fa-exclamation-circle"></i> <?php echo $error_validation; ?>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  $('#button-import').bind('click', function() {
    $.ajax({
      url: 'index.php?route=openbay/ebay/importOrdersManual&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#button-import').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'post',
      dataType: 'json',
      success: function(json) {
        $('#button-import').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');
        alert('<?php echo $text_ajax_orders_import; ?>');
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#button-import').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $text_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });
//--></script>
<?php echo $footer; ?>