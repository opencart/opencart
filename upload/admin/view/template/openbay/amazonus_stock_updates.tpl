<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?><?php echo $breadcrumb['separator']; ?>
    <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><?php } ?>
  </div>

  <?php if(isset($error)) { ?>
  <div class="warning" style="margin:5px 0px;">
    <?php echo $error; ?>
  </div>
  <?php } ?>

  <div class="box">
    <div class="heading">
      <h1><?php echo $lang_title;?></h1>

      <div class="buttons">
        <a class="button" onclick="location = '<?php echo $link_overview; ?>';"><span><?php echo $lang_btn_return; ?></span></a>
      </div>
    </div>

    <div class="content">

      <table class="form">
        <tbody>
        <tr>
          <td><?php echo $lang_date_start; ?>
            <input type="text" value="<?php echo $date_start; ?>" name="filter_date_start" id="date-start" size="12">
          </td>
          <td><?php echo $lang_date_end; ?>
            <input type="text" value="<?php echo $date_end; ?>" name="filter_date_end" id="date-end" size="12"></td>
          <td style="text-align: right;"><a onclick="filter();" class="button"><?php echo $lang_filter_btn; ?></a></td>
        </tr>
        </tbody>
      </table>

      <table class="list">
        <thead>
        <tr>
          <td class="left"><?php echo $lang_ref; ?></td>
          <td class="left"><?php echo $lang_date_requested; ?></td>
          <td class="right"><?php echo $lang_date_updated; ?></td>
          <td class="right"><?php echo $lang_status; ?></td>
          <td class="left"><?php echo $lang_sku; ?></td>
          <td class="left"><?php echo $lang_stock; ?></td>
        </tr>
        </thead>
        <tbody>
        <?php if(empty($tableData)) { ?>
        <tr>
          <td class="center" colspan="6"><?php echo $lang_empty; ?></td>
        </tr>
        <?php } ?>

        <?php foreach($tableData as $ref => $row) { ?>
        <tr>
          <td class="left" rowspan="<?php echo count($row['data']) + 1; ?>"><?php echo $ref; ?></td>
          <td class="left" rowspan="<?php echo count($row['data']) + 1; ?>"><?php echo $row['date_requested']; ?></td>
          <td class="right" rowspan="<?php echo count($row['data']) + 1; ?>"><?php echo $row['date_updated']; ?></td>
          <td class="right" rowspan="<?php echo count($row['data']) + 1; ?>"><?php echo $row['status']; ?></td>
          <?php foreach($row['data'] as $dataRow) { ?>
        <tr>
          <td class="left"><?php echo $dataRow['sku']; ?></td>
          <td class="left"><?php echo $dataRow['stock']; ?></td>
        </tr>
        <?php } ?></tr><?php } ?>
        </tbody>
      </table>
    </div>
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
$(document).ready(function () {
  $('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
  $('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>