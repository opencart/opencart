<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-google-sitemap" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-yandex_market_status" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="yandex_market_status" id="input-status" class="form-control">
                <?php if ($yandex_market_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
                <label class="col-sm-2 control-label" for="input-yandex_market_shopname"><span data-toggle="tooltip" title="<?php echo $help_shopname; ?>"><?php echo $entry_shopname; ?></span></label>
                <div class="col-sm-10">
				  <input type="text" name="yandex_market_shopname" value="<?php echo $yandex_market_shopname; ?>" placeholder="<?php echo $entry_shopname; ?>" id="input-yandex_market_shopname" class="form-control" />
                </div>
              </div>
		  <div class="form-group">
                <label class="col-sm-2 control-label" for="input-yandex_market_company"><span data-toggle="tooltip" title="<?php echo $help_company; ?>"><?php echo $entry_company; ?></span></label>
                <div class="col-sm-10">
				  <input type="text" name="yandex_market_company" value="<?php echo $yandex_market_company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-yandex_market_company" class="form-control" />
                </div>
              </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
			<div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($categories as $category) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($category['category_id'], $yandex_market_categories)) { ?>
						<input type="checkbox" name="yandex_market_categories[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
						<?php echo $category['name']; ?>
						<?php } else { ?>
						<input type="checkbox" name="yandex_market_categories[]" value="<?php echo $category['category_id']; ?>" />
						<?php echo $category['name']; ?>
						<?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
          </div>
		   <div class="form-group">
            <label class="col-sm-2 control-label" for="yandex_market_currency"><span data-toggle="tooltip" title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></label>
            <div class="col-sm-10">
              <select name="yandex_market_currency" id="yandex_market_currency" class="form-control">
					<?php foreach ($currencies as $currency) { ?>
					<?php if ($currency['code'] == $yandex_market_currency) { ?>
					<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
					<?php } ?>
					<?php } ?>
					</select>
            </div>
          </div>
		   <div class="form-group">
            <label class="col-sm-2 control-label" for="yandex_market_in_stock"><span data-toggle="tooltip" title="<?php echo $help_in_stock; ?>"><?php echo $entry_in_stock; ?></span></label>
            <div class="col-sm-10">
              <select name="yandex_market_in_stock" id="yandex_market_in_stock" class="form-control">
					<?php foreach ($stock_statuses as $stock_status) { ?>
                    <?php if ($stock_status['stock_status_id'] == $yandex_market_in_stock) { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
					</select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="yandex_market_out_of_stock"><span data-toggle="tooltip" title="<?php echo $help_out_of_stock; ?>"><?php echo $entry_out_of_stock; ?></span></label>
            <div class="col-sm-10">
              <select name="yandex_market_out_of_stock" id="yandex_market_out_of_stock" class="form-control">
					<?php foreach ($stock_statuses as $stock_status) { ?>
                    <?php if ($stock_status['stock_status_id'] == $yandex_market_out_of_stock) { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
					</select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-data-feed"><?php echo $entry_data_feed; ?></label>
            <div class="col-sm-10">
              <textarea rows="5" readonly id="input-data-feed" class="form-control"><?php echo $data_feed; ?></textarea>
            </div>
          </div>
		  <div class="form-group">
            <div style="text-align:center;" class="col-sm-12">
              <?php echo $help_yandex_market; ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>