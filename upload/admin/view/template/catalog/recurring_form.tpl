<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-recurring" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="alert alert-info"><?php echo $text_recurring; ?></div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-recurring" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="recurring_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($recurring_description[$language['language_id']]) ? $recurring_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
              </div>
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <fieldset>
            <legend><?php echo $text_profile; ?></legend>
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
                  <?php foreach ($frequencies as $frequency_option) { ?>
                  <?php if ($frequency == $frequency_option['value']) { ?>
                  <option value="<?php echo $frequency_option['value']; ?>" selected="selected"><?php echo $frequency_option['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $frequency_option['value']; ?>"><?php echo $frequency_option['text']; ?></option>
                  <?php } ?>
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
          </fieldset>
          <fieldset>
            <legend><?php echo $text_trial; ?></legend>
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
                  <?php foreach ($frequencies as $frequency_option) { ?>
                  <?php if ($trial_frequency  == $frequency_option['value']) { ?>
                  <option value="<?php echo $frequency_option['value']; ?>" selected="selected"><?php echo $frequency_option['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $frequency_option['value']; ?>"><?php echo $frequency_option['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
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
          </fieldset>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control"/>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>