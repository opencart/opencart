<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $text_btn_return; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $text_heading; ?></h1>
    </div>
    <div class="panel-body">
      <?php if ($validation === true) { ?>
        <?php if ($image_import > 0){ ?>
          <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i> <?php echo $image_import; ?> <?php echo $text_import_images_msg1; ?>
            <a href="<?php echo $image_import_link; ?>" target="_blank"><?php echo $text_import_images_msg2; ?></a> <?php echo $text_import_images_msg3; ?>
          </div>
        <?php } ?>

        <?php if ($maintenance == 1){ ?>
          <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i> <?php echo $text_maintenance_fail; ?>
          </div>
        <?php } ?>

        <p><?php echo $text_sync_import_line1; ?></p>
        <p><?php echo $text_sync_import_line3; ?></p>
        <div class="attention"><?php echo $text_sync_server_size; ?>
          <strong><?php echo ini_get('post_max_size'); ?></strong>
        </div>
        <div class="attention"><?php echo $text_sync_memory_size; ?>
          <strong><?php echo ini_get('memory_limit'); ?></strong>
        </div>
        <form id="form-ebay-import" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_import_categories; ?></label>
            <div class="col-sm-10">
              <input type="checkbox" name="import_categories" id="import_categories" value="1" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_sync_item_description; ?></label>
            <div class="col-sm-10">
              <input type="checkbox" name="import_description" id="import_description" value="1" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_import_item_advanced; ?></label>
            <div class="col-sm-10">
              <input type="checkbox" name="import_advanced" id="import_advanced" value="1" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="button-import"><?php echo $text_import_ebay_items; ?></label>
            <div class="col-sm-10">
              <a class="btn btn-primary" id="button-import"><?php echo $text_import; ?></a>
            </div>
          </div>
        </form>
      <?php }else{ ?>
        <div class="alert alert-danger">
          <i class="fa fa-exclamation-circle"></i> <?php echo $text_error_validation; ?>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
  $('#button-import').bind('click', function() {
    var answer = confirm("<?php echo $text_ajax_import_confirm;?>");

    if (answer) {
      var note_import = 0;
      var import_description = $('#import_description:checked').val();
      var import_advanced = $('#import_advanced:checked').val();
      var import_categories = $('#import_categories:checked').val();
      if (import_description == undefined){ import_description = 0; }else{ import_description = 1; }
      if (import_advanced == undefined){ import_advanced = 0; }else{ import_advanced = 1; }
      if (import_categories == undefined){ import_categories = 0; }else{ import_categories = 1; }

      $.ajax({
        url: 'index.php?route=openbay/ebay/importItems&token=<?php echo $token; ?>&desc='+import_description+'&note='+note_import+'&advanced='+import_advanced+'&categories='+import_categories,
        beforeSend: function(){
          $('#button-import').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
        },
        type: 'post',
        dataType: 'json',
        success: function(json) {
          $('#button-import').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');
          alert('<?php echo $text_ajax_import_notify; ?>');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          $('#button-import').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $text_failed; ?>').removeAttr('disabled');
          if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        }
      });
    } else {
      return 0;
    }
  });
//--></script>
<?php echo $footer; ?>