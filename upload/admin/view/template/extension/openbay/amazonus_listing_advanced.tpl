<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <?php if ($has_listing_errors) { ?>
                <a href="<?php echo $url_remove_errors; ?>" data-toggle="tooltip" title="<?php echo $button_remove_error; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a>
                <?php } ?>
                <a href="<?php echo $cancel_url; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> </div>
            <h1><?php echo $text_title_advanced; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($errors) { ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $listing_error) { ?>
                <li><i class="fa fa-exclamation-circle"></i> <?php echo $listing_error['message']; ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
        <?php } ?>
        <ul class="nav nav-tabs" id="tabs">
            <li class="active"><a href="#page-main" data-toggle="tab"><?php echo $tab_main; ?></a></li>
        </ul>
        <form method="POST" id="product_form_advanced" class="form-horizontal">
            <input type="hidden" name="upload_after" value="false">
            <div class="tab-content" id="tab-content">
                <div class="tab-pane active" id="page-main">
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_product; ?></label>
                        <div class="col-sm-10">
                            <p> <a href="<?php echo $listing_url; ?>"><?php echo $listing_name; ?>
                                <?php if (!empty($options)) { echo " : "; } ?>
                            </a>
                                <?php if (!empty($options)) { ?>
                                <select id="openstock_selector" name="optionVar" class="form-control">
                                    <?php $option_selected = false; ?>
                                    <?php foreach($options as $option) { ?>
                                    <?php if (!empty($option['sku'])) { ?>
                                    <option <?php if ($variation == $option['sku']) { echo "selected='selected'"; $option_selected = true; } ?> value="<?php echo  $option['sku']?>"><?php echo $option['combination']?></option>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php if ($option_selected == false) { ?>
                                    <option selected="selected"></option>
                                    <?php } ?>
                                </select>
                                <?php }?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="category_selector" id="category_selector_label"><?php echo $entry_category; ?></label>
                        <div class="col-sm-4">
                            <select name="category_selector" id="category_selector" class="form-control">
                                <option value=""></option>
                                <?php foreach($amazonus_categories as $category) {  ?>
                                <option <?php if ($edit_product_category == $category["name"]) echo 'selected="selected"'; ?> value="<?php echo $category['template']; ?>"><?php echo $category['friendly_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover">
                        <tbody class="fields_advanced">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="browse-node-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 id="mySmallModalLabel" class="modal-title"><?php echo $entry_browse_node; ?></h4>
                        </div>
                        <div class="modal-body">
                            <div id="browse-node-content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="well">
            <div class="row">
                <div class="col-md-12 text-right"> <a class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_save; ?>" onclick="validate_and_save('advanced')"><i class="fa fa-save fa-lg"></i></a> <a class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_save_upload; ?>" onclick="save_and_upload()"><i class="fa fa-cloud-upload fa-lg"></i></a> <a class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_saved_listings; ?>" href="<?php echo $saved_listings_url; ?>"><i class="fa fa-copy fa-lg"></i></a> </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function(){
    $('#openstock_selector').change(function() {
        redirectOption($('#openstock_selector').val(), 'advanced');
    });

    <?php if (empty($amazonus_categories)) { ?>
        $("#advanced_table").html("");
        $(".content").prepend('<div id="warning" class="alert alert-danger"><?php echo $error_connecting; ?></div>');
        return;
    <?php } ?>

    $(".fields_advanced :input").bind('change', function() {
        update_form(this, 'advanced');
    });

    $('#category_selector').change(function(){
        var xml = $('#category_selector').val();
        if (xml == '') {
            $('.fields_advanced').empty();
            $('.dynamic-tab').remove();
            $('.dynamic-page').remove();
            return;
        }
        show_form(xml, 'advanced');
    });
    //Update needed if editing
    $('#category_selector').change();
    $('#product_form_advanced input[name=upload_after]').val(false);

});

function redirectOption(varOption, tabOption) {
    var searchLoc = insertParamToUrl(document.location.search, 'sku', varOption);
    searchLoc = insertParamToUrl(searchLoc, 'tab', tabOption);
    searchLoc = searchLoc.substr(1);
    if (document.location.search === searchLoc) {
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
                if (x[1] == value) {
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

function show_form(xml, formType) {
    $('.fields_' + formType).empty();
    $('.dynamic-tab').remove();
    $('.dynamic-page').remove();

    var parserURL = '<?php echo html_entity_decode($template_parser_url); ?>';
    var reqUrl = parserURL + '&xml=' + xml;

    if ($('#openstock_selector').val() !== undefined) {
        reqUrl = reqUrl + '&sku=' + $('#openstock_selector').val();
    }

    $.ajax({
        url: reqUrl,
        data: {},
        dataType: 'json',
        beforeSend: function() {
            $('#category_selector').attr('disabled', 'disabled');
            $('#category_selector_label').after('<a class="btn btn-primary wait" disabled="disabled"><i class="fa fa-cog fa-lg fa-spin"></i> </a>');
        },
        complete: function() {
            $('#category_selector').removeAttr('disabled');
            $('.wait').remove();
        },
        success: function(data) {
            if (data['status'] === 'error') {
                if ('info' in data) {
                    alert(data['info']);
                } else {
                    alert('Unexpected error.');
                }
                return;
            }
            for(tab in data['tabs']) {
                $('#tabs').append('<li class="dynamic-tab"><a href="#page-' + data['tabs'][tab]['id'] + '" data-toggle="tab">' + data['tabs'][tab]['name'] + '</a></li>');

                var pageHtml = '';
                pageHtml += '<div id="page-' + data['tabs'][tab]['id'] + '" class="tab-pane dynamic-page">';
                pageHtml += '<div class="fields_advanced"></div>';
                pageHtml += '</div>'

                $('#tab-content').append(pageHtml);
            }

            var categoryName = data['category'];
            fieldsArray[formType] = data['fields'];

            $('.fields_' + formType).append('<input type="hidden" name="category" value="' + categoryName + '">');

            for (i in fieldsArray[formType]) {
                var row  = '<div class="form-group';


                if (fieldsArray[formType][i]['type'] == 'required') {
                    row += ' required';
                }

                if (fieldsArray[formType][i]['child']){
                    row += ' child_row" display="no" field_index="' + i + '" style="display: none">';
                } else {
                    row += '">';
                }

                row += '<label class="col-sm-2 control-label">'+fieldsArray[formType][i]['title']+'</label>';
                row += '<div class="col-sm-10">';

                row += '<div class="alert alert-danger" id="error_' + fieldsArray[formType][i]['name'] + '" style="display:none;"></div>'

                if (fieldsArray[formType][i]['name'] == "Quantity") {
                    row += getQuantityField(fieldsArray[formType][i]);
                } else if (fieldsArray[formType][i]['accepted']['type'] == "integer") {
                    if (fieldsArray[formType][i]['name'] == 'RecommendedBrowseNode' || fieldsArray[formType][i]['name'] == 'RecommendedBrowseNode2'){
                        row += getBrowseNodeField(fieldsArray[formType][i]);
                    } else {
                        row += getIntegerField(fieldsArray[formType][i]);
                    }
                }
                else if (fieldsArray[formType][i]['accepted']['type'] == "text_area") {
                    row += getTextAreaField(fieldsArray[formType][i]);
                }
                else if (fieldsArray[formType][i]['accepted']['type'] == "select") {
                    row += getSelectField(fieldsArray[formType][i]);
                }
                else if (fieldsArray[formType][i]['accepted']['type'] == "image") {
                    row += getImageField(fieldsArray[formType][i]);
                }
                else {
                    row += getStringField(fieldsArray[formType][i]);
                }

                if (fieldsArray[formType][i]['definition']) {
                    row += '<span class="help-block">' + fieldsArray[formType][i]['definition'] + '</span>';
                }

                row += '</div>';
                row += '</div>';

                $('#page-' + fieldsArray[formType][i]['tab'] + ' .fields_' + formType).append(row);
            }

            //Emulate changes to populate child fields
            $('.fields_' + formType + ' :input').each(function (i) {
                $(this).change();
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        }
    });
}

//Called when chenge to form was made. Shows child rows bassed on input if needed.
function update_form(element, formType) {
    var changedFieldName = $(element).attr('field_name');
    var changedFieldValue = $(element).val();

    $('.fields_' + formType + ' .child_row').each(function (i) {
        var index = $(this).attr('field_index');
        if (fieldsArray[formType][index]['parent']['name'] == changedFieldName) {
            var showChild = false;

            //values is array?
            if (fieldsArray[formType][index]['parent']['value'] instanceof Array) {
                for(i in fieldsArray[formType][index]['parent']['value']) {
                    if (fieldsArray[formType][index]['parent']['value'][i] == changedFieldValue) {
                        showChild = true;
                    }
                }
            } else if (fieldsArray[formType][index]['parent']['value'] == changedFieldValue) {
                showChild = true;
            } else if (fieldsArray[formType][index]['parent']['value'] == '*' && changedFieldValue != '') {
                showChild = true;
            }

            if (showChild) {
                $(this).attr('display', 'yes');
                $(this).removeAttr('style');
            } else {
                $('.fields_' + formType + ' [field_name="' + fieldsArray[formType][index]['name']  + '"]').val('');
                $(this).attr('display', 'no');
                $(this).attr('style', 'display: none');
            }
        }
    });
}

function getImageField(fieldData) {
    var output = "";

    output += '<a class="img-thumbnail img-edit" id="thumb-image-'+fieldData['name']+'">';
    if (fieldData['thumb'] != "") {
        output += '<img src="'+fieldData['thumb']+'" alt="" title="" />';
    } else {
        output += '<i class="fa fa-camera fa-5x"></i>';
    }
    output += "</a>";
    output += '<input type="hidden" id="input-image-'+fieldData['name']+'" name="fields[' + fieldData['name'] + ']" value="' + fieldData['value'] + '" accepted="' + fieldData['accepted']['type'] + '" field_name="' + fieldData['name'] + '" field_type="' + fieldData['type'] + '">';

    return output;
}

function getQuantityField(fieldData) {
    var output = "";

    output += fieldData['value'];
    output += '<input ';
    output += 'type="hidden" ';
    output += 'min="0" ';
    output += 'accepted="' + fieldData['accepted']['type'] + '" ';
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' + fieldData['type'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" ';
    output += 'value="' + fieldData['value'] + '" class="form-control">';

    return output;
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
    output += 'class="form-control" ';
    output += 'value="' + fieldData['value'] + '">';

    return output;
}

function getBrowseNodeField(fieldData) {
    var output = "";

    output += '<div class="input-group col-md-3">';
    output += '<input ';
    output += 'id="'+fieldData['name']+'_input" ';
    output += 'type="number" ';
    output += 'min="0" ';
    output += 'accepted="' + fieldData['accepted']['type'] + '" ';
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' + fieldData['type'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" ';
    output += 'class="form-control" ';
    output += 'onclick="loadBrowseNode(\''+fieldData['name']+'\');" ';
    output += 'value="' + fieldData['value'] + '">';
    output += '<span class="input-group-addon"><i class="fa fa-sitemap fa-lg"></i></span>';
    output += '</div>';
    output += '<span class="label label-info" style="display:none;" id="'+fieldData['name']+'_label"></span>';

    return output;
}

function getTextAreaField(fieldData) {
    var output = "";

    output += '<textarea ';
    if ('min_length' in fieldData['accepted']) {
        output += 'min_length="'+ fieldData['accepted']['min_length'] + '" ';
    }
    if ('max_length' in fieldData['accepted']) {
        output += 'max_length="'+ fieldData['accepted']['max_length'] + '" ';
    }
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' +  fieldData['type'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" class="form-control" rows="3">';
    output += fieldData['value'];
    output += '</textarea>';

    return output;
}

function getStringField(fieldData) {
    var output = "";

    output += '<input type="text"';
    output += 'accepted="' + fieldData['accepted']['type'] + '" ';
    if ('min_length' in fieldData['accepted']) {
        output += 'min_length="'+ fieldData['accepted']['min_length'] + '" ';
    }
    if ('max_length' in fieldData['accepted']) {
        output += 'max_length="'+ fieldData['accepted']['max_length'] + '" ';
    }
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' + fieldData['type'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" ';
    output += 'value="' + fieldData['value'] + '" class="form-control">';

    return output;
}

function getSelectField(fieldData) {
    var output = "";

    output += '<select ';
    output += 'field_name="' + fieldData['name'] + '" ';
    output += 'field_type="' + fieldData['type'] + '" ';
    output += 'name="fields[' + fieldData['name'] + ']" class="form-control">';

    output += '<option></option>';

    if (fieldData['accepted']['option'].length != undefined) {
        for(j in fieldData['accepted']['option']) {
            output += '<option ';

            if (fieldData['value'].toLowerCase() == fieldData['accepted']['option'][j]['value'].toLowerCase()) {
                output += 'selected="selected" ';
            }
            output += 'value="' + fieldData['accepted']['option'][j]['value'] + '">';
            output += fieldData['accepted']['option'][j]['name'];
            output += '</option>';
        }
    }
    else {
        output += '<option ';

        if (fieldData['value'].toLowerCase() == fieldData['accepted']['option']['value'].toLowerCase()) {
            output += 'selected="selected" ';
        }
        output += 'value="' + fieldData['accepted']['option']['value'] + '">';
        output += fieldData['accepted']['option']['name'];
        output += '</option>';
    }
    output += '</select>';
    return output;
}

function validate(formType) {
    var warnings = 0;
    var productIdType;
    var productId;
    var productIdRequired;

    if ($('#category_selector').val() == '') {
        return false;
    }

    $('.fields_' + formType + ' :input').each(function (i) {
        if ($(this).parent().parent().attr('display') === "no") {
            return;
        }

        var field_value = $(this).val();
        var field_name = $(this).attr('field_name');
        var field_type = $(this).attr('field_type');
        var min_length = $(this).attr('min_length');
        var max_length = $(this).attr('max_length');

        if (field_name === 'Type') {
            productIdType = field_value;
        } else if (field_name === 'Value') {
            productId = field_value;
            if (field_type === 'required') {
                productIdRequired = true;
            } else {
                productIdRequired = false;
            }
        }

        if (field_type == 'required' || field_value !== '') {
            if (field_value === '') {
                $('.fields_' + formType + ' #error_' + field_name).text('<?php echo $error_required; ?>').show();
                warnings ++;
            } else if (min_length != undefined && field_value.length < min_length) {
                $('.fields_' + formType + ' #error_' + field_name).text('<?php echo $error_length; ?> ' + min_length + ' <?php echo $text_characters; ?>').show();
                warnings ++;
            } else if (max_length != undefined && field_value.length > max_length) {
                $('.fields_' + formType + ' #error_' + field_name).text((field_value.length - max_length) + ' <?php echo $error_char_limit; ?>').show();
                warnings ++;
            } else {
                $('.fields_' + formType + ' #error_' + field_name).text('').hide();
            }
        }
    });

    if (productIdRequired && productIdType !== 'ASIN' && !isValidProductId(productId)) {
        $('.fields_' + formType + ' :input').each(function (i) {
            var field_name = $(this).attr('field_name');
            if (field_name === 'Value') {
                $('.fields_' + formType + ' #error_' + field_name).text('Not valid product ID!');
                warnings ++;
                return;
            }
        });
    }

    if ($('.fields_' + formType + ' [name="category"]').val() == undefined) {
        warnings ++;
    }

    if (warnings > 0) {
        return false;
    } else {
        return true;
    }
}

function validate_and_save(formType) {
    if (validate(formType)) {
        if (formType == 'advanced') {
            $("#product_form_advanced").submit();
        } else if (formType == 'quick') {
            $("#product_form_quick").submit();
        }
    } else {
        alert('<?php echo $error_not_saved; ?>');
    }
}

function save_and_upload() {
    $('#product_form_advanced input[name=upload_after]').val(true);

    if (validate('advanced')) {
        $("#product_form_advanced").submit();
    } else {
        alert('<?php echo $error_not_saved; ?>');
    }
}

function isValidProductId(value) {
    var barcode = value.substring(0, value.length - 1);
    var checksum = parseInt(value.substring(value.length - 1), 10);
    var calcSum = 0;
    var calcChecksum = 0;
    barcode.split('').map(function(number, index ) {
        number = parseInt(number, 10);
        if (value.length === 13) {
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

var nodeBox = '';
var nodeString = '';
var nodeStringSimple = '';

function loadBrowseNode(field) {
    $('#browse-node-modal').modal('toggle');

    var html = '';

    $('#'+field+'_input').val('');

    nodeString = '';
    nodeStringSimple = '';

    $.ajax({
        url: 'index.php?route=extension/openbay/amazonus_listing/getBrowseNodes&token=<?php echo $token; ?>',
        type: 'POST',
        data: {},
        dataType: 'json',
        beforeSend: function(){
            $('#browse-node-content').empty();
            $('#'+field+'_label').empty().hide();
        },
        success: function(data) {
            if (data.node.error != true){
                html += '<div class="well">';
                html += '<div class="input-group col-md-12">';
                html += '<p><select class="form-control" id="root-node" onchange="nodeSelect(\'root-node\', \''+field+'\');">';
                html += '<option value=""><?php echo $text_select; ?></option>';
                $.each(data.children, function(k,v){
                    html += '<option value="'+ v.node_id+'">'+ v.name+'</option>';
                });
                html += '</select></p>';
                html += '</div>';
                html += '</div>';

                $('#browse-node-content').empty().html(html);
            }else{
                alert(data.node.error);
            }
        },
        failure: function(){
            alert('<?php echo $error_load_nodes; ?>');
        },
        error: function(){
            alert('<?php echo $error_load_nodes; ?>');
        }
    });
}

function nodeSelect(field, original_field) {
    //called when the root node id is chosen
    var html = '';
    var node = $('#'+field).val();
    var parentNodeName = $('#'+field).find(":selected").text();
    nodeStringSimple += parentNodeName+' > ';

    $.ajax({
        url: 'index.php?route=extension/openbay/amazonus_listing/getBrowseNodes&token=<?php echo $token; ?>',
        type: 'POST',
        data: { node: node},
        dataType: 'json',
        beforeSend: function(){
            $('#browse-node-content').empty().html('<a class="btn btn-primary" disabled="disabled"><i class="fa fa-cog fa-lg fa-spin"></i> </a>');
        },
        success: function(data) {
            if (data.node.error != true){
                html += '<div class="row">';
                html += '<div class="col-sm-12 text-left">';
                html += '<h4>'+nodeStringSimple+'</h4>';
                html += '</div>';
                html += '</div>';
                if (data.node.final == 0){
                    html += '<div class="well">';
                    html += '<div class="input-group col-md-12">';
                    html += '<p><select class="form-control" id="'+field+'-'+node+'" onchange="nodeSelect(\''+field+'-'+node+'\', \''+original_field+'\');">';
                    html += '<option value=""><?php echo $text_select; ?></option>';
                    $.each(data.children, function(k,v){
                        html += '<option value="'+ v.node_id+'">'+ v.name+'</option>';
                    });
                    html += '</select></p>';
                    html += '</div>';
                    html += '</div>';
                }else{
                    html += '<div class="row">';
                    html += '<div class="col-sm-12 text-right">';
                    html += '<a onclick="saveNode('+data.node.id+', \''+original_field+'\', \''+nodeStringSimple+'\')" class="btn btn-primary"><i class="fa fa-save fa-lg"></i> <?php echo $button_save; ?></a>';
                    html += '</div>';
                    html += '</div>';
                }

                $('#browse-node-content').empty().html(html);
            }else{
                alert(data.node.error);
            }
        },
        failure: function(){
            alert('<?php echo $error_load_nodes; ?>');
        },
        error: function(){
            alert('<?php echo $error_load_nodes; ?>');
        }
    });
}

function saveNode(id, field, text){
    $('input[field_name='+field+']').val(id);
    $('#'+field+'_label').text(text).show();
    $('#browse-node-modal').modal('toggle');
}
//--></script>
<?php echo $footer; ?>
