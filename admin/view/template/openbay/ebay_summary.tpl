<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_summary; ?></h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-md-12">
            <p><?php echo $text_use_desc; ?></p>
            <div id="selling-limits" class="alert alert-warning" style="display:none;"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="panel panel-default dsr-table">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-lg"></i> <?php echo $text_report_30; ?></h3>
              </div>
              <div class="panel-body">
                <table class="table" id="dsr-table-30">
                  <thead>
                  <tr>
                    <th></th>
                    <th class="text-center"><?php echo $text_score; ?></th>
                    <th class="text-center"><?php echo $text_count; ?></th>
                  </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-default dsr-table">
              <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-lg"></i> <?php echo $text_report_52; ?></h3>
              </div>
              <div class="panel-body">
                <table class="table" id="dsr-table-52">
                  <thead>
                  <tr>
                    <th></th>
                    <th class="text-center"><?php echo $text_score; ?></th>
                    <th class="text-center"><?php echo $text_count; ?></th>
                  </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="well">
          <div class="row">
            <div class="col-sm-12 text-right">
              <a class="btn btn-primary" id="load-usage"><i class="fa fa-cog fa-lg fa-spin"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
  function loadSummary(){
    $.ajax({
      url: 'index.php?route=openbay/ebay/getSellerSummary&token=<?php echo $token; ?>',
      type: 'post',
      dataType: 'json',
      beforeSend: function(){
        $('#load-usage').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
        $('#selling-limits').empty().hide();
        $('.dsr-table').hide();
        $('.data-row').remove();
      },
      success: function(json) {
          $('#load-usage').empty().html('<i class="fa fa-refresh"></i> <?php echo $button_refresh; ?>').removeAttr('disabled');

          if (json.data.summary.QuantityLimitRemaining != ''){
              var limitHtml = '';

              limitHtml += '<p><?php echo $text_ebay_limit_t1; ?> <span class="bold underline">'+json.data.summary.QuantityLimitRemaining+'</span> <?php echo $text_ebay_limit_t2; ?> <span class="underline bold">'+json.data.summary.AmountLimitRemaining+'</span></p>';
              limitHtml += '<p><?php echo $text_ebay_limit_t3; ?></p>';

              $('#selling-limits').html(limitHtml).show();
          }

          if (json.data.dsr_feedback.AverageRatingSummary){
              $.each(json.data.dsr_feedback.AverageRatingSummary, function(key,val){

                htmlInj = '';

                $.each(val.AverageRatingDetails, function(key2,val2){
                    if (val2.RatingDetail == 'ItemAsDescribed') {
                        htmlInj += '<tr class="data-row"><td class="text-left"><?php echo $text_as_described; ?></td><td class="text-center">'+val2.Rating+'</td><td class="text-center">'+val2.RatingCount+'</td></tr>';
                    }
                    if (val2.RatingDetail == 'Communication') {
                        htmlInj += '<tr class="data-row"><td class="text-left"><?php echo $text_communication; ?></td><td class="text-center">'+val2.Rating+'</td><td class="text-center">'+val2.RatingCount+'</td></tr>';
                    }
                    if (val2.RatingDetail == 'ShippingTime') {
                        htmlInj += '<tr class="data-row"><td class="text-left"><?php echo $text_shippingtime; ?></td><td class="text-center">'+val2.Rating+'</td><td class="text-center">'+val2.RatingCount+'</td></tr>';
                    }
                    if (val2.RatingDetail == 'ShippingAndHandlingCharges') {
                        htmlInj += '<tr class="data-row"><td class="text-left"><?php echo $text_shipping_charge; ?></td><td class="text-center">'+val2.Rating+'</td><td class="text-center">'+val2.RatingCount+'</td></tr>';
                    }
                });

                if (val.FeedbackSummaryPeriod == 'FiftyTwoWeeks') {
                    $('#dsr-table-52').append(htmlInj).show();
                }
                if (val.FeedbackSummaryPeriod == 'ThirtyDays') {
                    $('#dsr-table-30').append(htmlInj).show();
                }

                $('.dsr-table').show();
              });
          }

          if (json.lasterror == true){
              alert(json.lastmsg);
          }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#load-usage').empty().removeClass('btn-primary').addClass('btn-danger').html('<?php echo $text_failed; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  $('#load-usage').bind('click', function() {
    loadSummary();
  });

  $(document).ready(function() {
    loadSummary();
  });
//--></script>
<?php echo $footer; ?>