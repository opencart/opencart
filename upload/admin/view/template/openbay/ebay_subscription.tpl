<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_subscription; ?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4">
            <a class="btn btn-primary" id="load-account" disabled="disabled"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_load_my_plan; ?></a>
            <div class="panel panel-default" id="my-plan-container">
              <div class="panel-heading">
                <h1 class="panel-title"><i class="fa fa-user fa-lg"></i> <?php echo $text_subscription_current; ?></h1>
              </div>
              <div class="panel-body">
                <table class="table" id="my-plan"></table>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <a class="btn btn-primary" id="load-plans" disabled="disabled"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_load_plans; ?></a>
            <div class="panel panel-default" id="openbay-plans-container">
              <div class="panel-heading">
                <h1 class="panel-title"><i class="fa fa-list fa-lg"></i> <?php echo $text_subscription_avail; ?></h1>
              </div>
              <div class="panel-body">
                <table id="openbay-plans" class="table"></table>
                <p><?php echo $text_subscription_avail1; ?></p>
                <p><?php echo $text_subscription_avail2; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
  function loadAccount() {
    $.ajax({
      url: 'index.php?route=openbay/ebay/getMyPlan&token=<?php echo $token; ?>',
      type: 'post',
      dataType: 'json',
      beforeSend: function(){
        $('#my-plan-container').hide();
      },
      success: function(json) {
        $('#load-account').hide();
        $('#my-plan').empty();

        htmlInj = '';

        htmlInj += '<thead>';
          htmlInj += '<tr>';
            htmlInj += '<th><?php echo $column_plan; ?></th>';
            htmlInj += '<th><?php echo $column_price; ?></th>';
            htmlInj += '<th><?php echo $column_description; ?></th>';
            htmlInj += '<th></th>';
          htmlInj += '</tr>';
        htmlInj += '</thead>';
        htmlInj += '<tbody>';
          htmlInj += '<tr>';
            htmlInj += '<td>'+json.plan.title+'</td>';
            htmlInj += '<td>&pound;'+json.plan.price+'</td>';
            htmlInj += '<td>'+json.plan.description+'</td>';
            htmlInj += '<td></td>';
          htmlInj += '</tr>';

          if (json.sub_id){
            htmlInj += '<tr>';
            htmlInj += '<td colspan="4"><?php echo $text_ajax_acc_load_plan; ?>'+json.sub_id+'<?php echo $text_ajax_acc_load_plan2; ?></td>';
            htmlInj += '</tr>';
          }

        htmlInj += '</tbody>';

        $('#my-plan').append(htmlInj);
        $('#my-plan-container').show();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  function loadPlans() {
    $.ajax({
      url: 'index.php?route=openbay/ebay/getPlans&token=<?php echo $token; ?>',
      type: 'post',
      dataType: 'json',
      beforeSend: function(){
        $('#openbay-plans-container').hide();
      },
      success: function(json) {
        $('#load-plans').hide();
        $('#openbay-plans').empty();

        htmlInj = '';
        htmlInj += '<thead>';
          htmlInj += '<tr>';
            htmlInj += '<th><?php echo $column_plan; ?></th>';
            htmlInj += '<th><?php echo $column_price; ?></th>';
            htmlInj += '<th><?php echo $column_description; ?></th>';
            htmlInj += '<th></td>';
          htmlInj += '</tr>';
        htmlInj += '</thead>';
        htmlInj += '<tbody>';
        $.each(json.plans, function(key,val){
          htmlInj += '<tr>';
          htmlInj += '<td>'+val.title+'</td>';
          htmlInj += '<td>&pound;'+val.price+'</td>';
          htmlInj += '<td>'+val.description+'</td>';
          if (val.myplan == 1){
            htmlInj += '<td><a class="btn btn-success" disabled="disabled"><i class="fa fa-check-circle-o fa-lg"></i> <?php echo $column_current; ?></a></td>';
          }else{
            if (val.user_plan_id == 1) {
              htmlInj += '<td></td>';
            }else{
              htmlInj += '<td>';
              htmlInj += '<a href="https://uk.openbaypro.com/account/live/subscription_setup.php?plan_id='+val.user_plan_id+'&subscriber_id=<?php echo $obp_token;?>" class="btn btn-primary" target="_BLANK"><i class="fa fa-arrow-right fa-lg"></i> <?php echo $button_plan_change; ?></a>';
              htmlInj += '</td>';
            }
          }
          htmlInj += '</tr>';
        });
        htmlInj += '<tbody>';

        $('#openbay-plans').append(htmlInj);
        $('#openbay-plans-container').show();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  }

  $(document).ready(function() {
    loadAccount();
    loadPlans();
  });
//--></script>
<?php echo $footer; ?>