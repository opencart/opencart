<?php echo $header; ?>
<div id="content">

    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <div class="box mBottom130">
        <div class="left"></div>
        <div class="right"></div>
        <div class="heading">
            <h1><?php echo $lang_text_manager; ?></h1>
            <div class="buttons"><a onclick="validateForm(); return false;" class="button"><span><?php echo $lang_btn_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $lang_btn_cancel; ?></span></a></div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs">
                <a href="#tab-updates"><?php echo $lang_btn_update; ?></a>
                <a href="#tab-settings"><?php echo $lang_btn_settings; ?></a>
                <a href="#tab-patch"><?php echo $lang_btn_patch; ?></a>
                <a href="#tab-help"><?php echo $lang_tab_help; ?></a>
            </div>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <div id="tab-updates">
                    <p><?php echo $lang_patch_notes1; ?> <a href="http://shop.openbaypro.com/index.php?route=information/information/changelog" title="OpenBay Pro change log" target="_BLANK"><?php echo $lang_patch_notes2; ?></a></p>
                    <p><?php echo $lang_patch_notes3; ?></p>
                    
                    <table class="form">
                        <tr>
                            <td><?php echo $lang_installed_version; ?>:</td>
                            <td id="openBayVersionText">
                                <?php echo $txt_obp_version; ?>
                            </td>
                            <input type="hidden" name="openbay_version" value="<?php echo $openbay_version;?>" id="openbay_version" />
                            <input type="hidden" name="openbaymanager_show_menu" value="<?php echo $openbaymanager_show_menu;?>" />
                        </tr>
                        <tr>
                            <td><label for="openbay_ftp_username"><?php echo $field_ftp_user; ?></label></td>
                            <td><input class="ftpsetting width250" type="text" name="openbay_ftp_username" id="openbay_ftp_username" maxlength="" value="<?php echo $openbay_ftp_username;?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="openbay_ftp_pw"><?php echo $field_ftp_pw; ?></label></td>
                            <td><input class="ftpsetting width250" type="text" name="openbay_ftp_pw" id="openbay_ftp_pw" maxlength="" value="<?php echo $openbay_ftp_pw;?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="openbay_ftp_server"><?php echo $field_ftp_server_address; ?></label></td>
                            <td><input class="ftpsetting width250" type="text" name="openbay_ftp_server" id="openbay_ftp_server" maxlength="" value="<?php echo $openbay_ftp_server;?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="openbay_ftp_rootpath"><?php echo $field_ftp_root_path; ?><span class="help"><?php echo $field_ftp_root_path_info; ?></span></label></td>
                            <td><input class="ftpsetting width250" type="text" name="openbay_ftp_rootpath" id="openbay_ftp_rootpath" maxlength="" value="<?php echo $openbay_ftp_rootpath;?>" /></td>
                        </tr>
                        </tr>
                        <tr>
                            <td><label for="openbay_admin_directory"><?php echo $lang_admin_dir; ?><span class="help"><?php echo $lang_admin_dir_desc; ?></span></label></td>
                            <td><input class="ftpsetting width250" type="text" name="openbay_admin_directory" id="openbay_admin_directory" maxlength="" value="<?php echo $openbay_admin_directory;?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="openbay_ftp_pasv"><?php echo $lang_use_pasv; ?></label></td>
                            <td>
                                <input class="ftpsetting" type="hidden" name="openbay_ftp_pasv" value="0" />
                                <input class="ftpsetting" type="checkbox" name="openbay_ftp_pasv" id="openbay_ftp_pasv" value="1" <?php if(isset($openbay_ftp_pasv) && $openbay_ftp_pasv == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="openbay_ftp_beta"><?php echo $lang_use_beta; ?><span class="help"><?php echo $lang_use_beta_2; ?></span></label></td>
                            <td>
                                <input class="ftpsetting" type="hidden" name="openbay_ftp_beta" value="0" />
                                <input class="ftpsetting" type="checkbox" name="openbay_ftp_beta" id="openbay_ftp_beta" value="1" <?php if(isset($openbay_ftp_beta) && $openbay_ftp_beta == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr id="ftpTestRow">
                            <td height="50" valign="middle"><?php echo $lang_test_conn; ?></td>
                            <td>
                                <a onclick="ftpTest();" class="button" id="ftpTest"><span><?php echo $lang_btn_test; ?></span></a>
                                <img src="view/image/loading.gif" id="imageFtpTest" class="displayNone" alt="Loading" />
                            </td>
                        </tr>
                        <tr id="ftpUpdateRow" class="displayNone">
                            <td height="50" valign="middle"><?php echo $lang_text_run_1; ?></td>
                            <td><span id="preFtpTestText"><?php echo $lang_text_run_2; ?></span>
                                <a onclick="updateModule();" class="button displayNone" id="moduleUpdate"><span><?php echo $lang_btn_update; ?></span></a>
                                <img src="view/image/loading.gif" id="imageModuleUpdate" class="displayNone" alt="Loading" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" id="updateBox"></td>
                        </tr>
                    </table>
                </div>

                <div id="tab-settings">
                    <table class="form">
                        <tr>
                            <td ><?php echo $lang_language; ?></td>
                            <td>
                                <select name="openbay_language">
                                    <?php foreach($languages as $key => $language){ ?>
                                        <option value="<?php echo $key; ?>" <?php if($key == $openbay_language){ echo'selected="selected"'; } ?>><?php echo $language; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle"><label for=""><?php echo $lang_clearfaq; ?></td>
                            <td><a onclick="clearFaq();" class="button" id="clearFaq"><span><?php echo $lang_clearfaqbtn; ?></span></a><img src="view/image/loading.gif" id="imageClearFaq" class="displayNone" alt="Loading" /></td>
                        </tr>
                    </table>
                </div>

                <div id="tab-patch">
                    <table class="form">
                        <tr>
                            <td ><?php echo $lang_run_patch_desc; ?></td>
                            <td><a onclick="runPatch();" class="button" id="runPatch"><span><?php echo $lang_run_patch; ?></span></a><img src="view/image/loading.gif" id="imageRunPatch" class="displayNone" alt="Loading" /></td>
                        </tr>
                    </table>
                </div>

                <div id="tab-help">
                    <h2><?php echo $lang_help_title; ?></h2>
                    <table class="form">
                        <tr>
                            <td class="p10"><?php echo $lang_help_support_title; ?></td>
                            <td class="p10"><?php echo $lang_help_support_description; ?></td>
                        </tr>
                        <tr>
                            <td class="p10"><?php echo $lang_help_template_title; ?></td>
                            <td class="p10"><?php echo $lang_help_template_description; ?></td>
                        </tr>
                        <tr>
                            <td class="p10"><?php echo $lang_help_guide; ?></td>
                            <td class="p10"><?php echo $lang_help_guide_description; ?></td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    var token = "<?php echo $_GET['token']; ?>";
    
    $('.ftpsetting').keypress(function(){
        $('#preFtpTestText').show();
        $('#moduleUpdate').hide();
        $('#ftpTestRow').show();
        $('#ftpUpdateRow').hide();
    });

    function ftpTest(){
        $.ajax({
            url: 'index.php?route=extension/openbay/ftpTestConnection&token='+token,
            type: 'post',
            data: $('.ftpsetting').serialize(),
            dataType: 'json',
            beforeSend: function(){
                $('#ftpTest').hide();
                $('#imageFtpTest').show();
            },
            success: function(json) {
                alert(json.msg);

                if(json.connection == true){
                    $('#preFtpTestText').hide();
                    $('#moduleUpdate').show();
                    $('#ftpTestRow').hide();
                    $('#ftpUpdateRow').show();
                }

                $('#ftpTest').show();
                $('#imageFtpTest').hide();
            }
        });
    }

    function runPatch(){
        $.ajax({
            url: 'index.php?route=extension/openbay/runPatch&token='+token,
            type: 'post',
            dataType: 'json',
            beforeSend: function(){
                $('#runPatch').hide();
                $('#imageRunPatch').show();
            },
            success: function() {
                alert('<?php echo $lang_patch_applied; ?>');
                $('#runPatch').show();
                $('#imageRunPatch').hide();
            }
        });
    }

    function updateModule(){
        $.ajax({
            url: 'index.php?route=extension/openbay/ftpUpdateModule&token='+token,
            type: 'post',
            data: $('.ftpsetting').serialize(),
            dataType: 'json',
            beforeSend: function(){ 
                $('#moduleUpdate').hide();
                $('#imageModuleUpdate').show();
            },
            success: function(json) {
                alert(json.msg);
                $('#openBayVersionText').text(json.version);
                $('#openbay_version').val(json.version);
                $('#moduleUpdate').show();
                $('#imageModuleUpdate').hide();
            }
        });
    }
    
    function validateForm(){
        $('#form').submit();
    }

    function clearFaq(){
        $.ajax({
            url: 'index.php?route=extension/openbay/faqClear&token='+token,
            beforeSend: function(){
                $('#clearFaq').hide();
                $('#imageClearFaq').show();
            },
            type: 'post',
            dataType: 'json',
            success: function(json) {
                $('#clearFaq').show(); $('#imageClearFaq').hide();
            },
            failure: function(){
                $('#imageClearFaq').hide();
                $('#clearFaq').show();
            },
            error: function(){
                $('#imageClearFaq').hide();
                $('#clearFaq').show();
            }
        });
    }

    $('#tabs a').tabs();
//--></script>

<?php echo $footer; ?>
