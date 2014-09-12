<?php echo $header; ?><?php echo $column_left; ?>
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
        <a data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-default" onclick="$('#form').submit();"><i class="fa fa-check-circle"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-file-text fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $btn_save; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
        <input type="hidden" name="type" value="<?php echo $type; ?>" />
        <input type="hidden" name="ebay_profile_id" value="<?php echo $ebay_profile_id; ?>" />
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-profile" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_profile_default; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="default" value="0" />
                <input type="checkbox" name="default" value="1" <?php if ($default == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="name"><?php echo $text_profile_name; ?></label>
              <div class="col-sm-10">
                <input type="text" name="name" value="<?php if (isset($name)){ echo $name; } ?>" placeholder="<?php echo $text_profile_name; ?>" id="name" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="description"><?php echo $text_profile_desc; ?></label>
              <div class="col-sm-10">
                <textarea name="description" class="form-control" rows="3" id="description"><?php if (isset($description)){ echo $description; } ?></textarea>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-profile">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_general_private; ?></label>
              <div class="col-sm-10">
                <?php if (!isset($data['private_listing'])){ $data['private_listing'] = '0'; } ?>
                <select name="data[private_listing]" class="form-control">
                  <option value="0" <?php if ($data['private_listing'] == '0'){ echo'selected'; } ?>><?php echo $text_no; ?></option>
                  <option value="1" <?php if ($data['private_listing'] == '1'){ echo'selected'; } ?>><?php echo $text_yes; ?></option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="name"><?php echo $text_general_price; ?></label>
              <div class="col-sm-10">
                <input type="text" name="data[price_modify]" id="price_modify" value="<?php echo (isset($data['price_modify']) ? $data['price_modify'] : '0');  ?>" class="form-control" />
                <span class="help-block"><?php echo $text_general_price_help; ?></span>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
    $('#price_modify').on('change', function(){
        $(this).text().replace('%', '');
    });
//--></script>
<?php echo $footer; ?>