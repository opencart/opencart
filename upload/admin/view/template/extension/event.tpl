<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_event; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                <td class="text-left"><?php if ($sort == 'code') { ?>
                  <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'trigger') { ?>
                  <a href="<?php echo $sort_trigger; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_trigger; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_trigger; ?>"><?php echo $column_trigger; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'action') { ?>
                  <a href="<?php echo $sort_action; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_action; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_action; ?>"><?php echo $column_action; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'status') { ?>
                  <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                  <?php } ?></td>
                <td class="text-left"><?php if ($sort == 'date_added') { ?>
                  <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                  <?php } ?></td>
                <td class="text-right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($events) { ?>
              <?php foreach ($events as $event) { ?>
              <tr>
                <td class="text-center"><?php if (in_array($event['event_id'], $selected)) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $event['event_id']; ?>" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $event['event_id']; ?>" />
                  <?php } ?></td>
                <td class="text-left"><?php echo $event['code']; ?></td>
                <td class="text-left"><?php echo $event['trigger']; ?></td>
                <td class="text-left"><?php echo $event['action']; ?></td>
                <td class="text-left"><?php echo $event['status']; ?></td>
                <td class="text-left"><?php echo $event['date_added']; ?></td>
                <td class="text-right"><?php if (!$event['enabled']) { ?>
                  <a href="<?php echo $event['enable']; ?>" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                  <?php } else { ?>
                  <a href="<?php echo $event['disable']; ?>" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                  <?php } ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 