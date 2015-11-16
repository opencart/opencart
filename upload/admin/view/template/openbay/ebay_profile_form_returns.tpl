<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" onclick="$('#form').submit();"><i class="fa fa-check-circle"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_manage; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <input type="hidden" name="type" value="<?php echo $type; ?>" />
          <input type="hidden" name="ebay_profile_id" value="<?php echo $ebay_profile_id; ?>" />
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-returns" data-toggle="tab"><?php echo $tab_returns; ?></a></li>
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
            <div class="tab-pane" id="tab-returns">
              <?php if (!empty($setting['returns']['accepted'])) { ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $text_returns_accept; ?></label>
                  <div class="col-sm-10">
                    <?php if (!isset($data['returns_accepted'])){ $data['returns_accepted'] = ''; } ?>
                    <select name="data[returns_accepted]" class="form-control">
                      <?php foreach($setting['returns']['accepted'] as $v) { ?>
                        <option value="<?php echo $v['ReturnsAcceptedOption']; ?>" <?php if ($data['returns_accepted'] == $v['ReturnsAcceptedOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              <?php }else{ ?>
                <input type="hidden" name="data[returns_accepted]" value="" />
              <?php } ?>
              <?php if (!empty($setting['returns']['within'])) { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_returns_days; ?></label>
                <div class="col-sm-10">
                  <?php if (!isset($data['returns_within'])){ $data['returns_within'] = ''; } ?>
                  <select name="data[returns_within]" class="form-control">
                    <?php foreach($setting['returns']['within'] as $v) { ?>
                    <option value="<?php echo $v['ReturnsWithinOption']; ?>" <?php if ($data['returns_within'] == $v['ReturnsWithinOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php }else{ ?>
              <input type="hidden" name="data[returns_within]" value="" />
              <?php } ?>
              <?php if (!empty($setting['returns']['paidby'])) { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_returns_costs; ?></label>
                <div class="col-sm-10">
                  <?php if (!isset($data['returns_shipping'])){ $data['returns_shipping'] = ''; } ?>
                  <select name="data[returns_shipping]" class="form-control">
                    <?php foreach($setting['returns']['paidby'] as $v) { ?>
                    <option value="<?php echo $v['ShippingCostPaidByOption']; ?>" <?php if ($data['returns_shipping'] == $v['ShippingCostPaidByOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php }else{ ?>
              <input type="hidden" name="data[returns_shipping]" value="" />
              <?php } ?>
              <?php if (!empty($setting['returns']['refund'])) { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_returns_type; ?></label>
                <div class="col-sm-10">
                  <?php if (!isset($data['returns_option'])){ $data['returns_option'] = ''; } ?>
                  <select name="data[returns_option]" class="form-control">
                    <?php foreach($setting['returns']['refund'] as $v) { ?>
                    <option value="<?php echo $v['RefundOption']; ?>" <?php if ($data['returns_option'] == $v['RefundOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php }else{ ?>
              <input type="hidden" name="data[returns_option]" value="" />
              <?php } ?>
              <?php if ($setting['returns']['description'] == true) { ?>
                <?php if (!isset($data['returns_policy'])){ $data['returns_policy'] = ''; } ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $text_returns_inst; ?></label>
                  <div class="col-sm-10">
                    <textarea name="data[returns_policy]" class="form-control" rows="3" maxlength="5000"><?php echo $data['returns_policy'];?></textarea>
                  </div>
                </div>
              <?php }else{ ?>
                <input type="hidden" name="data[returns_policy]" value="" />
              <?php } ?>
              <?php if (!empty($setting['returns']['restocking_fee'])) { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_returns_restock; ?></label>
                <div class="col-sm-10">
                  <?php if (!isset($data['returns_restocking_fee'])){ $data['returns_restocking_fee'] = ''; } ?>
                  <select name="data[returns_restocking_fee]" class="form-control">
                    <?php foreach($setting['returns']['restocking_fee'] as $v) { ?>
                    <option value="<?php echo $v['RestockingFeeValueOption']; ?>" <?php if ($data['returns_restocking_fee'] == $v['RestockingFeeValueOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php }else{ ?>
              <input type="hidden" name="data[returns_restocking_fee]" value="" />
              <?php } ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>