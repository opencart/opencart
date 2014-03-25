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
        <button type="submit" form="form-profile" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a> </div>
      <h1 class="panel-title"><i class="fa fa-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-profile" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-profile" data-toggle="tab"><?php echo $tab_profile; ?></a></li>
          <li><a href="#tab-trial" data-toggle="tab"><?php echo $tab_trial; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <ul class="nav nav-tabs" id="language">
              <?php foreach ($languages as $language) { ?>
              <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
              <?php } ?>
            </ul>
            <div class="tab-content">
              <?php foreach ($languages as $language) { ?>
              <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="profile_description[<?php echo $language['language_id']; ?>][name]"><?php echo $entry_name ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="profile_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($profile_description[$language['language_id']]) ? $profile_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="profile_description[<?php echo $language['language_id']; ?>][name]" class="form-control"/>
                    <?php if (isset($error_name[$language['language_id']])) { ?>
                    <span class="text-danger"><?php echo $error_name[$language['language_id']]; ?></span>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
              <div class="col-sm-10">
                <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control"/>
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
          </div>
          <div class="tab-pane" id="tab-profile"> <span class="help-block"><?php echo $text_recurring_help ?></span>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
              <div class="col-sm-10">
                <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-duration"><?php echo $entry_duration; ?></label>
              <div class="col-sm-10">
                <input type="text" name="duration" value="<?php echo $duration; ?>" placeholder="<?php echo $entry_duration; ?>" id="input-duration" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-cycle"><?php echo $entry_cycle; ?></label>
              <div class="col-sm-10">
                <input type="text" name="cycle" value="<?php echo $cycle; ?>" placeholder="<?php echo $entry_cycle; ?>" id="input-cycle" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-frequency"><?php echo $entry_frequency; ?></label>
              <div class="col-sm-10">
                <select name="frequency" id="input-frequency" class="form-control">
                  <?php foreach ($frequencies as $key => $title) { ?>
                  <?php if ($frequency == $key) { ?>
                  <option value="<?php echo $key ?>" selected="selected"><?php echo $title ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $key ?>"><?php echo $title ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-trial">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-status"><?php echo $entry_trial_status; ?></label>
              <div class="col-sm-10">
                <select name="trial_status" id="input-trial-status" class="form-control">
                  <?php if ($trial_status) { ?>
                  <option value="0"><?php echo $text_disabled ?></option>
                  <option value="1" selected="selected"><?php echo $text_enabled ?></option>
                  <?php } else { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled ?></option>
                  <option value="1"><?php echo $text_enabled ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-price"><?php echo $entry_trial_price; ?></label>
              <div class="col-sm-10">
                <input type="text" name="trial_price" value="<?php echo $trial_price; ?>" placeholder="<?php echo $entry_trial_price; ?>" id="input-trial-price" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-duration"><?php echo $entry_trial_duration; ?></label>
              <div class="col-sm-10">
                <input type="text" name="trial_duration" value="<?php echo $trial_duration; ?>" placeholder="<?php echo $entry_trial_duration; ?>" id="input-trial-duration" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-cycle"><?php echo $entry_trial_cycle; ?></label>
              <div class="col-sm-10">
                <input type="text" name="trial_cycle" value="<?php echo $trial_cycle; ?>" placeholder="<?php echo $entry_trial_cycle; ?>" id="input-trial-cycle" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-frequency"><?php echo $entry_trial_frequency; ?></label>
              <div class="col-sm-10">
                <select name="trial_frequency" id="input-trial-frequency" class="form-control">
                  <?php foreach ($frequencies as $key => $title) { ?>
                  <?php if ($trial_frequency  == $key) { ?>
                  <option value="<?php echo $key ?>" selected="selected"><?php echo $title ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $key ?>"><?php echo $title ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script> 
<?php echo $footer; ?>