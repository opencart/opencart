<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-bar-chart"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      
      <div class="row-fluid">
        <div class="span3">
        <?php echo $entry_date_start; ?>
        <input type="date" name="filter_date_start" value="<?php echo $filter_date_start; ?>" class="input-medium" />
        </div>
        
        <div class="span3"><?php echo $entry_date_end; ?>
            <input type="date" name="filter_date_end" value="<?php echo $filter_date_end; ?>" class="input-medium" /></div>
        <div class="span3"><button type="button" id="button-filter" class="btn"><i class="icon-search"></i> <?php echo $button_filter; ?></button></div>
        
      </div>
      

      

      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <td class="left"><?php echo $column_affiliate; ?></td>
            <td class="left"><?php echo $column_email; ?></td>
            <td class="left"><?php echo $column_status; ?></td>
            <td class="right"><?php echo $column_commission; ?></td>
            <td class="right"><?php echo $column_orders; ?></td>
            <td class="right"><?php echo $column_total; ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($affiliates) { ?>
          <?php foreach ($affiliates as $affiliate) { ?>
          <tr>
            <td class="left"><?php echo $affiliate['affiliate']; ?></td>
            <td class="left"><?php echo $affiliate['email']; ?></td>
            <td class="left"><?php echo $affiliate['status']; ?></td>
            <td class="right"><?php echo $affiliate['commission']; ?></td>
            <td class="right"><?php echo $affiliate['orders']; ?></td>
            <td class="right"><?php echo $affiliate['total']; ?></td>
            <td class="right"><?php foreach ($affiliate['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
      <div class="results"><?php echo $results; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/affiliate_commission&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	location = url;
});
//--></script> 
<?php echo $footer; ?>