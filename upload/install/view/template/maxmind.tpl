<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h3><?php echo $heading_maxmind; ?><br><small><?php echo $heading_maxmind_small; ?></small></h3>
      </div>
      <div class="col-sm-6">
        <div id="logo" class="pull-right hidden-xs">
          <img src="view/image/logo.png" alt="OpenCart" title="OpenCart" />
        </div>
      </div>
    </div>
  </header>
  <div class="row">
    <div class="col-sm-12">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <p><?php echo $text_maxmind_top; ?> <a target="_blank" href="http://www.maxmind.com/?rId=opencart"><u><?php echo $text_maxmind_link; ?></u></a></p>
        <fieldset>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="fraud-key"><?php echo $entry_licence_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_fraud_key" id="fraud-key" class="form-control" value="<?php echo $config_fraud_key; ?>" placeholder="<?php echo $entry_licence_key; ?>"/>
              <?php if ($error_fraud_key) { ?>
              <div class="text-danger"><?php echo $error_fraud_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="fraud-score"><span data-toggle="tooltip" title="<?php echo $help_maxmind_risk; ?>"><?php echo $entry_risk; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="config_fraud_score" id="fraud-score" class="form-control" value="<?php echo $config_fraud_score; ?>" placeholder="<?php echo $entry_risk; ?>" />
              <?php if ($error_fraud_score) { ?>
              <div class="text-danger"><?php echo $error_fraud_score; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="fraud-status-id"><span data-toggle="tooltip" title="<?php echo $maxmind_fraud; ?>"><?php echo $entry_fraud_status; ?></span></label>
            <div class="col-sm-10">
              <select name="config_fraud_status_id" id="fraud-status-id" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $config_fraud_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>
        </fieldset>
        <div class="buttons">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>