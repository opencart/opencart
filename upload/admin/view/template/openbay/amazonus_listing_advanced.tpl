<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <?php if ($has_listing_errors) { ?>
        <a href="<?php echo $url_remove_errors; ?>" data-toggle="tooltip" title="<?php echo $button_remove_error; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
        <li><i class="fa fa-exclamation-circle"></i> <?php echo $listing_error ?></li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
    <?php } ?>
    <div id="tabs" class="htabs"> <a href="#page-main"><?php echo $tab_main; ?></a>
      <div id="dynamic_tabs"></div>
    </div>
    <form method="POST" id="product_form_advanced">
      <div id="page-main">
        <table class="form" align="left">
          <tbody>
            <tr>
              <td style="width: 400px;"><?php echo $entry_product; ?></td>
              <td><a href="<?php echo $listing_url; ?>"><?php echo $listing_name; ?>
                <?php if (!empty($options)) { echo " : "; } ?>
                </a>
                <?php if (!empty($options)) { ?>
                <select id="openstock_selector" name="optionVar">
                  <option></option>
                  <?php foreach($options as $option) { ?>
                  <option <?php if ($variation === $option['var']) { echo "selected='selected'";} ?> value="<?php echo  $option['var']?>"><?php echo $option['combi']?></option>
                  <?php } ?>
                </select>
                <?php }?></td>
            </tr>
            <tr>
              <td><?php echo $entry_category; ?><br>
                <span class="help"></span></td>
              <td><select id="category_selector">
                  <option value=""></option>
                  <?php foreach($amazonus_categories as $category) {  ?>
                  <option <?php if ($edit_product_category == $category["name"]) echo 'selected="selected"'; ?> value="<?php echo $category['template'] ?>"><?php echo $category['friendly_name'] ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </tbody>
          <input type="hidden" name="upload_after" value="false">
          <tbody class="fields_advanced">
          </tbody>
        </table>
      </div>
      <div id="dynamic_pages"> </div>
      <div id="greyScreen"></div>
      <div id="browseNodeForm" class="greyScreenBox nodePage">
        <div class="bold border p5 previewClose">X</div>
        <div id="browseNodeFormContent"></div>
      </div>
    </form>
    <div class="well">
      <div class="row">
        <div class="col-md-12 text-right"> <a class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_save ?>" onclick="validate_and_save('advanced')"><i class="fa fa-save fa-lg"></i></a> <a class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_save_upload ?>" onclick="save_and_upload()"><i class="fa fa-cloud-upload fa-lg"></i></a> <a class="btn btn-primary" data-toggle="tooltip" title="<?php echo $button_saved_listings ?>" href="<?php echo $saved_listings_url; ?>"><i class="fa fa-copy fa-lg"></i></a> </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function(){
    $('#openstock_selector').change(function() {
        redirectOption($('#openstock_selector').attr('value'), 'advanced');
    });

    <?php if (empty($amazonus_categories)) { ?>
        $("#advanced_table").html("");
        $(".content").prepend('<div id="warning" class="warning"><?php echo $error_connecting; ?></div>');
        return;
    <?php } ?>

    $(".fields_advanced :input").bind('change', function() {
        update_form(this, 'advanced');
    });

    $('#category_selector').change(function(){

        var xml = $('#category_selector').attr('value');
        if (xml == '') {
            $('.fields_advanced').empty();
            $('#dynamic_tabs').empty();
            $('#dynamic_pages').empty()
            return;
        }
        show_form(xml, 'advanced');
    });
    //Update needed if editing
    $('#category_selector').change();
    $('#product_form_advanced input[name=upload_after]').val(false);

});

function redirectOption(varOption, tabOption) {
    var searchLoc = insertParamToUrl(document.location.search, 'var', varOption);
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

//formType = 'quick' or 'advanced'
function show_form(xml, formType) {
    $('.fields_' + formType).empty();
    $('#dynamic_tabs').empty();
    $('#dynamic_pages').empty();

    var parserURL = '<?php echo html_entity_decode($template_parser_url) ?>';
    var reqUrl = parserURL + '&xml=' + xml;

    if ($('#openstock_selector').attr('value') !== undefined) {
        reqUrl = reqUrl + '&var=' + $('#openstock_selector').attr('value');
    }

    $.ajax({
        url: reqUrl,
        data: {},
        dataType: 'json',
        beforeSend: function() {
            $('#category_selector').attr('disabled', true);
            $('#category_selector').after('<span class="wait"><img src="view/image/loading.gif" alt="" />&nbsp;</span>');
        },
        complete: function() {
            $('#category_selector').attr('disabled', false);
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
                $('#dynamic_tabs').append('<a href="#page-' + data['tabs'][tab]['id'] + '">' + data['tabs'][tab]['name'] + '</a>');

                var pageHtml = '<div id="page-' + data['tabs'][tab]['id'] + '"><table class="form" align="left">';
                pageHtml += '<tbody class="fields_advanced"></tbody>';
                pageHtml += '</table></div>'

                $('#dynamic_pages').append(pageHtml);
            }

            var categoryName = data['category'];
            fieldsArray[formType] = data['fields'];

            $('.fields_' + formType).append('<input type="hidden" name="category" value="' + categoryName + '">');

            for (i in fieldsArray[formType]) {
                var row  = "";
                if (fieldsArray[formType][i]['child']){
                    row += '<tr class="child_row" display="no" field_index="' + i + '" style="display: none">';
                } else {
                    row += '<tr>';
                }

                row += '<td>';
                if (fieldsArray[formType][i]['type'] == 'required') {
                    row += '<span class="required">* </span>';
                }
                row += fieldsArray[formType][i]['title'];
                row += '<span class="help">' + fieldsArray[formType][i]['definition'] + '</span>';
                row += '</td>';
                row += '<td>';


                if (fieldsArray[formType][i]['name'] == "Quantity") {
                    row += getQuantityField(fieldsArray[formType][i]);
                } else if (fieldsArray[formType][i]['accepted']['type'] == "integer") {
                    row += getIntegerField(fieldsArray[formType][i]);
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

                row += '<span class="required" id="error_' + fieldsArray[formType][i]['name'] + '"></span>'

                row += '</td>';
                row += '</tr>';

                $('#page-' + fieldsArray[formType][i]['tab'] + ' .fields_' + formType).append(row);
            }

            //Emulate changes to populate child fields
            $('.fields_' + formType + ' :input').each(function (i) {
                $(this).change();
            });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        }
    });
}

//Called when chenge to form was made. Shows child rows bassed on input if needed.
function update_form(element, formType) {
    var changedFieldName = $(element).attr('field_name');
    var changedFieldValue = $(element).attr('value');

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
                $('.fields_' + formType + ' [field_name="' + fieldsArray[formType][index]['name']  + '"]').attr('value', '');
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
    if (fieldData['value'] === "") {
        output += 'src="<?php echo $no_image; ?>"';
    } else if (fieldData['thumb'] !== "") {
        output += 'src="' + fieldData['thumb'] + '"';
    } else {
        output += 'src="' + fieldData['value'] + '"';
    }
    output += "/>";
    output += '<br />';

    output += '<a onclick="image_upload(\'imagefield_' + fieldData['name'] + '\', \'thumb_' + fieldData['name'] + '\')"><?php echo $button_browse; ?></a>';
    output += '  |  ';
    output += '<a onclick="cleaImageField(\'' + fieldData['name'] + '\')"><?php echo $button_clear; ?></a>';
    output += "</div>";

    return output;
}

function cleaImageField(fieldName) {
    $('#imagefield_' + fieldName).attr('value', '');
    $('#thumb_' + fieldName).attr('src', '<?php echo $no_image; ?>');
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
    output += 'value="' + fieldData['value'] + '">';

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
    if (fieldData['name'] == 'RecommendedBrowseNode' || fieldData['name'] == 'RecommendedBrowseNode2'){
        output += 'class="browseNode" ';
    }
    output += 'value="' + fieldData['value'] + '">';

    return output;
}

function getTextAreaField(fieldData) {
    var output = "";

    output += '<textarea ';
    output += 'rows="5" ';
    output += 'cols="60" ';
    if ('min_length' in fieldData['accepted']) {
        output += 'min_length="'+ fieldData['accepted']['min_length'] + '" ';
    }
    if ('max_length' in fieldData['accepted']) {
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
    if ('min_length' in fieldData['accepted']) {
        output += 'min_length="'+ fieldData['accepted']['min_length'] + '" ';
    }
    if ('max_length' in fieldData['accepted']) {
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
                        if (data != "") {
                            $('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
                            var imageUrl = $('#' + field).attr('value');
                            $('#' + field).attr('value', '<?php echo HTTPS_CATALOG; ?>image/' + imageUrl);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
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

function validate(formType) {
    if ($('#category_selector').val() == '') {
        return false;
    }

    var warnings = 0;
    var productIdType;
    var productId;
    var productIdRequired;

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
                $('.fields_' + formType + ' #error_' + field_name).text('<?php echo $error_required ?>');
                warnings ++;
            }
            else if (min_length != undefined && field_value.length < min_length) {
                $('.fields_' + formType + ' #error_' + field_name).text('<?php echo $error_length; ?> ' + min_length + ' <?php echo $text_characters; ?>');
                warnings ++;
            }
            else if (max_length != undefined && field_value.length > max_length) {
                $('.fields_' + formType + ' #error_' + field_name).text((field_value.length - max_length) + ' <?php echo $error_char_limit; ?>');
                warnings ++;
            }
            else {
                $('.fields_' + formType + ' #error_' + field_name).text('');
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

    if ($('.fields_' + formType + ' [name="category"]').attr('value') == undefined) {
        warnings ++;
    }

    if (warnings > 0) {
        return false;
    } else {
        return true;
    }
}

//form = 'quick' or 'advanced'
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

$('.browseNode').bind('click', function(e){
  e.preventDefault();

  var html = '';

  nodeBox = $(this).attr("field_name");
  $('#'+nodeBox+'_text').remove();
  $(this).val('');

  nodeString = '';
  nodeStringSimple = '';

  $.ajax({
      url: 'index.php?route=openbay/amazonus_listing/getBrowseNodes&token=<?php echo $token; ?>',
      type: 'POST',
      dataType: 'json',
      beforeSend: function(){
          $('#browseNodeFormContent').empty();
          showGreyScreen('browseNodeForm');
      },
      success: function(data) {
          if (data.node.error != true){
              html += '<select class="nodeSelect mTop20 width250">';
              html += '<option value=""><?php echo $text_select; ?></option>';

              $.each(data.children, function(k,v){
                  html += '<option value="'+ v.node_id+'">'+ v.name+'</option>';
              });

              html += '</select><br />';

              $('#browseNodeFormContent').html(html);
          }else{
              alert(data.node.error);
              hideGreyScreen('browseNodeForm');
          }
      },
      failure: function(){
          alert('<?php echo $error_load_nodes; ?>');
          hideGreyScreen('browseNodeForm');
      },
      error: function(){
          alert('<?php echo $error_load_nodes; ?>');
          hideGreyScreen('browseNodeForm');
      }
  });
});

$('.nodeSelect').bind('change', function(e){
  e.preventDefault();

  //called when the root node id is chosen
  var html = '';
  var node = $(this).val();
  var parentNodeName = $(this).find(":selected").text();
  nodeString += '<h3>'+parentNodeName+' ></h3>';
  nodeStringSimple += parentNodeName+' > ';

  $.ajax({
      url: 'index.php?route=openbay/amazonus_listing/getBrowseNodes&token=<?php echo $token; ?>',
      type: 'POST',
      data: { node: node},
      dataType: 'json',
      beforeSend: function(){
          $('#browseNodeFormContent select').remove();
          $('#browseNodeFormContent').append('<img src="view/image/loading.gif" alt="" />');
      },
      success: function(data) {
          if (data.node.error != true){
              if (data.node.final == 0){
                  html += '<select class="nodeSelect mTop20 width250">';
                  html += '<option value=""><?php echo $text_select; ?></option>';

                  $.each(data.children, function(k,v){
                      html += '<option value="'+ v.node_id+'">'+ v.name+'</option>';
                  });

                  html += '</select>';
              }else{
                  html += '<a onclick="saveNode('+data.node.id+')" class="button"><?php echo $button_save; ?></a>';
              }

              $('#browseNodeFormContent').html(nodeString+html);
          }else{
              alert(data.node.error);
              hideGreyScreen('browseNodeForm');
          }
      },
      failure: function(){
          alert('<?php echo $error_load_nodes; ?>');
          hideGreyScreen('browseNodeForm');
      },
      error: function(){
          alert('<?php echo $error_load_nodes; ?>');
          hideGreyScreen('browseNodeForm');
      }
  });
});

function saveNode(id){
    $('input[field_name='+nodeBox+']').val(id);
    $('input[field_name='+nodeBox+']').after('<span id="'+nodeBox+'_text" style="margin-left:15px;">'+nodeStringSimple+'</span>');
    hideGreyScreen('browseNodeForm');
}

//--></script> 
<?php echo $footer; ?>