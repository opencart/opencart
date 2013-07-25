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
                <a href="#tab-template"><?php echo $lang_tab_template; ?></a>
                <a href="#tab-gallery"><?php echo $lang_tab_gallery; ?></a>
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

                <div id="tab-template">
                    <table class="form">
                        <tr>
                            <td><label><?php echo $lang_template_choose; ?></td>
                            <td>
                                <select name="data[ebay_template_id]" class="width250">
                                    <option value="None">None</option>

                                    <?php foreach($templates as $template){ ?>
                                        <?php echo '<option value="'.$template['template_id'].'"'.(($template['template_id'] == $data['ebay_template_id'])?' selected':'').'>'.$template['name'].'</option>'; ?>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="tab-gallery">
                    <table class="form">
                        <tr>
                            <td><label><?php echo $lang_image_gallery; ?></td>
                            <td>
                                <input type="text" name="data[ebay_gallery_height]" id="ebay_gallery_height" maxlength="4" value="<?php if(isset($data['ebay_gallery_height'])){ echo $data['ebay_gallery_height']; }?>" class="credentials width100" />h&nbsp;
                                <input type="text" name="data[ebay_gallery_width]" id="ebay_gallery_width" maxlength="4" value="<?php if(isset($data['ebay_gallery_width'])){ echo $data['ebay_gallery_width']; }?>" class="credentials width100" />w
                            </td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_image_thumb; ?></td>
                            <td>
                                <input type="text" name="data[ebay_thumb_height]" id="ebay_thumb_height" maxlength="4" value="<?php if(isset($data['ebay_thumb_height'])){ echo $data['ebay_thumb_height']; }?>" class="credentials width100" />h&nbsp;
                                <input type="text" name="data[ebay_thumb_width]" id="ebay_thumb_width" maxlength="4" value="<?php if(isset($data['ebay_thumb_width'])){ echo $data['ebay_thumb_width']; }?>" class="credentials width100" />w
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_image_super; ?></td>
                            <td>
                                <input type="hidden" name="data[ebay_supersize]" value="0" />
                                <input type="checkbox" name="data[ebay_supersize]" value="1" <?php if(isset($data['ebay_supersize']) && $data['ebay_supersize'] == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_image_gallery_plus; ?></td>
                            <td>
                                <input type="hidden" name="data[ebay_gallery_plus]" value="0" />
                                <input type="checkbox" name="data[ebay_gallery_plus]" value="1" <?php if(isset($data['ebay_gallery_plus']) && $data['ebay_gallery_plus'] == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_image_all_ebay; ?></td>
                            <td>
                                <input type="hidden" name="data[ebay_img_ebay]" value="0" />
                                <input type="checkbox" name="data[ebay_img_ebay]" value="1" <?php if(isset($data['ebay_img_ebay']) && $data['ebay_img_ebay'] == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_image_all_template; ?></td>
                            <td>
                                <input type="hidden" name="data[ebay_img_template]" value="0" />
                                <input type="checkbox" name="data[ebay_img_template]" value="1" <?php if(isset($data['ebay_img_template']) && $data['ebay_img_template'] == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_image_exclude_default; ?></td>
                            <td>
                                <input type="hidden" name="data[default_img_exclude]" value="0" />
                                <input type="checkbox" name="data[default_img_exclude]" value="1" <?php if(isset($data['default_img_exclude']) && $data['default_img_exclude'] == 1){ echo 'checked="checked"'; } ?> />
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