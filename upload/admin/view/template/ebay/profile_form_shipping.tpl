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
                <a href="#tab-shipping"><?php echo $lang_tab_shipping; ?></a>
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
                            <td><input type="text" name="name" class="width400" value="<?php if(isset($name)){ echo $name; } ?>"></td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_profile_desc; ?></td>
                            <td>
                                <textarea name="description" cols="40" rows="5" class="width400"><?php if(isset($description)){ echo $description; } ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div id="tab-shipping">
                    <table class="form">
                        <tr>
                            <td><label><?php echo $lang_shipping_postcode; ?></label></td>
                            <td><input type="text" name="data[postcode]" id="postcode" class="width100" maxlength="" value="<?php if(isset($data['postcode'])){ echo $data['postcode']; } ?>" /></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_shipping_location; ?></label></td>
                            <td><input type="text" name="data[location]" id="location" class="width100" maxlength="" value="<?php if(isset($data['location'])){ echo $data['location']; } ?>" /></td>
                        </tr>
                        <tr>
                            <td><label><?php echo $lang_shipping_despatch; ?></label></td>
                            <td>
                                <select name="data[dispatch_time]" id="dispatch_time" class="width100">
                                    <?php foreach($dispatchTimes as $dis){ ?>
                                        <option value="<?php echo $dis['DispatchTimeMax'];?>" 
                                        <?php if(isset($data['dispatch_time']) && $data['dispatch_time'] == $dis['DispatchTimeMax']){ echo' selected'; } ?>
                                        ><?php echo $dis['Description'];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_shipping_in_desc; ?></td>
                            <td>
                                <input type="hidden" name="data[shipping_in_desc]" value="0" />
                                <input type="checkbox" name="data[shipping_in_desc]" value="1" id="shipping_in_desc" <?php if(isset($data['shipping_in_desc']) && $data['shipping_in_desc'] == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_shipping_getitfast; ?></td>
                            <td>
                                <input type="hidden" name="data[get_it_fast]" value="0" />
                                <input type="checkbox" name="data[get_it_fast]" value="1" id="get_it_fast" <?php if(isset($data['get_it_fast']) && $data['get_it_fast'] == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr class="shipping_table_rows">
                            <td valign="top">
                                <label><?php echo $lang_shipping_nat; ?></label>
                                <p style="text-align:left; margin-right:10px;"><a class="button" onclick="addShipping('national');"><span><?php echo $lang_btn_add; ?></span></a></p>
                            </td>
                            <td id="nationalBtn">
                                <input type="hidden" name="data[count_national]" value="<?php echo $data['shipping_national_count']; ?>" id="count_national" />
                                <?php
                                if(isset($data['shipping_national']) && is_array($data['shipping_national'])){
                                foreach($data['shipping_national'] as $key => $service){
                                echo'<p class="shipping_national_'.$key.'" style="border-top:1px dotted; margin:0; padding:5px 0;"><label><strong>'.$lang_shipping_service.'</strong><label>';

                                echo'<input type="hidden" name="data[service_national]['.$key.']" value="'.$service['id'].'" /> '.$service['name'].'</p>';

                                echo'<p class="shipping_national_'.$key.'"><label>'.$lang_shipping_first.'</label>';
                                echo'<input type="text" name="data[price_national]['.$key.']" style="width:50px;" value="'.$service['price'].'" />';
                                echo'&nbsp;&nbsp;<label>'.$lang_shipping_add.'</label>';
                                echo'<input type="text" name="data[priceadditional_national]['.$key.']" style="width:50px;" value="'.$service['additional'].'" />&nbsp;&nbsp;<a onclick="removeShipping(\'national\',\''.$key.'\');" class="button"><span>'.$lang_btn_remove.'</span></a></p>';
                                }
                                }
                                ?>
                            </td>
                        </tr>
                        <tr class="shipping_table_rows">
                            <td valign="top">
                                <label><?php echo $lang_shipping_intnat; ?></label>
                                <p style="text-align:left; margin-right:10px;"><a class="button" onclick="addShipping('international');"><span><?php echo $lang_btn_add; ?></span></a></p>
                            </td>
                            <td id="internationalBtn">
                                <input type="hidden" name="data[count_international]" value="<?php echo $data['shipping_international_count']; ?>" id="count_international" />
                                <?php
                                if(isset($data['shipping_international']) && is_array($data['shipping_international'])){
                                foreach($data['shipping_international'] as $key => $service){
                                echo'<p class="shipping_international_'.$key.'" style="border-top:1px dotted; margin:0; padding:8px 0;"><label><strong>'.$lang_shipping_service.'</strong><label>';

                                echo'<input type="hidden" name="data[service_international]['.$key.']" value="'.$service['id'].'" /> '.$service['name'].'</p>';

                                echo'<h5 style="margin:5px 0;" class="shipping_international_'.$key.'">Ship to zones</h5>';
                                echo'<div style="border:1px solid #000; background-color:#F5F5F5; width:100%; min-height:40px; margin-bottom:10px; display:inline-block;" class="shipping_international_'.$key.'">';

                                echo'<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
                                echo'<input type="checkbox" name="data[shipto_international]['.$key.'][]" value="Worldwide" ';
                                if(in_array('Worldwide', $service['shipto'])){ echo' checked="checked"'; }
                                echo'/> Worldwide</div>';

                                foreach($shipping_international_zones as $zone){
                                    echo'<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
                                    echo'<input type="checkbox" name="data[shipto_international]['.$key.'][]" value="'.$zone['shipping_location'].'" ';
                                    if(in_array($zone['shipping_location'], $service['shipto'])){ echo' checked="checked"'; }
                                    echo'/> '.$zone['description'].'</div>';
                                }
                                echo'</div>';

                                echo'<div style="clear:both;" class="shipping_international_'.$key.'"></div>';
                                echo'<p class="shipping_international_'.$key.'"><label>'.$lang_shipping_first.'</label>';
                                echo'<input type="text" name="data[price_international]['.$key.']" style="width:50px;" value="'.$service['price'].'" />';
                                echo'&nbsp;&nbsp;<label>'.$lang_shipping_add.'</label>';
                                echo'<input type="text" name="data[priceadditional_international]['.$key.']" style="width:50px;" value="'.$service['additional'].'" />&nbsp;&nbsp;<a onclick="removeShipping(\'international\',\''.$key.'\');" class="button"><span>'.$lang_btn_remove.'</span></a></p>';
                                echo'<div style="clear:both;"></div>';
                                }
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    function addShipping(id) {
        if (id == 'national') {
            var loc = '0';
        } else {
            var loc = '1';
        }

        var count = $('#count_' + id).val();
        count = parseInt(count);

        $.ajax({
            url: 'index.php?route=openbay/openbay/getShippingService&token=<?php echo $token; ?>&loc=' + loc,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                html = '';
                html += '<p class="shipping_' + id + '_' + count + '" style="border-top:1px dotted; margin:0; padding:8px 0;"><label><strong><?php echo $lang_shipping_service; ?></strong> <label><select name="data[service_' + id + '][' + count + ']">';

                $.each(data.svc, function(key, val) {
                    html += '<option value="' + val.ShippingService + '">' + val.description + '</option>';
                });

                html += '</select></p>';

                if(id == 'international'){
                    html += '<h5 style="margin:5px 0;" class="shipping_' + id + '_' + count + '">Ship to zones</h5>';
                    html += '<div style="border:1px solid #000; background-color:#F5F5F5; width:100%; min-height:40px; margin-bottom:10px; display:inline-block;" class="shipping_' + id + '_' + count + '">';
                    html += '<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
                    html += '<input type="checkbox" name="data[shipto_international][' + count + '][]" value="Worldwide" /> Worldwide</div>';
                    
                    <?php foreach($shipping_international_zones as $zone){ ?>
                        html += '<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
                        html += '<input type="checkbox" name="data[shipto_international][' + count + '][]" value="<?php echo $zone['shipping_location']; ?>" /> <?php echo $zone['description']; ?></div>';
                    <?php } ?>
                    
                    html += '</div>';
                    html += '<div style="clear:both;" class="shipping_' + id + '_' + count + '"></div>';
                }
                
                
                html += '<p class="shipping_' + id + '_' + count + '"><label><?php echo $lang_shipping_first; ?></label><input type="text" name="data[price_' + id + '][' + count + ']" style="width:50px;" value="0.00" />';
                html += '&nbsp;&nbsp;<label><?php echo $lang_shipping_add; ?></label><input type="text" name="data[priceadditional_' + id + '][' + count + ']" style="width:50px;" value="0.00" />&nbsp;&nbsp;<a onclick="removeShipping(\'' + id + '\',\'' + count + '\');" class="button"><span><?php echo $lang_btn_remove; ?></span></a></p>';
                html += '<div style="clear:both;" class="shipping_' + id + '_' + count + '"></div>';                

                $('#' + id + 'Btn').append(html);
            }
        });

        $('#count_' + id).val(count + 1);
    }

    function removeShipping(id, count) {
        $('.shipping_' + id + '_' + count).remove();
    }

    $('#tabs a').tabs();

    $('#shipping_in_desc').change(function() {
        updateDisplayShippingOptions();
    });

    function updateDisplayShippingOptions() {
        if ($('#shipping_in_desc').is(':checked')) {
            $('.shipping_table_rows').hide();
        } else {
            $('.shipping_table_rows').show();
        }
    }

    $(document).ready(function() {
        updateDisplayShippingOptions();
    });
//--></script> 
<?php echo $footer; ?>