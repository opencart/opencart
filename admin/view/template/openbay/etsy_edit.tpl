<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default" id="btn-cancel"><i class="fa fa-reply"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <div id="page-listing">
          <?php if ($error_warning) { ?>
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
          <?php } ?>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
            <input type="hidden" name="etsy_item_id" value="<?php echo $etsy_item_id; ?>" />
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>"/>
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab-listing-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            </ul>
            <div class="tab-content">
              <div id="tab-listing-general" class="tab-pane active">
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="title" value="<?php echo $listing['title']; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="description" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control"><?php echo $listing['description']; ?></textarea>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="price" value="<?php echo $listing['price']; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-state"><?php echo $entry_state; ?></label>
                  <div class="col-sm-10">
                    <select name="state" id="input-state" class="form-control">
                      <?php foreach ($setting['state'] as $value) { ?>
                      <option value="<?php echo $value; ?>"<?php echo ($listing['state'] == $value) ? ' selected' : ''; ?>><?php echo $value; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="well">
                <div class="row">
                  <div class="col-sm-12 text-right">
                    <a data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" id="button-save"><i class="fa fa-save"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div id="page-listing-success" style="display:none;">
          <div class="well">
            <div class="row">
              <div class="col-sm-12">
                <h3><?php echo $text_updated; ?></h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  var image_count = 1;

  $('#button-save').bind('click', function() {
    $.ajax({
      url: 'index.php?route=openbay/etsy_product/editsubmit&token=<?php echo $token; ?>',
      beforeSend: function(){
        $('#button-submit').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'post',
      data: $("#form").serialize(),
      dataType: 'json',
      success: function(json) {
        if (json.error) {
          if (json.code) {
            alert(json.error);
          } else {
            $.each(json.error, function( k, v ) {
              alert(v);
            });
          }
          $('#button-submit').empty().html('<span><?php echo $button_submit; ?></span>').removeAttr('disabled');
        } else {
          if (json.listing_id) {
            $('#page-listing').hide();
            $('#page-listing-success').fadeIn();
            $('#button-submit').empty().html('<span><?php echo $button_submit; ?></span>').removeAttr('disabled');
          }
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        $('#button-submit').empty().html('<span><?php echo $button_submit; ?></span>').removeAttr('disabled');
      }
    });
  });

//--></script>
<?php echo $footer; ?>