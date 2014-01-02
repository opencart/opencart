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
            
                <?php if($imgImport > 0){ ?>
                    <div class="warning">
                        <?php echo $imgImport; ?> <?php echo $lang_import_images_msg1; ?> <a href="<?php echo $imgImportLink; ?>" target="_blank"><?php echo $lang_import_images_msg2; ?></a> <?php echo $lang_import_images_msg3; ?>
                    </div>
                <?php } ?>

                <?php if($this->config->get('config_maintenance') == 1){ ?>
                    <div class="warning"><?php echo $lang_maintenance_fail; ?></div>
                <?php } ?>
            
                <p><?php echo $lang_sync_import_line1; ?></p>
                <p><?php echo $lang_sync_import_line2; ?></p>
                <p><?php echo $lang_sync_import_line3; ?></p>

                <div class="attention"><?php echo $lang_sync_server_size; ?><strong><?php echo ini_get('post_max_size'); ?></strong></div>
                <div class="attention"><?php echo $lang_sync_memory_size; ?><strong><?php echo ini_get('memory_limit'); ?></strong></div>

                <table width="100%" cellspacing="0" cellpadding="2" border="0" class="adminlist">
                    <tr class="row0">
                        <td width="230" height="50" valign="middle"><label for="importDescription"><?php echo $lang_sync_item_description; ?></label></td>
                        <td><input type="checkbox" name="importDescription" id="importDescription" value="1"/></td>
                    </tr>
                    <tr class="row0">
                        <td width="230" height="50" valign="middle"><label for="import_notes"><?php echo $lang_sync_notes_location; ?></label></td>
                        <td><input type="checkbox" name="import_notes" id="import_notes" value="1"/></td>
                    </tr>
                    <tr class="row0">
                        <td width="230" height="50" valign="middle"><label><?php echo $lang_import_ebay_items; ?></td>
                        <td><a onclick="importItems();" class="button" id="importItems"><span><?php echo $lang_import; ?></span></a><img src="view/image/loading.gif" id="imageLoadingImportItems" class="displayNone" alt="Loading" /></td>
                    </tr>
                </table>
            <?php }else{ ?>
                <div class="warning"><?php echo $lang_error_validation; ?></div>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    function importItems(){
        var answer = confirm("<?php echo $lang_ajax_import_confirm;?>");

        if (answer){
            var descImport = $('#importDescription:checked').val();
            var noteImport = $('#import_notes:checked').val();
            if(descImport == undefined){ descImport = 0; }else{ descImport = 1; }
            if(noteImport == undefined){ noteImport = 0; }else{ noteImport = 1; }

            $.ajax({
                url: 'index.php?route=openbay/openbay/importItems&token=<?php echo $token; ?>&desc='+descImport+'&note='+noteImport,
                beforeSend: function(){
                    $('#importItems').hide(); 
                    $('#imageLoadingImportItems').show();
                },
                type: 'post',
                dataType: 'json',
                success: function(json) {
                    $('#importItems').show(); $('#imageLoadingImportItems').hide();
                    alert('<?php echo $lang_ajax_import_notify; ?>');
                },
                failure: function(){
                    $('#imageLoadingImportItems').hide();
                    $('#importItems').show();
                    alert('<?php echo $lang_ajax_load_error; ?>');
                },
                error: function(){
                    $('#imageLoadingImportItems').hide();
                    $('#importItems').show();
                    alert('<?php echo $lang_ajax_load_error; ?>');
                }
            });
        }else{
            return 0;
        }
    }
//--></script>
<?php echo $footer; ?>