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
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $text_btn_return; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-refresh fa-lg"></i> <?php echo $text_heading; ?></h1>
    </div>
    <div class="panel-body">
      <?php if($validation == true) { ?>
        <p><?php echo $text_sync_desc; ?></p>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="syncCats"><?php echo $text_sync_cats_lbl; ?></label>
          <div class="col-sm-10">
            <a class="btn btn-primary" id="syncCats" onclick="syncCats();"><?php echo $text_sync_btn; ?></a>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="sync-shop-cats"><?php echo $text_sync_shop_lbl; ?></label>
          <div class="col-sm-10">
            <a class="btn btn-primary" id="sync-shop-cats"><?php echo $text_sync_btn; ?></a>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="loadSettings"><?php echo $text_sync_setting_lbl; ?></label>
          <div class="col-sm-10">
            <a class="btn btn-primary" id="loadSettings" onclick="loadSettings();"><?php echo $text_sync_btn; ?></a>
          </div>
        </div>
      <?php }else{ ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_error_validation; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php } ?>
    </div>
</div>

<script type="text/javascript"><!--
    $('#sync-shop-cats').bind('click', function() {
      $.ajax({
          url: 'index.php?route=openbay/ebay/loadCategories&token=<?php echo $token; ?>',
          beforeSend: function(){
            $('#sync-shop-cats').empty().html('<i class="fa fa-refresh fa-spin"></i>');
            alert('<?php echo $text_ajax_ebay_categories; ?>');
          },
          type: 'post',
          dataType: 'json',
          success: function(json) {
            $('#sync-shop-cats').empty().html('<?php echo $text_sync_btn; ?>');
            alert(json.msg);
          },
          failure: function(){
            $('#sync-shop-cats').empty().html('<?php echo $text_sync_btn; ?>');
              alert('<?php echo $text_ajax_load_error; ?>');
          },
          error: function(){
            $('#sync-shop-cats').empty().html('<?php echo $text_sync_btn; ?>');
            alert('<?php echo $text_ajax_load_error; ?>');
          }
      });
    });

    function loadSettings(){
        $.ajax({
            url: 'index.php?route=openbay/ebay/loadSettings&token=<?php echo $token; ?>',
            beforeSend: function(){
                $('#loadSettings').hide();
                $('#imageloadSettings').show();
            },
            type: 'post',
            dataType: 'json',
            success: function(json) {
                $('#loadSettings').show(); $('#loadSettings').hide();

                if(json.error == false){
                    alert('<?php echo $text_ajax_setting_import; ?>');
                }else{
                    alert('<?php echo $text_ajax_setting_import_e; ?>');
                }
                $('#imageloadSettings').hide();
                $('#loadSettings').show();
            },
            failure: function(){
                $('#imageloadSettings').hide();
                $('#loadSettings').show();
                alert('<?php echo $text_ajax_load_error; ?>');
            },
            error: function(){
                $('#imageloadSettings').hide();
                $('#loadSettings').show();
                alert('<?php echo $text_ajax_load_error; ?>');
            }
        });
    }

    function syncShopCats(){
        $.ajax({
            url: 'index.php?route=openbay/ebay/loadSellerStore&token=<?php echo $token; ?>',
                beforeSend: function(){
                    $('#syncShopCats').hide();
                    $('#imageLoadingShopCats').show();
                },
            type: 'post',
            dataType: 'json',
            success: function(json) {
                $('#syncShopCats').show(); $('#imageLoadingShopCats').hide();

                if(json.error == 'false'){
                    alert('<?php echo $text_ajax_cat_import; ?>');
                }else{
                    alert(json.msg);
                }
            },
            failure: function(){
                $('#imageLoadingShopCats').hide();
                $('#syncShopCats').show();
                alert('<?php echo $text_ajax_load_error; ?>');
            },
            error: function(){
                $('#imageLoadingShopCats').hide();
                $('#syncShopCats').show();
                alert('<?php echo $text_ajax_load_error; ?>');
            }
        });
    }
//--></script>

<?php echo $footer; ?>