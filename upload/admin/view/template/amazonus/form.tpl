<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <?php if(!empty($errors)) { ?>
        <div class="warning">
            <ul>
                <?php foreach($errors as $error) : ?>
                    <li><?php echo $error['message']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php } ?>
    
  <div class="box">
    
    <div class="heading">
      <h1><?php echo $heading_title; ?></h1>
      <div class="buttons">
          <a id="cancel_button" onclick="location = '<?php echo $cancel_url; ?>';" class="button"><span><?php echo $cancel_button_text; ?></span></a>
      </div>
    </div>
    <div class="content"> 
        
        <div id="tabs" class="htabs">
            <a href="#page-links" id="tab-links"><?php echo $item_links_tab_text; ?></a>
            <a href="#page-quick" id="tab-quick"><?php echo $quick_listing_tab_text ;?></a>
            <a href="#page-advanced" id="tab-advanced"><?php echo $advanced_listing_tab_text ;?></a>
            <a href="#page-saved" id="tab-saved"><?php echo $saved_tab_text ;?></a>
        </div>

<div id="page-links">
    <table class="form" align="left">
        <tr>
            <td colspan="2">
                <h2><?php echo $item_links_header_text; ?></h2>
                <p><?php echo $item_links_description; ?></p>
            </td>
        </tr>
    </table>
   
<?php $pId = isset($product_id) ? $product_id : $edit_id; ?>         
<table align="left" class="list" id="linkListTable">
    <thead id="tableThread1">
        <tr>
            <td class="center" colspan="4"><?php echo $new_link_table_name;?></td>
        </tr>
    </thead>
    <thead id="tableThread2">
        <tr>
            <td class="right" width="35%"><?php echo $new_link_product_column; ?></td>
            <td class="center" width="20%"><?php echo $new_link_sku_column; ?></td>
            <td class="left" width="35%"><?php echo $new_link_amazonus_sku_column; ?></td>
            <td class="center" width="10%"><?php echo $new_link_action_column; ?></td>
        </tr>
    </thead>
    <tbody id="tableBody">
        <tr>
            <td class="right">
                <input type="text" disabled style="width: 90%" value="<?php echo $listing_name; ?>">
                <input type="hidden" id="var0" value="">
            </td>
            <td class="center">
                <input type="text" disabled value="<?php echo $listing_sku; ?>">
            </td>
            <td>
                <input id="amazonusSku0" type="text">
            </td>
            <td class="center">
                <a class="button" id="addNewButton" onclick="addNewLink(this, <?php echo $pId; ?>, 0)"><span>Add</span></a>
            </td>
        </tr>
        <?php $i = 1; foreach($options as $option) { ?>
            <tr>
                <td class="right">
                    <input type="text" disabled style="width: 90%" value="<?php echo $listing_name . " : " . $option['combi'] ?>">
                    <input type="hidden" id="var<?php echo $i; ?>" value="<?php echo $option['var']; ?>">
                </td>
                <td class="center">
                    <input type="text" disabled value="<?php echo $option['sku']; ?>">
                </td>
                <td>
                    <input id="amazonusSku<?php echo $i; ?>" type="text">
                </td>
                <td class="center">
                    <a class="button" id="addNewButton" onclick="addNewLink(this, <?php echo $pId; ?>, <?php echo $i; ?>)"><span>Add</span></a>
                </td>
            </tr>
        <?php $i++; } ?>
          
    </tbody>
</table>    
    <table align="left" class="list" id="linkListTable">
    <thead>
        <tr>
            <td class="center" colspan="5"><?php echo $item_links_table_name; ?></td>
        </tr>
    </thead>
    <thead>
        <tr>
            <td width="45%"><?php echo $new_link_product_column; ?></td>
            <td width="45%"><?php echo $new_link_amazonus_sku_column; ?></td>
            <td class="center" width="10%"><?php echo $new_link_action_column; ?></td>
        </tr>
    </thead>
    <tbody id="linkedItems"></tbody>
</table>
                   
</div>  
        <form method="POST" id="product_form_quick">
            <div id="page-quick">
                <table class="form" align="left">
                    <tr>
                        <td colspan="2"><h2><?php echo $quick_listing_header_text; ?></h2>
                        <p><?php echo $quick_listing_description; ?></p>
                        </td>
                    </tr>
                </table>
                
                <table id="quick_table" class="form" align="left"> 
                    <tbody>
                        <tr>
                            <td style="width: 400px;"><?php echo $listing_row_text; ?></td>
                            <td>
                                <a href="<?php echo $listing_url; ?>"><?php echo $listing_name; ?><?php if(!empty($options)) { echo " : "; } ?></a>
                                <?php if(!empty($options)) { ?>
                                <select id="quick_option_selector" name="optionVar">
                                    <option></option>
                                    <?php foreach($options as $option) { ?>
                                    <option <?php if ($edit_var === $option['var']) { echo "selected='selected'";} ?> value="<?php echo  $option['var']?>"><?php echo $option['combi']?></option>
                                    <?php } ?>
                                </select>
                                <?php }?>
                            </td>
                        </tr>
                        <?php if($product_saved) { ?>
                        <tr>
                            <td><?php echo $already_saved_text; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    
                    <?php if(!$product_saved) { ?>
                    <tbody id="fields_quick"></tbody>
                    <tbody>
                        <tr>
                            <td>
                                <div class="buttons">
                                    <a id="save_button" onclick="validate_and_save('quick')" class="button"><span><?php echo $save_button_text; ?></span></a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
        </form>
        
        <form method="POST" id="product_form_advanced">   
            <div id="page-advanced">
                <table class="form" align="left">
                    <tr>
                        <td colspan="2"><h2><?php echo $advanced_listing_header_text; ?></h2>
                        <p><?php echo $advanced_listing_description; ?></p>
                        </td>
                    </tr>
                </table>
                
                <table id="advanced_table" class="form" align="left"> 
                <tbody>
                    <tr>
                        <td style="width: 400px;"><?php echo $listing_row_text; ?></td>
                        <td>
                            <a href="<?php echo $listing_url; ?>"><?php echo $listing_name; ?><?php if(!empty($options)) { echo " : "; } ?></a>
                            <?php if(!empty($options)) { ?>
                            <select id="advanced_option_selector" name="optionVar">
                                <option></option>
                                <?php foreach($options as $option) { ?>
                                <option <?php if ($edit_var === $option['var']) { echo "selected='selected'";} ?> value="<?php echo  $option['var']?>"><?php echo $option['combi']?></option>
                                <?php } ?>
                            </select>
                            <?php }?>
                        </td>
                    </tr>

                    <?php if(!$product_saved) { ?>
                    <tr>
                        <td>
                            <?php echo $category_selector_field_text; ?><br>
                            <span class="help"></span>
                        </td>
                        <td>
                            <select id="category_selector">
                                <option value=""></option>
                                <?php foreach($amazonus_categories as $category) {  ?>
                                    <option <?php if ($edit_product_category == $category["name"]) echo 'selected="selected"'; ?> value="<?php echo $category['template'] ?>"><?php echo $category['friendly_name'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td><?php echo $already_saved_text; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>

                <tbody id="fields_advanced"></tbody>
                
                <?php if(!$product_saved) { ?>
                <tbody>
                    <tr>
                        <td>
                            <div class="buttons">
                                <a id="save_button" onclick="validate_and_save('advanced')" class="button"><span><?php echo $save_button_text; ?></span></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <?php } ?>
                
            </table>
            </div>
        </form>        

    <div id="page-saved">
        <table class="form" align="left">
            <tr>
                <td colspan="2"><h2><?php echo $saved_heder_text; ?></h2>
                <p><?php echo $saved_listings_description; ?></p>
                <div class="buttons">
                    <a id="upload_button" onclick="upload()" class="button"><span><?php echo $upload_button_text; ?></span></a>
                </div>
                </td>
            </tr>
        </table>
        
        <table class="list" align="left">
            <thead>
                <tr>
                    <td width="22.5%"><?php echo $name_column_text ;?></td>
                    <td width="22.5%"><?php echo $model_column_text ;?></td>
                    <td width="22.5%"><?php echo $sku_column_text ;?></td>
                    <td width="22.5%"><?php echo $amazonus_sku_column_text ;?></td>
                    <td class="center" width="10%"><?php echo $actions_column_text ;?></td>
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
                        <a href="<?php echo $saved_product['edit_link']; ?>" >[<?php echo $actions_edit_text; ?>]</a> <a onclick="removeSaved('<?php echo $saved_product['product_id']; ?>', '<?php echo $saved_product['var']; ?>')">[<?php echo $actions_remove_text; ?>]</a>
                    </td>
                </tr>

                <?php endforeach; ?>
            </tbody>
        </table>  
    </div>
    </div>
  </div>
</div>



<script type="text/javascript"><!--

function loadLinks() {
    $.ajax({
            url: '<?php echo html_entity_decode($loadLinks); ?>',
            type: 'get',
            dataType: 'json',
            data: 'product_id=<?php echo $pId; ?>',
            success: function(json) {
                if(json == 'error') {
                    alert('Error loading item links.');
                    return;
                }
                var rows = '';
                for(i in json) {
                    rows += '<tr>';
                    rows += '<td class="left">' + json[i]['product_name'] + ' : ' + json[i]['model'] + ' : ' + json[i]['combi'] + '</td>';
                    rows += '<td class="left">' + json[i]['amazonus_sku'] + '</td>';
                    rows += '<td class="center"><a class="button" onclick="removeLink(this, \'' + json[i]['amazonus_sku'] + '\')" ><span><?php echo $links_remove_text; ?></span></a></td>';
                    rows += '</tr>';
                }
                
                 $('#linkedItems').html(rows);  
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
    });	
}

function addNewLink(button, product_id, row_id) {
    var amazonus_sku_field = $('#amazonusSku' + row_id);
    var var_field = $('#var' + row_id);

    $.ajax({
            url: '<?php echo html_entity_decode($addLink); ?>',
            type: 'get',
            dataType: 'json',
            async: 'false',
            data: 'product_id=' + encodeURIComponent(product_id) + '&amazonus_sku=' + encodeURIComponent(amazonus_sku_field.val()) + '&var=' + encodeURIComponent(var_field.val()),
            beforeSend: function() {
               $(button).after('<span class="wait"><img src="view/image/loading.gif" alt="" /></span>');  
               $(button).hide();
            },
            complete: function() {
                $('.wait').remove();
                $(button).show();
            },
            success: function(json) {
                //alert(json);
                amazonus_sku_field.val('');
                loadLinks();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
    });	
}

function removeLink(button, amazonus_sku) {
    $.ajax({
            url: '<?php echo html_entity_decode($removeLink); ?>',
            type: 'get',
            dataType: 'json',
            data: 'amazonus_sku=' + encodeURIComponent(amazonus_sku),
            beforeSend: function() {
               $(button).after('<span class="wait"><img src="view/image/loading.gif" alt="" /></span>');  
               $(button).hide();
            },
            success: function(json) {
                //alert(json);
                loadLinks();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
    });	
}

//--></script> 







<script type="text/javascript">
    $(document).ready(function(){
        
        $('#quick_option_selector').change(function() {
            redirectOption($('#quick_option_selector').attr('value'), 'quick');

        });
        $('#advanced_option_selector').change(function() {
            redirectOption($('#advanced_option_selector').attr('value'), 'advanced');

        });
        /*
        if(redirectOption($('#quick_option_selector'))) {
            return;
        }
        */
        $('#tabs a').tabs();

        <?php if(empty($amazonus_categories)) { ?>
                $("#quick_table").html("");
                $("#advanced_table").html("");
                $(".content").prepend('<div id="warning" class="warning"><?php echo $text_error_connecting; ?></div>');
                return;
        <?php } ?>
        
        $("#fields_advanced :input").live('change', function() {
            update_form(this, 'advanced');
        });
        
        $("#fields_quick :input").live('change', function() {
            update_form(this, 'quick');
        });
        
        $('#category_selector').change(function(){
            
            var xml = $('#category_selector').attr('value');
            if(xml == '') {
                $('#fields_advanced').empty();
                return;
            }
            show_form(xml, 'advanced');
        });
        //Update needed if editing
        $('#category_selector').change();
        
        <?php if(isset($inventory_loader['template'])) { ?>
        show_form('<?php echo html_entity_decode($inventory_loader['template']) ?>', 'quick');
        <?php } ?>
        
        <?php if($selected_tab == 'saved') { ?>
            $('#tab-saved').click();
        <?php } else if ($selected_tab == 'advanced') { ?>
            $('#tab-advanced').click();
        <?php } else { ?>
             $('#tab-quick').click();
        <?php } ?>
            
        loadLinks();
    });
    
    function redirectOption(varOption, tabOption) {
        var searchLoc = insertParamToUrl(document.location.search, 'var', varOption);
        searchLoc = insertParamToUrl(searchLoc, 'tab', tabOption);
        searchLoc = searchLoc.substr(1);
        if(document.location.search === searchLoc) {
            return false;
        } else {
            document.location.search = searchLoc;
            return true;
        }
    }
    
    function insertParamToUrl(searchLoc, key, value) {
        var kvp = searchLoc.split('&');
       
        if (kvp == '') {
            searchLoc = '?' + key + '=' + value;
            return searchLoc;
        }
        else {
            var i = kvp.length; var x; while (i--) {
                x = kvp[i].split('=');
                if (x[0] == key) {
                    if(x[1] == value) {
                        return searchLoc;
                    }
                    x[1] = value;
                    kvp[i] = x.join('=');
                    break;
                }
            }
            if (i < 0) { kvp[kvp.length] = [key, value].join('='); }
            return kvp.join('&');
        }
    }
    
    
    
    
    
    var fieldsArray = new Array();

    //formType = 'quick' or 'advanced'
    function show_form(xml, formType) {
        $('#fields_' + formType).empty();
        
        var parserURL = '<?php echo html_entity_decode($template_parser_url) ?>';
        var reqUrl = parserURL + '&xml=' + xml;
        
        if(formType === 'quick' && $('#quick_option_selector').attr('value') != undefined) {
            reqUrl = reqUrl + '&var=' + $('#quick_option_selector').attr('value');
        } else if(formType === 'advanced' && $('#advanced_option_selector').attr('value') != undefined) {
            reqUrl = reqUrl + '&var=' + $('#advanced_option_selector').attr('value');
        }
        
        
        $.ajax({
            url: reqUrl,
            data: {},
            dataType: 'json',
            beforeSend: function() {
                if(formType == 'advanced') {
                    $('#category_selector').attr('disabled', true);
                    $('#category_selector').after('<span class="wait"><img src="view/image/loading.gif" alt="" />&nbsp;</span>');
                }
            },
            complete: function() {
                if(formType == 'advanced') {
                    $('#category_selector').attr('disabled', false);
                }
                $('.wait').remove();
            },	
            success: function(data) {
                var categoryName = data['category'];
                fieldsArray[formType] = data['fields'];
                

                $('#fields_' + formType).append('<input type="hidden" name="category" value="' + categoryName + '">');
                
                for (i in fieldsArray[formType]) {
                    var row  = "";
                     if (fieldsArray[formType][i]['child']){
                        row += '<tr class="child_row" display="no" field_index="' + i + '" style="display: none">';
                    } else {
                        row += '<tr>';
                    }
                    
                    row += '<td>';
                    if(fieldsArray[formType][i]['type'] == 'required') {
                        row += '<span class="required">* </span>';
                    }
                    row += fieldsArray[formType][i]['title'];
                    row += '<span class="help">' + fieldsArray[formType][i]['definition'] + '</span>';
                    row += '</td>';
                    row += '<td>';

                    if(fieldsArray[formType][i]['accepted']['type'] == "integer") {
                        row += getIntegerField(fieldsArray[formType][i]);
                    }                    
                    else if(fieldsArray[formType][i]['accepted']['type'] == "text_area") {
                        row += getTextAreaField(fieldsArray[formType][i]);
                    }
                    else if(fieldsArray[formType][i]['accepted']['type'] == "select") {
                        row += getSelectField(fieldsArray[formType][i]);
                    }
                    else if(fieldsArray[formType][i]['accepted']['type'] == "image") {
                        row += getImageField(fieldsArray[formType][i]);
                    } 
                    else {
                        row += getStringField(fieldsArray[formType][i]);
                    }

                    if(fieldsArray[formType][i]['type'] == "required") {
                        row += '<span class="required" id="required_' + fieldsArray[formType][i]['name'] + '"></span>'
                    }
                    row += '</td>';
                    row += '</tr>';

                    $('#fields_' + formType).append(row);
                }
                
                //Emulate changes to populate child fields
                $('#fields_' + formType + ' :input').each(function (i) {
                    $(this).change(); 
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
    
    //Called when chenge to form was made. Shows child rows bassed on input if needed.
    function update_form(element, formType) {
        var changedFieldName = $(element).attr('field_name');
        var changedFieldValue = $(element).attr('value');
        
        $('#fields_' + formType + ' .child_row').each(function (i) {
            var index = $(this).attr('field_index');
            if(fieldsArray[formType][index]['parent']['name'] == changedFieldName) {
                var showChild = false;
                
                //values is array?
                if(fieldsArray[formType][index]['parent']['value'] instanceof Array) {
                    for(i in fieldsArray[formType][index]['parent']['value']) {
                        if(fieldsArray[formType][index]['parent']['value'][i] == changedFieldValue) {
                            showChild = true;
                        }
                    }
                } else if(fieldsArray[formType][index]['parent']['value'] == changedFieldValue) {
                    showChild = true;
                } else if(fieldsArray[formType][index]['parent']['value'] == '*' && changedFieldValue != '') {
                    showChild = true;
                }
                
                if(showChild) {
                    $(this).attr('display', 'yes');
                    $(this).removeAttr('style');
                } else {
                    $('#fields_' + formType + ' [field_name="' + fieldsArray[formType][index]['name']  + '"]').attr('value', '');
                    $(this).attr('display', 'no');
                    $(this).attr('style', 'display: none');
                }
            }
        });
    }
    
    function getImageField(fieldData) {
        var output = "";
        
        output += '<input ';
        output += 'type="hidden" ';
        output += 'accepted="' + fieldData['accepted']['type'] + '" ';
        output += 'field_name="' + fieldData['name'] + '" ';
        output += 'field_type="' + fieldData['type'] + '" ';
        output += 'id="imagefield_' + fieldData['name'] + '" ';
        output += 'name="fields[' + fieldData['name'] + ']" ';
        output += 'value="' + fieldData['value'] + '">';
        
        output += '<div class="image">';
        output += '<img height="100" alt="" id="thumb_' + fieldData['name'] + '" ';
        if(fieldData['value'] == "") {
            output += 'src="<?php echo $no_image; ?>"';
        } else {
            output += 'src="' + fieldData['value'] + '"';
        }
        output += "/>";
        output += '<br />';
        
        output += '<a onclick="image_upload(\'imagefield_' + fieldData['name'] + '\', \'thumb_' + fieldData['name'] + '\')"><?php echo $browse_image_text; ?></a>';
        output += '  |  ';
        output += '<a onclick="cleaImageField(\'' + fieldData['name'] + '\')"><?php echo $clear_image_text; ?></a>';
        output += "</div>";
          
        return output;
    }
    
    function cleaImageField(fieldName) {
        $('#imagefield_' + fieldName).attr('value', '');
        $('#thumb_' + fieldName).attr('src', '<?php echo $no_image; ?>');
    }
    
    function getIntegerField(fieldData) {
        var output = "";
        
        output += '<input ';
        output += 'type="number" ';
        output += 'min="0" ';
        output += 'accepted="' + fieldData['accepted']['type'] + '" ';
        output += 'field_name="' + fieldData['name'] + '" ';
        output += 'field_type="' + fieldData['type'] + '" ';
        output += 'name="fields[' + fieldData['name'] + ']" ';
        if(fieldData['name'] == 'Quantity') {
            output += 'readonly ';
        }
        output += 'value="' + fieldData['value'] + '">';
        
        return output;
    }
    
    function getTextAreaField(fieldData) {
        var output = "";
        
        output += '<textarea ';
        output += 'rows="5" ';
        output += 'cols="60" ';
        if('min_length' in fieldData['accepted']) {
            output += 'min_length="'+ fieldData['accepted']['min_length'] + '" ';
        }
        if('max_length' in fieldData['accepted']) {
            output += 'max_length="'+ fieldData['accepted']['max_length'] + '" ';
        }
        output += 'field_name="' + fieldData['name'] + '" ';
        output += 'field_type="' +  fieldData['type'] + '" ';
        output += 'name="fields[' + fieldData['name'] + ']" class="width400 height250">';
        output += fieldData['value'];
        output += '</textarea>';
        
        return output;
    }
    
    function getStringField(fieldData) {
        var output = "";
        
        output += '<input type="text"';
        output += 'accepted="' + fieldData['accepted']['type'] + '" ';
        if('min_length' in fieldData['accepted']) {
            output += 'min_length="'+ fieldData['accepted']['min_length'] + '" ';
        }
        if('max_length' in fieldData['accepted']) {
            output += 'max_length="'+ fieldData['accepted']['max_length'] + '" ';
        }
        output += 'field_name="' + fieldData['name'] + '" ';
        output += 'field_type="' + fieldData['type'] + '" ';
        output += 'name="fields[' + fieldData['name'] + ']" ';
        output += 'value="' + fieldData['value'] + '" class="width400">';
        
        return output;
    }
    
    function getSelectField(fieldData) {
        var output = "";
        
        output += '<select ';
        output += 'field_name="' + fieldData['name'] + '" ';
        output += 'field_type="' + fieldData['type'] + '" ';
        output += 'name="fields[' + fieldData['name'] + ']" class="width250">';
        
        output += '<option></option>';
        
        if(fieldData['accepted']['option'].length != undefined) {
            for(j in fieldData['accepted']['option']) {
                output += '<option ';
                
                if(fieldData['value'] == fieldData['accepted']['option'][j]['value']) {
                    output += 'selected="selected" '; 
                }
                output += 'value="' + fieldData['accepted']['option'][j]['value'] + '">';
                output += fieldData['accepted']['option'][j]['name'];
                output += '</option>';
            }
        }
        else {
            output += '<option ';

            if(fieldData['value'] == fieldData['accepted']['option']['value']) {
                output += 'selected="selected" '; 
            }
            output += 'value="' + fieldData['accepted']['option']['value'] + '">';
            output += fieldData['accepted']['option']['name'];
            output += '</option>';            
        }
        output += '</select>';
        return output;
    }
   
    function image_upload(field, thumb) {
        $('#dialog').remove();
        
        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
        
        $('#dialog').dialog({
            title: '<?php echo $text_image_manager; ?>',
            close: function (event, ui) {
                if ($('#' + field).attr('value')) {
                    $.ajax({
                        url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
                        dataType: 'text',
                        success: function(data) {
                            if(data != "") {
                                $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                                var imageUrl = $('#' + field).attr('value');
                                $('#' + field).attr('value', '<?php echo HTTPS_CATALOG; ?>image/' + imageUrl);
                            }
                        }
                    });
                }
            },	
            bgiframe: false,
            width: 800,
            height: 400,
            resizable: false,
            modal: false
        });
    }
    
    //form = 'quick' or 'advanced'
    function validate_and_save(formType) {
        var warnings = 0;
        var productIdType;
        var productId;
        
        $('#fields_' + formType + ' :input').each(function (i) {
            
            if($(this).parent().parent().attr('display') == "no") {
                return;
            }
            
            var field_value = $(this).val();
            var field_name = $(this).attr('field_name');
            var field_type = $(this).attr('field_type');
            
            var min_length = $(this).attr('min_length');
            var max_length = $(this).attr('max_length');
            
            if(field_name === 'Type') {
                productIdType = field_value;
            } else if(field_name === 'Value') {
                productId = field_value;
            }
        
            if(field_type == 'required') {
                if(field_value == '') {
                    $('#fields_' + formType + ' #required_' + field_name).text('<?php echo $field_required_text ?>'); 
                    warnings ++;
                }
                else if (min_length != undefined && field_value.length < min_length) {
                    $('#fields_' + formType + ' #required_' + field_name).text('<?php echo $minimum_length_text; ?> ' + min_length + ' <?php echo $characters_text; ?>'); 
                    warnings ++;
                }
                else if (max_length != undefined && field_value.length > max_length) {
                    $('#fields_' + formType + ' #required_' + field_name).text((field_value.length - max_length) + ' <?php echo $chars_over_limit_text; ?>'); 
                    warnings ++;
                }
                else {
                    $('#fields_' + formType + ' #required_' + field_name).text('');
                }
            }
        });
        
        if(productIdType !== 'ASIN' && !isValidProductId(productId)) {
            $('#fields_' + formType + ' :input').each(function (i) {
                var field_name = $(this).attr('field_name');
                if(field_name === 'Value') {
                    $('#fields_' + formType + ' #required_' + field_name).text('Not valid product ID!'); 
                    warnings ++;
                    return;
                }
            });
        }
        
        if($('#fields_' + formType + ' [name="category"]').attr('value') == undefined) {
            warnings ++;
        }
       
        if(warnings > 0) {
            alert('<?php echo $not_saved_text; ?>');
        }
        else if (formType == 'advanced') {
            $("#product_form_advanced").submit();
        } else if (formType == 'quick') {
            $("#product_form_quick").submit();
        }
        
    }
    
    function removeSaved(id, optionVar) {
        if(!confirm("<?php echo $delete_confirm_text; ?>")) {
            return;
        }
        $.ajax({
            url: '<?php echo html_entity_decode($remover_url); ?>' + '&product_id=' + id + '&var=' + optionVar,
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
            url: '<?php echo html_entity_decode($uploader_url); ?>',
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
                    alert('<?php echo $uploaded_alert_text; ?>');
                } else if(data['info'] != undefined){
                    alert(data['status'].toUpperCase() + ': ' + data['info']);
                    return;
                } else {
                    alert(data['status'] + ': ' + data['info']);
                    return;
                }
                window.location.href='<?php echo html_entity_decode($cancel_url); ?>';
            },
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
    
    function isValidProductId(value) {
        var barcode = value.substring(0, value.length - 1);
        var checksum = parseInt(value.substring(value.length - 1), 10);
        var calcSum = 0;
        var calcChecksum = 0;
        barcode.split('').map(function(number, index ) {
            number = parseInt(number, 10);
            if(value.length === 13) {
                if (index % 2 === 0) {
                    calcSum += number;
                }
                else {
                    calcSum += number * 3;
                }
            } else {
                if (index % 2 === 0) {
                    calcSum += number * 3;
                }
                else {
                    calcSum += number;
                }
            }
        });
        calcSum %= 10;
        calcChecksum = (calcSum === 0) ? 0 : (10 - calcSum);
        if (calcChecksum !== checksum) {
            return false;
        }
        return true;
    }

</script>
<?php echo $footer; ?>