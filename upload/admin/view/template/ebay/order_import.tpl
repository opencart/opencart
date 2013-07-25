<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
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
                        <td width="230" height="50" valign="middle"><label for=""><?php echo $lang_sync_pull_orders; ?></td>
                        <td><a onclick="importOrders();" class="button" id="importOrders"><span><?php echo $lang_sync_pull_orders_text; ?></span></a><img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" id="imageLoadingImportOrders" class="displayNone" /></td>
                    </tr>
                </table>
                <p><?php echo $lang_sync_pull_notice; ?></p>
            <?php }else{ ?>
                <div class="warning"><?php echo $lang_error_validation; ?></div>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    function importOrders(){
        $.ajax({
                url: 'index.php?route=openbay/openbay/importOrdersManual&token=<?php echo $token; ?>',
                beforeSend: function(){
                    $('#importOrders').hide(); 
                    $('#imageLoadingImportOrders').show();
                },
                type: 'post',
                dataType: 'json',
                success: function(json) {
                    $('#importOrders').show(); $('#imageLoadingImportOrders').hide();
                    alert('<?php echo $lang_ajax_orders_import; ?>');
                },
                failure: function(){
                    $('#imageLoadingImportOrders').hide();
                    $('#importOrders').show();

                    alert('<?php echo $lang_ajax_load_error; ?>');
                },
                error: function(){
                    $('#imageLoadingImportOrders').hide();
                    $('#importOrders').show();

                    alert('<?php echo $lang_ajax_load_error; ?>');
                }
        });
    }
//--></script> 
<?php echo $footer; ?>
