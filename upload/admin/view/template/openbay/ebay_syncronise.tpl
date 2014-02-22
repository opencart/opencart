<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="box mBottom130"> 
        <div class="heading">
            <h1><?php echo $lang_heading; ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $return; ?>';" class="button"><span><?php echo $lang_btn_return; ?></span></a>
            </div>
        </div>
        <div class="content">
        <?php if($validation == true) { ?>
            <h2><?php echo $lang_legend_ebay_sync; ?></h2>
            <p><?php echo $lang_sync_desc; ?></p>

            <table class="form">
                <tr>
                    <td valign="middle"><label for="syncCats"><?php echo $lang_sync_cats_lbl; ?></td>
                    <td><a onclick="syncCats();" class="button" id="syncCats"><span><?php echo $lang_sync_btn; ?></span></a><img src="view/image/loading.gif" id="imageLoadingCats" class="displayNone" alt="Loading" /></td>
                </tr>
                <tr>
                    <td valign="middle"><label for="syncShopCats"><?php echo $lang_sync_shop_lbl; ?></td>
                    <td><a onclick="syncShopCats();" class="button" id="syncShopCats"><span><?php echo $lang_sync_btn; ?></span></a><img src="view/image/loading.gif" id="imageLoadingShopCats" class="displayNone" alt="Loading" /></td>
                </tr>
                <tr>
                    <td valign="middle"><label for="loadSettings"><?php echo $lang_sync_setting_lbl; ?></td>
                    <td><a onclick="loadSettings();" class="button" id="loadSettings"><span><?php echo $lang_sync_btn; ?></span></a><img src="view/image/loading.gif" id="imageloadSettings" class="displayNone" alt="Loading" /></td>
                </tr>
            </table>
        <?php }else{ ?>
            <div class="warning"><?php echo $lang_error_validation; ?></div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript"><!--
    function syncCats(){
        $.ajax({
            url: 'index.php?route=openbay/openbay/loadCategories&token=<?php echo $token; ?>',
            beforeSend: function(){
                $('#syncCats').hide();
                $('#imageLoadingCats').show();
                alert('<?php echo $lang_ajax_ebay_categories; ?>');
            },
            type: 'post',
            dataType: 'json',
            success: function(json) {
                $('#syncCats').show(); $('#imageLoadingCats').hide();
                alert(json.msg);
            },
            failure: function(){
                $('#imageLoadingCats').hide();
                $('#syncCats').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            },
            error: function(){
                $('#imageLoadingCats').hide();
                $('#syncCats').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            }
        });
    }

    function loadSettings(){
        $.ajax({
            url: 'index.php?route=openbay/openbay/loadSettings&token=<?php echo $token; ?>',
            beforeSend: function(){
                $('#loadSettings').hide();
                $('#imageloadSettings').show();
            },
            type: 'post',
            dataType: 'json',
            success: function(json) {
                $('#loadSettings').show(); $('#loadSettings').hide();

                if(json.error == false){
                    alert('<?php echo $lang_ajax_setting_import; ?>');
                }else{
                    alert('<?php echo $lang_ajax_setting_import_e; ?>');
                }
                $('#imageloadSettings').hide();
                $('#loadSettings').show();
            },
            failure: function(){
                $('#imageloadSettings').hide();
                $('#loadSettings').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            },
            error: function(){
                $('#imageloadSettings').hide();
                $('#loadSettings').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            }
        });
    }

    function syncShopCats(){
        $.ajax({
            url: 'index.php?route=openbay/openbay/loadSellerStore&token=<?php echo $token; ?>',
                beforeSend: function(){
                    $('#syncShopCats').hide();
                    $('#imageLoadingShopCats').show();
                },
            type: 'post',
            dataType: 'json',
            success: function(json) {
                $('#syncShopCats').show(); $('#imageLoadingShopCats').hide();

                if(json.error == 'false'){
                    alert('<?php echo $lang_ajax_cat_import; ?>');
                }else{
                    alert(json.msg);
                }
            },
            failure: function(){
                $('#imageLoadingShopCats').hide();
                $('#syncShopCats').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            },
            error: function(){
                $('#imageLoadingShopCats').hide();
                $('#syncShopCats').show();
                alert('<?php echo $lang_ajax_load_error; ?>');
            }
        });
    }
//--></script>

<?php echo $footer; ?>