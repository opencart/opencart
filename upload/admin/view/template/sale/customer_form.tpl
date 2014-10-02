<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <?php if ($customer_id) { ?>
            <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
            <li><a href="#tab-transaction" data-toggle="tab"><?php echo $tab_transaction; ?></a></li>
            <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
            <li><a href="#tab-ip" data-toggle="tab"><?php echo $tab_ip; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="row">
                <div class="col-sm-2">
                  <ul class="nav nav-pills nav-stacked" id="address">
                    <li class="active"><a href="#tab-customer" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <li><a href="#tab-address<?php echo $address_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('#address a:first').tab('show'); $('#address a[href=\'#tab-address<?php echo $address_row; ?>\']').parent().remove(); $('#tab-address<?php echo $address_row; ?>').remove();"></i> <?php echo $tab_address . ' ' . $address_row; ?></a></li>
                    <?php $address_row++; ?>
                    <?php } ?>
                    <li id="address-add"><a onclick="addAddress();"><i class="fa fa-plus-circle"></i> <?php echo $button_address_add; ?></a></li>
                  </ul>
                </div>
                <div class="col-sm-10">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-customer">
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                        <div class="col-sm-10">
                          <select name="customer_group_id" id="input-customer-group" class="form-control">
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
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                          <?php if ($error_firstname) { ?>
                          <div class="text-danger"><?php echo $error_firstname; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                          <?php if ($error_lastname) { ?>
                          <div class="text-danger"><?php echo $error_lastname; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                          <?php if ($error_email) { ?>
                          <div class="text-danger"><?php echo $error_email; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                          <?php if ($error_telephone) { ?>
                          <div class="text-danger"><?php echo $error_telephone; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
                        </div>
                      </div>
                      <?php foreach ($custom_fields as $custom_field) { ?>
                      <?php if ($custom_field['location'] == 'account') { ?>
                      <?php if ($custom_field['type'] == 'select') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $account_custom_field[$custom_field['custom_field_id']]) { ?>
                            <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'radio') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <div class="radio">
                              <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $account_custom_field[$custom_field['custom_field_id']]) { ?>
                              <label>
                                <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } else { ?>
                              <label>
                                <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } ?>
                            </div>
                            <?php } ?>
                          </div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'checkbox') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <div class="checkbox">
                              <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $account_custom_field[$custom_field['custom_field_id']])) { ?>
                              <label>
                                <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } else { ?>
                              <label>
                                <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } ?>
                            </div>
                            <?php } ?>
                          </div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'text') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'textarea') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'file') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <button type="button" id="button-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                          <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : ''); ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'date') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group date">
                            <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="YYYY-MM-DD" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'time') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group time">
                            <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'datetime') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group datetime">
                            <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="YYYY-MM-DD HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php } ?>
                      <?php } ?>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
                        <div class="col-sm-10">
                          <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
                          <?php if ($error_password) { ?>
                          <div class="text-danger"><?php echo $error_password; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
                        <div class="col-sm-10">
                          <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" autocomplete="off" id="input-confirm" class="form-control" />
                          <?php if ($error_confirm) { ?>
                          <div class="text-danger"><?php echo $error_confirm; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-newsletter"><?php echo $entry_newsletter; ?></label>
                        <div class="col-sm-10">
                          <select name="newsletter" id="input-newsletter" class="form-control">
                            <?php if ($newsletter) { ?>
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
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                          <select name="status" id="input-status" class="form-control">
                            <?php if ($status) { ?>
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
                        <label class="col-sm-2 control-label" for="input-safe"><?php echo $entry_safe; ?></label>
                        <div class="col-sm-10">
                          <select name="safe" id="input-safe" class="form-control">
                            <?php if ($status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <div class="tab-pane" id="tab-address<?php echo $address_row; ?>">
                      <input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-firstname<?php echo $address_row; ?>"><?php echo $entry_firstname; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][firstname]" value="<?php echo $address['firstname']; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['firstname'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['firstname']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-lastname<?php echo $address_row; ?>"><?php echo $entry_lastname; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][lastname]" value="<?php echo $address['lastname']; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['lastname'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['lastname']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-company<?php echo $address_row; ?>"><?php echo $entry_company; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][company]" value="<?php echo $address['company']; ?>" placeholder="<?php echo $entry_company; ?>" id="input-company<?php echo $address_row; ?>" class="form-control" />
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-address-1<?php echo $address_row; ?>"><?php echo $entry_address_1; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['address_1'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['address_1']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-address-2<?php echo $address_row; ?>"><?php echo $entry_address_2; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2<?php echo $address_row; ?>" class="form-control" />
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-city<?php echo $address_row; ?>"><?php echo $entry_city; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][city]" value="<?php echo $address['city']; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['city'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['city']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-postcode<?php echo $address_row; ?>"><?php echo $entry_postcode; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode<?php echo $address_row; ?>" class="form-control" />
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-country<?php echo $address_row; ?>"><?php echo $entry_country; ?></label>
                        <div class="col-sm-10">
                          <select name="address[<?php echo $address_row; ?>][country_id]" id="input-country<?php echo $address_row; ?>" onchange="country(this, '<?php echo $address_row; ?>', '<?php echo $address['zone_id']; ?>');" class="form-control">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($countries as $country) { ?>
                            <?php if ($country['country_id'] == $address['country_id']) { ?>
                            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                          <?php if (isset($error_address[$address_row]['country'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['country']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-zone<?php echo $address_row; ?>"><?php echo $entry_zone; ?></label>
                        <div class="col-sm-10">
                          <select name="address[<?php echo $address_row; ?>][zone_id]" id="input-zone<?php echo $address_row; ?>" class="form-control">
                          </select>
                          <?php if (isset($error_address[$address_row]['zone'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['zone']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php foreach ($custom_fields as $custom_field) { ?>
                      <?php if ($custom_field['location'] == 'address') { ?>
                      <?php if ($custom_field['type'] == 'select') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <select name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <?php if (isset($address['custom_field'][$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address['custom_field'][$custom_field['custom_field_id']]) { ?>
                            <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'radio') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <div class="radio">
                              <?php if (isset($address['custom_field'][$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address['custom_field'][$custom_field['custom_field_id']]) { ?>
                              <label>
                                <input type="radio" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } else { ?>
                              <label>
                                <input type="radio" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } ?>
                            </div>
                            <?php } ?>
                          </div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'checkbox') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <div class="checkbox">
                              <?php if (isset($address['custom_field'][$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $address['custom_field'][$custom_field['custom_field_id']])) { ?>
                              <label>
                                <input type="checkbox" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } else { ?>
                              <label>
                                <input type="checkbox" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } ?>
                            </div>
                            <?php } ?>
                          </div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'text') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'textarea') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <textarea name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'file') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <button type="button" id="button-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                          <input type="hidden" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : ''); ?>" />
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'date') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group date">
                            <input type="text" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="YYYY-MM-DD" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'time') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group time">
                            <input type="text" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="HH:mm" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'datetime') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <label class="col-sm-2 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-10">
                          <div class="input-group datetime">
                            <input type="text" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-format="YYYY-MM-DD HH:mm" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php } ?>
                      <?php } ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_default; ?></label>
                        <div class="col-sm-10">
                          <label class="radio">
                            <?php if (($address['address_id'] == $address_id) || !$addresses) { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" />
                            <?php } else { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" />
                            <?php } ?>
                          </label>
                        </div>
                      </div>
                    </div>
                    <?php $address_row++; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <?php if ($customer_id) { ?>
            <div class="tab-pane" id="tab-history">
              <div id="history"></div>
              <br />
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                <div class="col-sm-10">
                  <textarea name="comment" rows="8" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control"></textarea>
                </div>
              </div>
              <div class="text-right">
                <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
              </div>
            </div>
            <div class="tab-pane" id="tab-transaction">
              <div id="transaction"></div>
              <br />
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-transaction-description"><?php echo $entry_description; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-transaction-description" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="button-transaction" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_transaction_add; ?></button>
              </div>
            </div>
            <div class="tab-pane" id="tab-reward">
              <div id="reward"></div>
              <br />
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-reward-description"><?php echo $entry_description; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-reward-description" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-points"><span data-toggle="tooltip" title="<?php echo $help_points; ?>"><?php echo $entry_points; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="points" value="" placeholder="<?php echo $entry_points; ?>" id="input-points" class="form-control" />
                </div>
              </div>
              <div class="text-right">
                <button type="button" id="button-reward" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_reward_add; ?></button>
              </div>
            </div>
            <?php } ?>
            <div class="tab-pane" id="tab-ip">
              <div id="ip"></div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('select[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/customfield&token=<?php echo $token; ?>&customer_group_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			$('.custom-field').hide();
			$('.custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('.custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('.custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'customer_group_id\']').trigger('change');
//--></script> 
  <script type="text/javascript"><!--
var address_row = <?php echo $address_row; ?>;

function addAddress() {
	html  = '<div class="tab-pane" id="tab-address' + address_row + '">';
	html += '  <input type="hidden" name="address[' + address_row + '][address_id]" value="" />';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-2 control-label" for="input-firstname' + address_row + '"><?php echo $entry_firstname; ?></label>';
	html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][firstname]" value="" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-2 control-label" for="input-lastname' + address_row + '"><?php echo $entry_lastname; ?></label>';
	html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][lastname]" value="" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group">';
	html += '    <label class="col-sm-2 control-label" for="input-company' + address_row + '"><?php echo $entry_company; ?></label>';
	html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][company]" value="" placeholder="<?php echo $entry_company; ?>" id="input-company' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-2 control-label" for="input-address-1' + address_row + '"><?php echo $entry_address_1; ?></label>';
	html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][address_1]" value="" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group">';
	html += '    <label class="col-sm-2 control-label" for="input-address-2' + address_row + '"><?php echo $entry_address_2; ?></label>';
	html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][address_2]" value="" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-2 control-label" for="input-city' + address_row + '"><?php echo $entry_city; ?></label>';
	html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][city]" value="" placeholder="<?php echo $entry_city; ?>" id="input-city' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-2 control-label" for="input-postcode' + address_row + '"><?php echo $entry_postcode; ?></label>';
	html += '    <div class="col-sm-10"><input type="text" name="address[' + address_row + '][postcode]" value="" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-2 control-label" for="input-country' + address_row + '"><?php echo $entry_country; ?></label>';
	html += '    <div class="col-sm-10"><select name="address[' + address_row + '][country_id]" id="input-country' + address_row + '" onchange="country(this, \'' + address_row + '\', \'0\');" class="form-control">';
    html += '         <option value=""><?php echo $text_select; ?></option>';
    <?php foreach ($countries as $country) { ?>
    html += '         <option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
    <?php } ?>
    html += '      </select></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-2 control-label" for="input-zone' + address_row + '"><?php echo $entry_zone; ?></label>';
	html += '    <div class="col-sm-10"><select name="address[' + address_row + '][zone_id]" id="input-zone' + address_row + '" class="form-control"><option value=""><?php echo $text_none; ?></option></select></div>';
	html += '  </div>';

	// Custom Fields
	<?php foreach ($custom_fields as $custom_field) { ?>
	<?php if ($custom_field['location'] == 'address') { ?>
	<?php if ($custom_field['type'] == 'select') { ?>

	html += '  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '  		<label class="col-sm-2 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '  		<div class="col-sm-10">';
	html += '  		  <select name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">';
	html += '  			<option value=""><?php echo $text_select; ?></option>';

	<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
	html += '  			<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo addslashes($custom_field_value['name']); ?></option>';
	<?php } ?>

	html += '  		  </select>';
	html += '  		</div>';
	html += '  	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'radio') { ?>
	html += '  	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '  		<label class="col-sm-2 control-label"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '  		<div class="col-sm-10">';
	html += '  		  <div>';

	<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
	html += '  			<div class="radio"><label><input type="radio" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" /><?php echo addslashes($custom_field_value['name']); ?></label></div>';
	<?php } ?>

	html += '		  </div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'checkbox') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '		<label class="col-sm-2 control-label"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-10">';
	html += '		  <div>';

	<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
	html += '			<div class="checkbox"><label><input type="checkbox" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" /><?php echo addslashes($custom_field_value['name']); ?></label></div>';
	<?php } ?>

	html += '		  </div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'text') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '		<label class="col-sm-2 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-10">';
	html += '		  <input type="text" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo addslashes($custom_field['value']); ?>" placeholder="<?php echo addslashes($custom_field['name']); ?>" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'textarea') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '		<label class="col-sm-2 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-10">';
	html += '		  <textarea name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo addslashes($custom_field['name']); ?>" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo addslashes($custom_field['value']); ?></textarea>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'file') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '		<label class="col-sm-2 control-label"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-10">';
	html += '		  <button type="button" id="button-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>';
	html += '		  <input type="hidden" name="address[' + address_row + '][<?php echo $custom_field['custom_field_id']; ?>]" value="" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" />';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'date') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '		<label class="col-sm-2 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-10">';
	html += '		  <div class="input-group date"><input type="text" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo addslashes($custom_field['value']); ?>" placeholder="<?php echo addslashes($custom_field['name']); ?>" data-format="YYYY-MM-DD" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'time') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '		<label class="col-sm-2 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-10">';
	html += '		  <div class="input-group time"><input type="text" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo addslashes($custom_field['name']); ?>" data-format="HH:mm" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'datetime') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '		<label class="col-sm-2 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-10">';
	html += '		  <div class="input-group datetime"><input type="text" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo addslashes($custom_field['value']); ?>" placeholder="<?php echo addslashes($custom_field['name']); ?>" data-format="YYYY-MM-DD HH:mm" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php } ?>
	<?php } ?>

	html += '  <div class="form-group">';
	html += '    <label class="col-sm-2 control-label"><?php echo $entry_default; ?></label>';
	html += '    <div class="col-sm-10"><label class="radio"><input type="radio" name="address[' + address_row + '][default]" value="1" /></label></div>';
	html += '  </div>';

    html += '</div>';

	$('#tab-general .tab-content').prepend(html);

	$('select[name=\'customer_group_id\']').trigger('change');

	$('select[name=\'address[' + address_row + '][country_id]\']').trigger('change');

	$('#address-add').before('<li><a href="#tab-address' + address_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'#address a:first\').tab(\'show\'); $(\'a[href=\\\'#tab-address' + address_row + '\\\']\').parent().remove(); $(\'#tab-address' + address_row + '\').remove();"></i> <?php echo $tab_address; ?> ' + address_row + '</a></li>');

	$('#address a[href=\'#tab-address' + address_row + '\']').tab('show');

	$('.date').datetimepicker({
		pickTime: false
	});
	
	$('.datetime').datetimepicker({
		pickDate: true,
		pickTime: true
	});
	
	$('.time').datetimepicker({
		pickDate: false
	});	

	address_row++;
}
//--></script> 
  <script type="text/javascript"><!--
function country(element, index, zone_id) {
  if (element.value != '') {
		$.ajax({
			url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + element.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'address[' + index + '][country_id]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('input[name=\'address[' + index + '][postcode]\']').parent().addClass('required');
				} else {
					$('input[name=\'address[' + index + '][postcode]\']').parent().parent().removeClass('required');
				}

				html = '<option value=""><?php echo $text_select; ?></option>';

				if (json['zone']) {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';

						if (json['zone'][i]['zone_id'] == zone_id) {
							html += ' selected="selected"';
						}

						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0"><?php echo $text_none; ?></option>';
				}

				$('select[name=\'address[' + index + '][zone_id]\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

$('select[name$=\'[country_id]\']').trigger('change');
//--></script> 
  <script type="text/javascript"><!--
$('#history').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#history').load(this.href);
});

$('#history').load('index.php?route=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-history').on('click', function(e) {
  e.preventDefault();

	$.ajax({
		url: 'index.php?route=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'comment=' + encodeURIComponent($('#tab-history textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('#button-history').button('loading');
		},
		complete: function() {
			$('#button-history').button('reset');
		},
		success: function(html) {
			$('.alert').remove();

			$('#history').html(html);

			$('#tab-history textarea[name=\'comment\']').val('');
		}
	});
});
//--></script> 
  <script type="text/javascript"><!--
$('#transaction').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#transaction').load(this.href);
});

$('#transaction').load('index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-transaction').on('click', function(e) {
  e.preventDefault();

  $.ajax({
		url: 'index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()),
		beforeSend: function() {
			$('#button-transaction').button('loading');
		},
		complete: function() {
			$('#button-transaction').button('reset');
		},
		success: function(html) {
			$('.alert').remove();

			$('#transaction').html(html);

			$('#tab-transaction input[name=\'amount\']').val('');
			$('#tab-transaction input[name=\'description\']').val('');
		}
	});
});
//--></script> 
  <script type="text/javascript"><!--
$('#reward').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#reward').load(this.href);
});

$('#reward').load('index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-reward').on('click', function(e) {
	e.preventDefault();

	$.ajax({
		url: 'index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),
		beforeSend: function() {
			$('#button-reward').button('loading');
		},
		complete: function() {
			$('#button-reward').button('reset');
		},
		success: function(html) {
			$('.alert').remove();

			$('#reward').html(html);

			$('#tab-reward input[name=\'points\']').val('');
			$('#tab-reward input[name=\'description\']').val('');
		}
	});
});

$('#ip').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#ip').load(this.href);
});

$('#ip').load('index.php?route=sale/customer/ip&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('body').delegate('.button-ban-add', 'click', function() {
	var element = this;

	$.ajax({
		url: 'index.php?route=sale/customer/addbanip&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'ip=' + encodeURIComponent(this.value),
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				 $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');

				$('.alert').fadeIn('slow');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$(element).replaceWith('<button type="button" value="' + element.value + '" class="btn btn-danger btn-xs button-ban-remove"><i class="fa fa-minus-circle"></i> <?php echo $text_remove_ban_ip; ?></button>');
			}
		}
	});
});

$('body').delegate('.button-ban-remove', 'click', function() {
	var element = this;

	$.ajax({
		url: 'index.php?route=sale/customer/removebanip&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'ip=' + encodeURIComponent(this.value),
		beforeSend: function() {
			$(element).button('loading');
		},
		complete: function() {
			$(element).button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				 $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				 $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$(element).replaceWith('<button type="button" value="' + element.value + '" class="btn btn-success btn-xs button-ban-add"><i class="fa fa-plus-circle"></i> <?php echo $text_add_ban_ip; ?></button>');
			}
		}
	});
});

$('#content').delegate('button[id^=\'button-custom-field\'], button[id^=\'button-address\']', 'click', function() {
	var node = this;
	
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	$('#form-upload input[name=\'file\']').on('change', function() {
		$.ajax({
			url: 'index.php?route=tool/upload/upload&token=<?php echo $token; ?>',
			type: 'post',		
			dataType: 'json',
			data: new FormData($(this).parent()[0]),
			cache: false,
			contentType: false,
			processData: false,		
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},		
			success: function(json) {
				$('.text-danger').remove();
				
				if (json['error']) {
					$(node).parent().find('input[type=\'hidden\']').after('<div class="text-danger">' + json['error'] + '</div>');
				}
							
				if (json['success']) {
					alert(json['success']);
				}
				
				if (json['code']) {
					$(node).parent().find('input[type=\'hidden\']').attr('value', json['code']);
				}
			},			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});

$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});	
//--></script></div>
<?php echo $footer; ?>