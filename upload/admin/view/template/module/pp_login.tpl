<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-pp-login" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if (isset($error['error_warning'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pp-login" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="entry-client_id"><?php echo $entry_client_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="pp_login_client_id" value="<?php echo $pp_login_client_id; ?>" placeholder="<?php echo $entry_client_id; ?>" id="entry-client_id" class="form-control"/>
              <?php if ($error_client_id) { ?>
              <div class="text-danger"><?php echo $error_client_id; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="entry-secret"><?php echo $entry_secret; ?></label>
            <div class="col-sm-10">
              <input type="text" name="pp_login_secret" value="<?php echo $pp_login_secret; ?>" placeholder="<?php echo $entry_secret; ?>" id="entry-secret" class="form-control"/>
              <?php if ($error_secret) { ?>
              <div class="text-danger"><?php echo $error_secret; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="entry-sandbox"><span data-toggle="tooltip" title="<?php echo $help_sandbox; ?>"><?php echo $entry_sandbox; ?></span></label>
            <div class="col-sm-10">
              <select name="pp_login_sandbox" id="entry-sandbox" class="form-control">
                <?php if ($pp_login_sandbox) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-loggin"><span data-toggle="tooltip" title="<?php echo $help_debug_logging; ?>"><?php echo $entry_debug; ?></span></label>
            <div class="col-sm-10">
              <select name="pp_login_debug" id="input-logging" class="form-control">
                <?php if ($pp_login_debug) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="<?php echo $help_customer_group; ?>"><?php echo $entry_customer_group; ?></span></label>
            <div class="col-sm-10">
              <select name="pp_login_customer_group_id" id="input-customer-group" class="form-control">
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-button_colour"><?php echo $entry_button; ?></label>
            <div class="col-sm-10">
              <select name="pp_login_button_colour" id="input-button_colour" class="form-control">
                <?php if ($pp_login_button_colour == 'blue') { ?>
                <option value="blue" selected="selected"><?php echo $text_button_blue; ?></option>
                <option value="grey"><?php echo $text_button_grey; ?></option>
                <?php } else { ?>
                <option value="blue"><?php echo $text_button_blue; ?></option>
                <option value="grey" selected="selected"><?php echo $text_button_grey; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-seamless"><span data-toggle="tooltip" title="<?php echo $help_seamless; ?>"><?php echo $entry_seamless; ?></span></label>
            <div class="col-sm-10">
              <select name="pp_login_seamless" id="input-logging" class="form-control">
                <?php if ($pp_login_seamless) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_locale; ?>"><?php echo $entry_locale; ?></span></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <select name="pp_login_locale[<?php echo $language['language_id']; ?>]" class="form-control">
                  <?php foreach ($locales as $locale) { ?>
                  <?php if (isset($pp_login_locale[$language['language_id']]) && $pp_login_locale[$language['language_id']] == $locale['value']) { ?>
                  <option value="<?php echo $locale['value']; ?>" selected="selected"><?php echo $locale['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $locale['value']; ?>"><?php echo $locale['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_return_url; ?>"><?php echo $entry_return_url; ?></span></label>
            <div class="col-sm-10">
              <input type="text" readonly="readonly" id="return-url" value="<?php echo $pp_login_return_url; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="pp_login_status" id="input-status" class="form-control">
                <?php if ($pp_login_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>