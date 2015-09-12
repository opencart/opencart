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
            <label class="col-sm-2 control-label" for="input-key"><?php echo $entry_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="maxmind_key" id="input-key" class="form-control" value="<?php echo $maxmind_key; ?>" placeholder="<?php echo $entry_key; ?>"/>
              <?php if ($error_key) { ?>
              <div class="text-danger"><?php echo $error_key; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-score"><?php echo $entry_score; ?></label>
            <div class="col-sm-10">
              <input type="text" name="maxmind_score" value="<?php echo $maxmind_score; ?>" placeholder="<?php echo $entry_score; ?>" id="input-score" class="form-control" />
              <div class="help"><?php echo $help_score; ?></div>
              <?php if ($error_score) { ?>
              <div class="text-danger"><?php echo $error_score; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="maxmind_order_status_id" id="input-order-status" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $maxmind_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
              <?php } ?>
              </select>
              <div class="help"><?php echo $help_order_status; ?></div>
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
