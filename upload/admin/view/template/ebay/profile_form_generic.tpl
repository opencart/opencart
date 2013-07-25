<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning" style="margin-bottom:10px;"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><?php echo $page_title; ?></h1>
            <div class="buttons">
                <a onclick="$('#form').submit();" class="button"><span><?php echo $lang_btn_save; ?></span></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $lang_btn_cancel; ?></span></a>
            </div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs">
                <a href="#tab-general"><?php echo $lang_tab_general; ?></a>
                <a href="#tab-returns"><?php echo $lang_tab_settings; ?></a>
            </div>
            <form action="<?php echo $btn_save; ?>" method="post" enctype="multipart/form-data" id="form">
                <input type="hidden" name="type" value="<?php echo $type; ?>" />
                <input type="hidden" name="ebay_profile_id" value="<?php echo $ebay_profile_id; ?>" />

                <div id="tab-general">

                    <table class="form">
                        <tr>
                            <td><?php echo $lang_profile_default; ?></td>
                            <td>
                                <input type="hidden" name="default" value="0" />
                                <input type="checkbox" name="default" value="1" <?php if($default == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_profile_name; ?></td>
                            <td><input type="text" name="name" size="80" value="<?php if(isset($name)){ echo $name; } ?>"></td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_profile_desc; ?></td>
                            <td>
                                <textarea name="description" cols="40" rows="5"><?php if(isset($description)){ echo $description; } ?></textarea>
                            </td>
                        </tr>
                    </table>

                </div>

                <div id="tab-returns">

                    <table class="form">

                        <tr>
                            <td><label for="private_auction"><?php echo $lang_private_auction; ?></td>
                            <td>
                                <?php if(!isset($data['private_listing'])){ $data['private_listing'] = '0'; } ?>
                                
                                <select name="data[private_listing]" class="width250">
                                    <option value="0" <?php if($data['private_listing'] == '0'){ echo'selected'; } ?>><?php echo $lang_no; ?></option>
                                    <option value="1" <?php if($data['private_listing'] == '1'){ echo'selected'; } ?>><?php echo $lang_yes; ?></option>
                                </select>
                            </td>
                        </tr>

                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
<?php echo $footer; ?>