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
                <a href="#tab-returns"><?php echo $lang_tab_returns; ?></a>
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

                        <?php if(!empty($setting['returns']['accepted'])) { ?>
                            <tr>
                                <td><label for="returns_accepted"><?php echo $lang_returns_accept; ?></td>
                                <td>
                                    <?php if(!isset($data['returns_accepted'])){ $data['returns_accepted'] = ''; } ?>

                                    <select name="data[returns_accepted]" class="width250">
                                        <?php foreach($setting['returns']['accepted'] as $v) { ?>
                                            <option value="<?php echo $v['ReturnsAcceptedOption']; ?>" <?php if($data['returns_accepted'] == $v['ReturnsAcceptedOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        <?php }else{ ?>
                            <input type="hidden" name="data[returns_accepted]" value="" />
                        <?php } ?>

                        <?php if(!empty($setting['returns']['within'])) { ?>
                            <tr>
                                <td><label for="returns_within"><?php echo $lang_returns_days; ?></td>
                                <td>
                                    <?php if(!isset($data['returns_within'])){ $data['returns_within'] = ''; } ?>

                                    <select name="data[returns_within]" class="width250">
                                        <?php foreach($setting['returns']['within'] as $v) { ?>
                                            <option value="<?php echo $v['ReturnsWithinOption']; ?>" <?php if($data['returns_within'] == $v['ReturnsWithinOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        <?php }else{ ?>
                            <input type="hidden" name="data[returns_within]" value="" />
                        <?php } ?>

                        <?php if(!empty($setting['returns']['paidby'])) { ?>
                            <tr>
                                <td><label for="returns_shipping"><?php echo $lang_returns_costs; ?></td>
                                <td>
                                    <?php if(!isset($data['returns_shipping'])){ $data['returns_shipping'] = ''; } ?>

                                    <select name="data[returns_shipping]" class="width250">
                                        <?php foreach($setting['returns']['paidby'] as $v) { ?>
                                            <option value="<?php echo $v['ShippingCostPaidByOption']; ?>" <?php if($data['returns_shipping'] == $v['ShippingCostPaidByOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        <?php }else{ ?>
                            <input type="hidden" name="data[returns_shipping]" value="" />
                        <?php } ?>

                        <?php if(!empty($setting['returns']['refund'])) { ?>
                            <tr>
                                <td><label for="returns_option"><?php echo $lang_returns_type; ?></td>
                                <td>
                                    <?php if(!isset($data['returns_option'])){ $data['returns_option'] = ''; } ?>

                                    <select name="data[returns_option]" class="width250">
                                        <?php foreach($setting['returns']['refund'] as $v) { ?>
                                            <option value="<?php echo $v['RefundOption']; ?>" <?php if($data['returns_option'] == $v['RefundOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        <?php }else{ ?>
                            <input type="hidden" name="data[returns_option]" value="" />
                        <?php } ?>

                        <?php if($setting['returns']['description'] == true) { ?>

                            <?php if(!isset($data['returns_policy'])){ $data['returns_policy'] = ''; } ?>

                            <tr>
                                <td><label for="returns_policy"><?php echo $lang_returns_inst; ?></td>
                                <td><textarea name="data[returns_policy]" id="returns_policy" maxlength="5000" style="width:400px; height:70px;"><?php echo $data['returns_policy'];?></textarea></td>
                            </tr>
                        <?php }else{ ?>
                            <input type="hidden" name="data[returns_policy]" value="" />
                        <?php } ?>

                        <?php if(!empty($setting['returns']['restocking_fee'])) { ?>
                            <tr>
                                <td><label for="returns_restocking_fee"><?php echo $lang_returns_restock; ?></td>
                                <td>
                                    <?php if(!isset($data['returns_restocking_fee'])){ $data['returns_restocking_fee'] = ''; } ?>

                                    <select name="data[returns_restocking_fee]" class="width250">
                                        <?php foreach($setting['returns']['restocking_fee'] as $v) { ?>
                                            <option value="<?php echo $v['RestockingFeeValueOption']; ?>" <?php if($data['returns_restocking_fee'] == $v['RestockingFeeValueOption']){ echo'selected'; } ?>><?php echo $v['Description']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        <?php }else{ ?>
                            <input type="hidden" name="data[returns_restocking_fee]" value="" />
                        <?php } ?>
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