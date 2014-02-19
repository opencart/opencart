<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
<?php 
        foreach ($breadcrumbs as $breadcrumb) {
            echo $breadcrumb['separator'] .'<a href="'.$breadcrumb['href'].'">'.$breadcrumb['text'].'</a>';
        } 
?>
    </div>

    <div class="box mBottom130"> 
        <div class="heading">
            <h1><?php echo $lang_heading; ?></h1>
            <div class="buttons">
                <?php if($validation == true) { ?>
                    <a onclick="loadAccount(); loadUsage();" class="button" id="loadAccount"><span><?php echo $lang_load; ?></span></a>
                    <img src="view/image/loading.gif" class="imageLoadAccount" class="displayNone" alt="Loading" />
                <?php } ?>
                <a onclick="location = '<?php echo $return; ?>';" class="button"><span><?php echo $lang_btn_return; ?></span></a>
            </div>
        </div>
        <div class="content">
        <?php if($validation == true) { ?>
            <h2><?php echo $lang_usage_title; ?> <img src="view/image/loading.gif" id="imageLoadUsage" class="displayNone" alt="Loading" /></h2>
            <div id="usageTable" class="displayNone"></div>
            
            <h2 class="mTop10"><?php echo $lang_subscription_current; ?> <img src="view/image/loading.gif" class="imageLoadAccount" class="displayNone" alt="Loading" /></h2>
            <table width="100%" cellspacing="0" cellpadding="5" border="0" id="myopenbayplan" class="displayNone border borderNoBottom"></table>
            
            <h2 class="mTop10"><?php echo $lang_subscription_avail; ?> <img src="view/image/loading.gif" class="imageLoadAccount" class="displayNone" alt="Loading" /></h2>
            <p><?php echo $lang_subscription_avail1; ?></p>
            <p><?php echo $lang_subscription_avail2; ?></p>
            
            <table width="100%" cellspacing="0" cellpadding="5" border="0" id="openbayplans" class="displayNone border borderNoBottom"></table>

        <?php }else{ ?>
            <div class="warning"><?php echo $lang_error_validation; ?></div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript"><!--
    function loadAccount(){
	    $.ajax({
        url: 'index.php?route=openbay/openbay/getMyPlan&token=<?php echo $token; ?>',
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
                    htmlInj += '<td colspan="4" class="bold borderBottom" style="background-color: #EAF7D9; height:40px;line-height:40px;"><?php echo $lang_ajax_acc_load_plan; ?>'+json.sub_id+'<?php echo $lang_ajax_acc_load_plan2; ?></td>';
                htmlInj += '</tr>';
            }

            htmlInj += '<tr>';
                htmlInj += '<td width="120" class="bold borderBottom"><?php echo $lang_ajax_acc_load_text1; ?></td>';
                htmlInj += '<td width="120" class="bold borderBottom"><?php echo $lang_ajax_acc_load_text3; ?></td>';
                htmlInj += '<td width="300" class="bold borderBottom"><?php echo $lang_ajax_acc_load_text4; ?></td>';
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
        url: 'index.php?route=openbay/openbay/getPlans&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
            $('#loadAccount').hide();
            $('.imageLoadAccount').show();
            $('#openbayplans').hide();
        },
        success: function(json) {
            $('#loadAccount').show();
            $('.imageLoadAccount').hide();
            $('#openbayplans').empty().show();

            htmlInj = '';
            htmlInj += '<tr>';
                htmlInj += '<td width="120" class="bold borderBottom"><?php echo $lang_ajax_acc_load_text1; ?></td>';
                htmlInj += '<td width="120" class="bold borderBottom"><?php echo $lang_ajax_acc_load_text3; ?></td>';
                htmlInj += '<td width="300" class="bold borderBottom"><?php echo $lang_ajax_acc_load_text4; ?></td>';
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
                        htmlInj += '<td class="borderBottom"><?php echo $lang_ajax_acc_load_text5; ?></td>';
                    }else{
                        if(val.user_plan_id == 1)
                        {
                            htmlInj += '<td class="borderBottom"></td>';
                        }else{
                            htmlInj += '<td class="borderBottom">';
                                htmlInj += '<a href="https://uk.openbaypro.com/account/live/subscription_setup.php?plan_id='+val.user_plan_id+'&subscriber_id=<?php echo $obp_token;?>" class="button" target="_BLANK"><span><?php echo $lang_ajax_acc_load_text6; ?></span></a>';
                            htmlInj += '</td>';
                        }
                    }
                htmlInj += '</tr>';

                $('#openbayplans').append(htmlInj);
            });
        },
        failure: function(){
            $('.imageLoadAccount').hide();
            $('#loadAccount').show();
            alert('<?php echo $lang_ajax_load_error; ?>');
        },
        error: function(){
            $('.imageLoadAccount').hide();
            $('#loadAccount').show();
            alert('<?php echo $lang_ajax_load_error; ?>');
        }
      });
    }
    
    function loadUsage(){
	    $.ajax({
        url: 'index.php?route=openbay/openbay/getUsage&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
            $('#usageTable').hide();
            $('#imageLoadUsage').show();
        },
        success: function(json) {
            $('#imageLoadUsage').hide();
            $('#usageTable').html(json.html).show();
            if(json.lasterror){ alert(json.lastmsg); }
        },
        failure: function(){
            $('#imageLoadUsage').hide();
            $('#usageTable').hide();
            alert('<?php echo $lang_ajax_load_error; ?>');
        },
        error: function(){
            $('#imageLoadUsage').hide();
            $('#usageTable').hide();
            alert('<?php echo $lang_ajax_load_error; ?>');
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
