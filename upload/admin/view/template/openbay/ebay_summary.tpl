<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
<?php 
        foreach ($breadcrumbs as $breadcrumb) {
            echo $breadcrumb['separator'].'<a href="'.$breadcrumb['href'].'">'.$breadcrumb['text'].'</a>';
        } 
?>
    </div>

    <div class="box mBottom130"> 
        <div class="heading">
            <h1><?php echo $lang_heading; ?></h1>
            <div class="buttons">
                <?php if($validation == true) { ?>
                    <a onclick="loadSummary();" class="button" id="loadUsage"><span><?php echo $lang_load; ?></span></a>
                    <img src="view/image/loading.gif" id="imageLoadUsage" class="displayNone" alt="Loading"/>
                <?php } ?>
                <a onclick="location = '<?php echo $return; ?>';" class="button"><span><?php echo $lang_btn_return; ?></span></a>
            </div>
        </div>
        <div class="content">
        <?php if($validation == true) { ?>
            <p><?php echo $lang_use_desc; ?></p>

            <h2 class="mTop30 displayNone"><?php echo $lang_limits_heading; ?></h2>
            <div id="sellingLimits" class="attention displayNone"></div>

            <div id="dsrContainer">
                <h2><?php echo $lang_title_dsr; ?></h2>
                <table class="left border width390" id="dsrTable30">
                    <tr>
                        <th><?php echo $lang_report_30; ?></th>
                        <th class="center"><?php echo $lang_score; ?></th>
                        <th class="center"><?php echo $lang_count; ?></th>
                    </tr>
                </table>
                <table class="left border mLeft20 width390" id="dsrTable52">
                    <tr>
                        <th><?php echo $lang_report_52; ?></th>
                        <th class="center"><?php echo $lang_score; ?></th>
                        <th class="center"><?php echo $lang_count; ?></th>
                    </tr>
                </table>
            </div>

        <?php }else{ ?>
            <div class="warning"><?php echo $lang_error_validation; ?></div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript"><!--
    function loadSummary(){
	$.ajax({ 
            url: 'index.php?route=openbay/openbay/getSellerSummary&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            beforeSend: function(){
                $('#loadUsage').hide();
                $('#imageLoadUsage').show();
                $('#sellingLimits').empty().hide();
                $('#dsrContainer').hide();
                $('.ajaxData').remove();
            },
            success: function(json) {
                $('#loadUsage').show();
                $('#imageLoadUsage').hide();

                if(json.data.summary.QuantityLimitRemaining != ''){
                    var limitHtml = '';

                    limitHtml += '<p><span class="bold"><?php echo $lang_ebay_limit_head; ?></span></p>';
                    limitHtml += '<p><?php echo $lang_ebay_limit_t1; ?> <span class="bold underline">'+json.data.summary.QuantityLimitRemaining+'</span> <?php echo $lang_ebay_limit_t2; ?> <span class="underline bold">'+json.data.summary.AmountLimitRemaining+'</span></p>';
                    limitHtml += '<p><?php echo $lang_ebay_limit_t3; ?></p>';

                    $('#sellingLimits').html(limitHtml).show();
                }

                if(json.data.dsr_feedback.AverageRatingSummary){
                    $.each(json.data.dsr_feedback.AverageRatingSummary, function(key,val){

                        htmlInj = '';

                        $.each(val.AverageRatingDetails, function(key2,val2){
                            if(val2.RatingDetail == 'ItemAsDescribed') {
                                htmlInj += '<tr class="ajaxData"><td><?php echo $lang_as_described; ?></td><td class="center">'+val2.Rating+'</td><td class="center">'+val2.RatingCount+'</td></tr>';
                            }
                            if(val2.RatingDetail == 'Communication') {
                                htmlInj += '<tr class="ajaxData"><td><?php echo $lang_communication; ?></td><td class="center">'+val2.Rating+'</td><td class="center">'+val2.RatingCount+'</td></tr>';
                            }
                            if(val2.RatingDetail == 'ShippingTime') {
                                htmlInj += '<tr class="ajaxData"><td><?php echo $lang_shippingtime; ?></td><td class="center">'+val2.Rating+'</td><td class="center">'+val2.RatingCount+'</td></tr>';
                            }
                            if(val2.RatingDetail == 'ShippingAndHandlingCharges') {
                                htmlInj += '<tr class="ajaxData"><td><?php echo $lang_shipping_charge; ?></td><td class="center">'+val2.Rating+'</td><td class="center">'+val2.RatingCount+'</td></tr>';
                            }
                        });

                        if(val.FeedbackSummaryPeriod == 'FiftyTwoWeeks') {
                            $('#dsrTable52').append(htmlInj).show();
                            $('#dsrContainer').show();
                        }
                        if(val.FeedbackSummaryPeriod == 'ThirtyDays') {
                            $('#dsrTable30').append(htmlInj).show();
                            $('#dsrContainer').show();
                        }
                    });
                }
                
                if(json.lasterror == true){
                    alert(json.lastmsg);
                }
            },
            failure: function(){
                $('#imageLoadUsage').hide();
                $('#loadUsage').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            },
            error: function(){
                $('#imageLoadUsage').hide();
                $('#loadUsage').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            }
	});
    }
//--></script> 

<?php if($validation == true) { ?>
    <script type="text/javascript"><!--
        $(document).ready(function() { loadSummary(); });
    //--></script>
<?php } ?>

<?php echo $footer; ?>