<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"> <a href="<?php echo $link_overview; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> </div>
      <h1><?php echo $text_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="well">
      <div class="row">
        <div class="col-sm-5">
          <div class="input-group date">
            <input type="text" class="form-control" id="input-date-start" data-date-format="YYYY-MM-DD" placeholder="<?php echo $entry_date_start; ?>" value="<?php echo $date_start; ?>" name="filter_date_start">
            <span class="input-group-btn">
            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
            </span> </div>
        </div>
        <div class="col-sm-5">
          <div class="input-group date">
            <input type="text" class="form-control" id="input-date-end" data-date-format="YYYY-MM-DD" placeholder="<?php echo $entry_date_end; ?>" value="<?php echo $date_end; ?>" name="filter_date_end">
            <span class="input-group-btn">
            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
            </span> </div>
        </div>
        <div class="col-sm-2 text-right"> <a onclick="filter();" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_filter; ?>"><i class="fa fa-filter"></i></a> </div>
      </div>
    </div>
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th class="text-left"><?php echo $column_ref; ?></th>
          <th class="text-left"><?php echo $column_date_requested; ?></th>
          <th class="text-right"><?php echo $column_date_updated; ?></th>
          <th class="text-right"><?php echo $column_status; ?></th>
          <th class="text-left"><?php echo $column_sku; ?></th>
          <th class="text-left"><?php echo $column_stock; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($table_data)) { ?>
        <tr>
          <td class="text-center" colspan="6"><?php echo $text_empty; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($table_data as $ref => $row) { ?>
        <tr>
          <td class="text-left" rowspan="<?php echo count($row['data']) + 1; ?>"><?php echo $ref; ?></td>
          <td class="text-left" rowspan="<?php echo count($row['data']) + 1; ?>"><?php echo $row['date_requested']; ?></td>
          <td class="text-right" rowspan="<?php echo count($row['data']) + 1; ?>"><?php echo $row['date_updated']; ?></td>
          <td class="text-right" rowspan="<?php echo count($row['data']) + 1; ?>"><?php echo $row['status']; ?></td>
          <?php foreach ($row['data'] as $dataRow) { ?>
        <tr>
          <td class="text-left"><?php echo $dataRow['sku']; ?></td>
          <td class="text-left"><?php echo $dataRow['stock']; ?></td>
        </tr>
        <?php } ?>
          </tr>

        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
  url = 'index.php?route=openbay/amazonus/stockUpdates&token=<?php echo $token; ?>';

  var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');

  if (filter_date_start) {
    url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
  }

  var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');

  if (filter_date_end) {
    url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
  }
  location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
  $('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
  $('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
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
//--></script>
<?php echo $footer; ?>