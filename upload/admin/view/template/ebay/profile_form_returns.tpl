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

                        <tr>
                            <td><label for="returns_accepted"><?php echo $lang_returns_accept; ?></td>
                            <td>
                                <?php if(!isset($data['returns_accepted'])){ $data['returns_accepted'] = ''; } ?>
                                
                                <select name="data[returns_accepted]" class="width250">
                                    <option value="ReturnsNotAccepted" <?php if($data['returns_accepted'] == 'ReturnsNotAccepted'){ echo'selected'; } ?>><?php echo $lang_no; ?></option>
                                    <option value="ReturnsAccepted" <?php if($data['returns_accepted'] == 'ReturnsAccepted'){ echo'selected'; } ?>><?php echo $lang_yes; ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <?php if(!isset($data['returns_policy'])){ $data['returns_policy'] = ''; } ?>
                                
                            <td><label for="returns_policy"><?php echo $lang_returns_inst; ?></td>
                            <td><textarea name="data[returns_policy]" id="returns_policy" maxlength="" style="width:400px; height:70px;"><?php echo $data['returns_policy'];?></textarea></td>
                        </tr>

                        <tr>
                            <td><label for="returns_within"><?php echo $lang_returns_days; ?></td>
                            <td>
                                <?php if(!isset($data['returns_within'])){ $data['returns_within'] = ''; } ?>
                                
                                <select name="data[returns_within]" class="width250">
                                    <option value="Days_14" <?php if($data['returns_within'] == 'Days_14'){ echo'selected'; } ?>><?php echo $lang_returns_days14; ?></option>
                                    <option value="Days_30" <?php if($data['returns_within'] == 'Days_30'){ echo'selected'; } ?>><?php echo $lang_returns_days30; ?></option>
                                    <option value="Days_60" <?php if($data['returns_within'] == 'Days_60'){ echo'selected'; } ?>><?php echo $lang_returns_days60; ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="returns_option"><?php echo $lang_returns_type; ?></td>
                            <td>
                                <?php if(!isset($data['returns_option'])){ $data['returns_option'] = ''; } ?>
                                
                                <select name="data[returns_option]" class="width250">
                                    <option value="MoneyBack" <?php if($data['returns_option'] == 'MoneyBack'){ echo'selected'; } ?>><?php echo $lang_returns_type_money; ?></option>
                                    <option value="MoneyBackOrExchange" <?php if($data['returns_option'] == 'MoneyBackOrExchange'){ echo'selected'; } ?>><?php echo $lang_returns_type_exch; ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="returns_shipping"><?php echo $lang_returns_costs; ?></td>
                            <td>
                                <?php if(!isset($data['returns_shipping'])){ $data['returns_shipping'] = ''; } ?>
                                
                                <select name="data[returns_shipping]" class="width250">
                                    <option value="Buyer" <?php if($data['returns_shipping'] == 'Buyer'){ echo'selected'; } ?>><?php echo $lang_returns_costs_b; ?></option>
                                    <option value="Seller" <?php if($data['returns_shipping'] == 'Seller'){ echo'selected'; } ?>><?php echo $lang_returns_costs_s; ?></option>
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