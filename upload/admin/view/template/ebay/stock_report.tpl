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
                <a onclick="location = '<?php echo $return; ?>';" class="button"><span><?php echo $lang_btn_return; ?></span></a>
            </div>
        </div>
        <div class="content">
            <?php if($validation === true) { ?>
                <table  width="100%" cellspacing="0" cellpadding="2" border="0" class="adminlist">
                    <tr class="row0">
                        <td width="230" height="50" valign="middle"><label for=""><?php echo $lang_report_label; ?></td>
                        <td><a onclick="createStockReport();" class="button" id="createStockReport"><span><?php echo $lang_report_btn; ?></span></a><img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="imageLoadingStockReport" class="displayNone"/></td>
                    </tr>
                </table>
            <?php }else{ ?>
                <div class="warning"><?php echo $lang_error_validation; ?></div>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    function createStockReport(){
	$.ajax({
            url: 'index.php?route=openbay/openbay/getStockCheck&token=<?php echo $token; ?>',
            beforeSend: function(){
                $('#createStockReport').hide(); 
                $('#imageLoadingStockReport').show();
            },
            type: 'post',
            dataType: 'json',
            success: function(json) {
                $('#createStockReport').show(); $('#imageLoadingStockReport').hide();

                if(json.error == 'false'){
                    alert('<?php echo $lang_ajax_load_sent; ?>');
                }else{
                    alert(json.msg);
                }
            },
            failure: function(){
                $('#imageLoadingStockReport').hide();
                $('#createStockReport').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            },
            error: function(){
                $('#imageLoadingStockReport').hide();
                $('#createStockReport').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            }
	});
    }
//--></script> 
<?php echo $footer; ?>
