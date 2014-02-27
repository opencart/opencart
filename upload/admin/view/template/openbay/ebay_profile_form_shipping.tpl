<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn" onclick="$('#form').submit();"><i class="fa fa-check-circle"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-file-text fa-lg"></i> <?php echo $text_title_list; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $btn_save; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
        <input type="hidden" name="type" value="<?php echo $type; ?>" />
        <input type="hidden" name="ebay_profile_id" value="<?php echo $ebay_profile_id; ?>" />
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_profile_default; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="default" value="0" />
                <input type="checkbox" name="default" value="1" <?php if($default == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="name"><?php echo $text_profile_name; ?></label>
              <div class="col-sm-10">
                <input type="text" name="name" value="<?php if(isset($name)){ echo $name; } ?>" placeholder="<?php echo $text_profile_name; ?>" id="name" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="description"><?php echo $text_profile_desc; ?></label>
              <div class="col-sm-10">
                <textarea name="description" class="form-control" rows="3" id="description"><?php if(isset($description)){ echo $description; } ?></textarea>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-shipping">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_postcode; ?></label>
              <div class="col-sm-10">
                <input type="text" name="data[postcode]" id="postcode" value="<?php if(isset($data['postcode'])){ echo $data['postcode']; } ?>" placeholder="<?php echo $text_shipping_postcode; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_location; ?></label>
              <div class="col-sm-10">
                <input type="text" name="data[location]" id="location" value="<?php if(isset($data['location'])){ echo $data['location']; } ?>" placeholder="<?php echo $text_shipping_location; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_dispatch_country; ?></label>
              <div class="col-sm-10">
                <select name="data[country]" class="form-control" id="country">
                  <?php foreach($setting['countries'] as $country){ ?>
                    <?php echo '<option value="'.$country['code'].'"'.(isset($data['country']) && $data['country'] == $country['code'] ? ' selected' : '').'>'.$country['name'].'</option>'; ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_despatch; ?></label>
              <div class="col-sm-10">
                <select name="data[dispatch_time]" class="form-control" id="dispatch_time">
                  <?php foreach($setting['dispatch_times'] as $dis){ ?>
                    <?php echo '<option value="'.$dis['DispatchTimeMax'].'"'.(isset($data['dispatch_time']) && $data['dispatch_time'] == $dis['DispatchTimeMax'] ? ' selected' : '').'>'.$dis['Description'].'</option>'; ?>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $text_shipping_despatch_help; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_in_desc; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[shipping_in_desc]" value="0" />
                <input type="checkbox" name="data[shipping_in_desc]" value="1" id="shipping_in_desc" <?php if(isset($data['shipping_in_desc']) && $data['shipping_in_desc'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_getitfast; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[get_it_fast]" value="0" />
                <input type="checkbox" name="data[get_it_fast]" value="1" id="get_it_fast" <?php if(isset($data['get_it_fast']) && $data['get_it_fast'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-2">
                <div class="row">
                  <div class="col-sm-12 text-right">
                    <p><label class="control-label text-right"><?php echo $text_shipping_nat; ?></label></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 text-right">
                    <p><a class="btn btn-primary" id="add-shipping-national"><i class="fa fa-plus-circle"></i> <?php echo $text_btn_add; ?></a></p>
                  </div>
                </div>
              </div>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-12" id="national-shipping-options">
                    <input type="hidden" name="data[count_national]" value="<?php echo $data['shipping_national_count']; ?>" id="count_national" />
                    <?php if(isset($data['shipping_national']) && is_array($data['shipping_national'])){ ?>
                      <?php foreach($data['shipping_national'] as $key => $service){ ?>
                        <div class="well shipping_national_<?php echo $key; ?>">
                          <div class="row">
                            <div class="col-sm-2">
                              <label class="control-label"><?php echo $text_shipping_service; ?><label>
                            </div>
                            <div class="col-sm-10">
                              <input type="hidden" name="data[service_national][<?php echo $key; ?>]" value="<?php echo $service['id']; ?>" /> <?php echo $service['name']; ?>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-2">
                              <label class="control-label"><?php echo $text_shipping_first; ?></label>
                            </div>
                            <div class="col-sm-2">
                              <input type="text" name="data[price_national][<?php echo $key; ?>]" class="form-control" value="<?php echo $service['price']; ?>" />
                            </div>
                            <div class="col-sm-2">
                              <label class="control-label"><?php echo $text_shipping_add; ?></label>
                            </div>
                            <div class="col-sm-2">
                              <input type="text" name="data[priceadditional_national][<?php echo $key; ?>]" class="form-control" value="<?php echo $service['additional']; ?>" />
                            </div>
                            <div class="col-sm-2">
                              <a onclick="removeShipping('national','<?php echo $key; ?>');" class="button"><span><?php echo $text_btn_remove; ?></span></a>
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-2">
                <div class="row">
                  <div class="col-sm-12 text-right">
                    <p><label class="control-label text-right"><?php echo $text_shipping_intnat; ?></label></p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 text-right">
                    <p><a class="btn btn-primary" id="add-shipping-international"><i class="fa fa-plus-circle"></i> <?php echo $text_btn_add; ?></a></p>
                  </div>
                </div>
              </div>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-12" id="international-shipping-options">
                    <input type="hidden" name="data[count_international]" value="<?php echo $data['shipping_international_count']; ?>" id="count_international" />
                    <?php if(isset($data['shipping_international']) && is_array($data['shipping_international'])){ ?>
                      <?php foreach($data['shipping_international'] as $key => $service){ ?>
                        <div class="well">
                          <p class="shipping_international_<?php echo $key; ?>"><label><strong><?php echo $text_shipping_service; ?></strong><label>

                          <input type="hidden" name="data[service_international][<?php echo $key; ?>]" value="<?php echo $service['id']; ?>" /> <?php echo $service['name']; ?></p>
<?php
                          echo'<h5 style="margin:5px 0;" class="shipping_international_'.$key.'">Ship to zones</h5>';
                          echo'<div style="border:1px solid #000; background-color:#F5F5F5; width:100%; min-height:40px; margin-bottom:10px; display:inline-block;" class="shipping_international_'.$key.'">';

                          echo'<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
                            echo'<input type="checkbox" name="data[shipto_international]['.$key.'][]" value="Worldwide" '.(in_array("Worldwide", $service["shipto"]) ? ' checked="checked"' : '').'/> Worldwide</div>';

                          foreach($shipping_international_zones as $zone){
                          echo'<div style="display:inline; float:left; padding:10px 6px;line-height:20px; height:20px;">';
                            echo'<input type="checkbox" name="data[shipto_international]['.$key.'][]" value="'.$zone['shipping_location'].'" ';
                            if(in_array($zone['shipping_location'], $service['shipto'])){ echo' checked="checked"'; }
                            echo'/> '.$zone['description'].'</div>';
                          }
                          echo'</div>';

                          echo'<div style="clear:both;" class="shipping_international_'.$key.'"></div>';
                          echo'<p class="shipping_international_'.$key.'"><label>'.$text_shipping_first.'</label>';
                          echo'<input type="text" name="data[price_international]['.$key.']" style="width:50px;" value="'.$service['price'].'" />';
                          echo'&nbsp;&nbsp;<label>'.$text_shipping_add.'</label>';
                          echo'<input type="text" name="data[priceadditional_international]['.$key.']" style="width:50px;" value="'.$service['additional'].'" />&nbsp;&nbsp;<a onclick="removeShipping(\'international\',\''.$key.'\');" class="button"><span>'.$text_btn_remove.'</span></a></p>';
                          echo'<div style="clear:both;"></div>'; ?>
                        </div>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#add-shipping-national').bind('click', function(){
  addShipping('national');
});
$('#add-shipping-international').bind('click', function(){
  addShipping('international');
});

  function addShipping(id) {
      if (id == 'national') {
          var loc = '0';
      } else {
          var loc = '1';
      }

      var count = $('#count_' + id).val();
      count = parseInt(count);

      $.ajax({
        url: 'index.php?route=openbay/ebay/getShippingService&token=<?php echo $token; ?>&loc=' + loc,
        beforeSend: function(){
          $('#add-shipping-'+id).empty().html('<i class="fa fa-refresh fa-spin"></i>').attr('disabled','disabled');
        },
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          html = '';
          html += '<div class="well shipping_' + id + '_' + count + '">';
            html += '<div class="row form-group">';
              html += '<div class="col-sm-1 text-right">';
                html += '<label class="control-label"><?php echo $text_shipping_service; ?><label>';
              html += '</div>';
              html += '<div class="col-sm-11">';
                html += '<select name="data[service_' + id + '][' + count + ']" class="form-control">';
                  $.each(data.svc, function(key, val) {
                    html += '<option value="' + val.ShippingService + '">' + val.description + '</option>';
                  });
                html += '</select>';
              html += '</div>';
            html += '</div>';
            if (id == 'international') {
              html += '<div class="row form-group">';
                html += '<div class="col-sm-1 text-right">';
                  html += '<label class="control-label"><?php echo $text_shipping_zones; ?></label>';
                html += '</div>';
                html += '<div class="col-sm-10">';
                  html += '<label class="checkbox-inline">';
                    html += '<input type="checkbox" name="data[shipto_international][' + count + '][]" value="Worldwide" />';
                    html += ' <?php echo $text_shipping_worldwide; ?>';
                  html += '</label>';
                  <?php foreach($shipping_international_zones as $zone) { ?>
                    html += '<label class="checkbox-inline">';
                      html += '<input type="checkbox" name="data[shipto_international][' + count + '][]" value="<?php echo $zone['shipping_location']; ?>" />';
                      html += ' <?php echo $zone['description']; ?>';
                    html += '</label>';
                  <?php } ?>
                html += '</div>';
              html += '</div>';
            }
            html += '<div class="row form-group">';
              html += '<div class="col-sm-1 text-right">';
                html += '<label class="control-label"><?php echo $text_shipping_first; ?></label>';
              html += '</div>';
              html += '<div class="col-sm-2">';
                html += '<input type="text" name="data[price_' + id + '][' + count + ']" class="form-control" value="0.00" class="form-control" />';
              html += '</div>';
              html += '<div class="col-sm-1 text-right">';
                html += '<label class="control-label"><?php echo $text_shipping_add; ?></label>';
              html += '</div>';
              html += '<div class="col-sm-2">';
                html += '<input type="text" name="data[priceadditional_' + id + '][' + count + ']" class="form-control" value="0.00" />';
              html += '</div>';
              html += '<div class="col-sm-2">';
                html += '<a onclick="removeShipping(\'' + id + '\',\'' + count + '\');" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $text_btn_remove; ?></a>';
              html += '</div>';
            html += '</div>';
          html += '</div>';

          $('#' + id + '-shipping-options').append(html);

          $('#add-shipping-'+id).empty().html('<i class="fa fa-plus-circle"></i> <?php echo $text_btn_add; ?>').removeAttr('disabled');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          $('#add-shipping-'+id).empty().html('<i class="fa fa-plus-circle"></i> <?php echo $text_btn_add; ?>').removeAttr('disabled');
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });

      $('#count_' + id).val(count + 1);
    }

  function removeShipping(id, count) {
    $('.shipping_' + id + '_' + count).remove();
  }

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