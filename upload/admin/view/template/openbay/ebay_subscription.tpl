<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a data-toggle="tooltip" title="<?php echo $text_load; ?>" class="btn" onclick="loadAccount(); loadUsage();"><i class="fa fa-refresh"></i></a>
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $text_btn_return; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $text_heading; ?></h1>
    </div>
    <div class="panel-body">



        <?php if($validation == true) { ?>
            <h4><?php echo $text_usage_title; ?> <div class="btn btn-primary" id="load_usage_loading"><i class="fa fa-refresh fa-spin"></i></div></h4>
            <div id="usageTable" class="displayNone"></div>

            <h4><?php echo $text_subscription_current; ?> <div class="btn btn-primary load_account_loading" style="display:none;"><i class="fa fa-refresh fa-spin"></i></div></h4>
            <table width="100%" cellspacing="0" cellpadding="5" border="0" id="myopenbayplan" class="displayNone border borderNoBottom"></table>

            <h4><?php echo $text_subscription_avail; ?> <div class="btn btn-primary load_account_loading" style="display:none;"><i class="fa fa-refresh fa-spin"></i></div></h4>
            <p><?php echo $text_subscription_avail1; ?></p>
            <p><?php echo $text_subscription_avail2; ?></p>

            <table width="100%" cellspacing="0" cellpadding="5" border="0" id="openbayplans" class="displayNone border borderNoBottom"></table>

        <?php }else{ ?>
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_error_validation; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript"><!--
    function loadAccount(){
	    $.ajax({
        url: 'index.php?route=openbay/ebay/getMyPlan&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
            $('#myopenbayplan').hide();
        },
        success: function(json) {
            $('#myopenbayplan').empty().show();

            htmlInj = '';

            if(json.sub_id){
                htmlInj += '<tr>';
                    htmlInj += '<td colspan="4" class="bold borderBottom" style="background-color: #EAF7D9; height:40px;line-height:40px;"><?php echo $text_ajax_acc_load_plan; ?>'+json.sub_id+'<?php echo $text_ajax_acc_load_plan2; ?></td>';
                htmlInj += '</tr>';
            }

            htmlInj += '<tr>';
                htmlInj += '<td width="120" class="bold borderBottom"><?php echo $text_ajax_acc_load_text1; ?></td>';
                htmlInj += '<td width="120" class="bold borderBottom"><?php echo $text_ajax_acc_load_text3; ?></td>';
                htmlInj += '<td width="300" class="bold borderBottom"><?php echo $text_ajax_acc_load_text4; ?></td>';
                htmlInj += '<td width="150" class="bold borderBottom"></td>';
            htmlInj += '</tr>';

            $('#myopenbayplan').append(htmlInj);

            htmlInj = '';
            htmlInj += '<tr>';
                htmlInj += '<td class="borderBottom">'+json.plan.title+'</td>';
                htmlInj += '<td class="borderBottom">&pound;'+json.plan.price+'</td>';
                htmlInj += '<td class="borderBottom">'+json.plan.description+'</td>';
                htmlInj += '<td class="borderBottom"></td>';
            htmlInj += '</tr>';

            $('#myopenbayplan').append(htmlInj);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	    });

      $.ajax({
        url: 'index.php?route=openbay/ebay/getPlans&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
            $('#loadAccount').hide();
            $('.load_account_loading').show();
            $('#openbayplans').hide();
        },
        success: function(json) {
            $('#loadAccount').show();
            $('.load_account_loading').hide();
            $('#openbayplans').empty().show();

            htmlInj = '';
            htmlInj += '<tr>';
                htmlInj += '<td width="120" class="bold borderBottom"><?php echo $text_ajax_acc_load_text1; ?></td>';
                htmlInj += '<td width="120" class="bold borderBottom"><?php echo $text_ajax_acc_load_text3; ?></td>';
                htmlInj += '<td width="300" class="bold borderBottom"><?php echo $text_ajax_acc_load_text4; ?></td>';
                htmlInj += '<td width="150" class="bold borderBottom"></td>';
            htmlInj += '</tr>';

            $('#openbayplans').append(htmlInj);

            $.each(json.plans, function(key,val){

                htmlInj = '';
                htmlInj += '<tr>';
                    htmlInj += '<td class="borderBottom">'+val.title+'</td>';
                    htmlInj += '<td class="borderBottom">&pound;'+val.price+'</td>';
                    htmlInj += '<td class="borderBottom">'+val.description+'</td>';
                    if(val.myplan == 1){
                        htmlInj += '<td class="borderBottom"><?php echo $text_ajax_acc_load_text5; ?></td>';
                    }else{
                        if(val.user_plan_id == 1)
                        {
                            htmlInj += '<td class="borderBottom"></td>';
                        }else{
                            htmlInj += '<td class="borderBottom">';
                                htmlInj += '<a href="https://uk.openbaypro.com/account/live/subscription_setup.php?plan_id='+val.user_plan_id+'&subscriber_id=<?php echo $obp_token;?>" class="button" target="_BLANK"><span><?php echo $text_ajax_acc_load_text6; ?></span></a>';
                            htmlInj += '</td>';
                        }
                    }
                htmlInj += '</tr>';

                $('#openbayplans').append(htmlInj);
            });
        },
        failure: function(){
            $('.load_account_loading').hide();
            $('#loadAccount').show();
            alert('<?php echo $text_ajax_load_error; ?>');
        },
        error: function(){
            $('.load_account_loading').hide();
            $('#loadAccount').show();
            alert('<?php echo $text_ajax_load_error; ?>');
        }
      });
    }

    function loadUsage(){
	    $.ajax({
        url: 'index.php?route=openbay/ebay/getUsage&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
            $('#usageTable').hide();
            $('#load_usage_loading').show();
        },
        success: function(json) {
            $('#load_usage_loading').hide();
            $('#usageTable').html(json.html).show();
            if(json.lasterror){ alert(json.lastmsg); }
        },
        failure: function(){
            $('#load_usage_loading').hide();
            $('#usageTable').hide();
            alert('<?php echo $text_ajax_load_error; ?>');
        },
        error: function(){
            $('#load_usage_loading').hide();
            $('#usageTable').hide();
            alert('<?php echo $text_ajax_load_error; ?>');
        }
	    });
    }
//--></script>

<?php if($validation == true) { ?>
    <script type="text/javascript"><!--
        $(document).ready(function() { loadAccount(); loadUsage(); });
    //--></script>
<?php } ?>

<?php echo $footer; ?>
