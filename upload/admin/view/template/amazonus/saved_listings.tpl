<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <div class="box">
        <div class="heading">
            <h1><?php echo $lang_title;?></h1>
            <div class="buttons">
                <a class="button" onclick="location = '<?php echo $link_overview; ?>';" ><span><?php echo $lang_btn_return; ?></span></a>
            </div>
        </div>

        <div class="content">
            <table class="form" align="left">
                <tr>
                    <td colspan="2"><h2><?php echo $lang_saved_listings; ?></h2>
                        <p><?php echo $lang_description; ?></p>
                        <div class="buttons">
                            <a id="upload_button" onclick="upload()" class="button"><span><?php echo $lang_btn_upload; ?></span></a>
                        </div>
                    </td>
                </tr>
            </table>
            <table class="list" align="left">
            <thead>
                <tr>
                    <td width="22.5%"><?php echo $lang_name_column ;?></td>
                    <td width="22.5%"><?php echo $lang_model_column ;?></td>
                    <td width="22.5%"><?php echo $lang_sku_column ;?></td>
                    <td width="22.5%"><?php echo $lang_amazonus_sku_column ;?></td>
                    <td class="center" width="10%"><?php echo $lang_actions_column ;?></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($saved_products as $saved_product) : ?>
                <tr>
                    <td class="left"><?php echo $saved_product['product_name']; ?></td>
                    <td class="left"><?php echo $saved_product['product_model']; ?></td>
                    <td class="left"><?php echo $saved_product['product_sku']; ?></td>
                    <td class="left"><?php echo $saved_product['amazonus_sku']; ?></td>
                    <td class="center">
                        <a href="<?php echo $saved_product['edit_link']; ?>" >[<?php echo $lang_actions_edit; ?>]</a> <a onclick="removeSaved('<?php echo $saved_product['product_id']; ?>', '<?php echo $saved_product['var']; ?>')">[<?php echo $lang_actions_remove; ?>]</a>
                    </td>
                </tr>

                <?php endforeach; ?>
            </tbody>
        </table>  

        </div>
    </div>
</div>
<script type="text/javascript">
function removeSaved(id, optionVar) {
    if(!confirm("<?php echo $lang_delete_confirm; ?>")) {
        return;
    }
    $.ajax({
        url: '<?php echo html_entity_decode($deleteSavedAjax); ?>',
        type: 'get',
        data: 'product_id=' + id + '&var=' + optionVar,
        success: function() {
                window.location.href=window.location.href;
        },
        error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
function upload() {
    $.ajax({
        url: '<?php echo html_entity_decode($uploadSavedAjax); ?>',
        dataType: 'json',
        beforeSend: function() {
            $('#upload_button').after('<span class="wait"><img src="view/image/loading.gif" alt="" />&nbsp;<b>Uploading.. Please wait</b></span>');
            $('#save_button').hide();
            $('#cancel_button').hide();
            $('#upload_button').hide();
        },
        complete: function() {
            $('.wait').remove();
            $('#save_button').show();
            $('#cancel_button').show();
            $('#upload_button').show();
        },	
        success: function(data) {
            if(data == null) {
                alert('Error. No response from amazonus/product/uploadSaved.');
                return;
            } else if(data['status'] == 'ok') {
                alert('<?php echo $lang_uploaded_alert; ?>');
            } else if(data['error_message'] !== undefined){
                alert(data['error_message']);
                return;
            } else {
                alert('Unknown error.');
                return;
            }
            window.location.href='<?php echo html_entity_decode($link_overview); ?>';
        },
        error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
</script>
<?php echo $footer; ?>