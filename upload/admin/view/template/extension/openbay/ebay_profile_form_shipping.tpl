<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" onclick="$('#form').submit();"><i class="fa fa-check-circle"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_manage; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
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
                <input type="checkbox" name="default" value="1" <?php if ($default == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="name"><?php echo $text_profile_name; ?></label>
              <div class="col-sm-10">
                <input type="text" name="name" value="<?php if (isset($name)){ echo $name; } ?>" placeholder="<?php echo $text_profile_name; ?>" id="name" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="description"><?php echo $text_profile_desc; ?></label>
              <div class="col-sm-10">
                <textarea name="description" class="form-control" rows="3" id="description"><?php if (isset($description)){ echo $description; } ?></textarea>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-shipping">
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_postcode; ?></label>
              <div class="col-sm-10">
                <input type="text" name="data[postcode]" id="postcode" value="<?php if (isset($data['postcode'])){ echo $data['postcode']; } ?>" placeholder="<?php echo $text_shipping_postcode; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_location; ?></label>
              <div class="col-sm-10">
                <input type="text" name="data[location]" id="location" value="<?php if (isset($data['location'])){ echo $data['location']; } ?>" placeholder="<?php echo $text_shipping_location; ?>" class="form-control" />
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
              <label class="col-sm-2 control-label"><?php echo $text_shipping_getitfast; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[get_it_fast]" value="0" />
                <input type="checkbox" name="data[get_it_fast]" value="1" id="get_it_fast" <?php if (isset($data['get_it_fast']) && $data['get_it_fast'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <?php if (isset($setting['listing_restrictions']['eligible_for_pickup_dropoff']) && $setting['listing_restrictions']['eligible_for_pickup_dropoff'] == 1) { ?>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_shipping_pickupdropoff; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[eligible_for_pickup_dropoff]" value="0" />
                <input type="checkbox" name="data[eligible_for_pickup_dropoff]" value="1" id="eligible_for_pickup_dropoff" <?php if (isset($data['eligible_for_pickup_dropoff']) && $data['eligible_for_pickup_dropoff'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>
            <?php } ?>
            <?php if (isset($setting['listing_restrictions']['eligible_for_pickup_instore']) && $setting['listing_restrictions']['eligible_for_pickup_instore'] == 1) { ?>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_shipping_pickupinstore; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[eligible_for_pickup_instore]" value="0" />
                <input type="checkbox" name="data[eligible_for_pickup_instore]" value="1" id="eligible_for_pickup_instore" <?php if (isset($data['eligible_for_pickup_instore']) && $data['eligible_for_pickup_instore'] == 1){ echo 'checked="checked"'; } ?>/>
              </div>
            </div>
            <?php } ?>
            <?php if (isset($setting['listing_restrictions']['global_shipping']) && $setting['listing_restrictions']['global_shipping'] == 1) { ?>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_shipping_global_shipping; ?></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[global_shipping]" value="0" />
                <input type="checkbox" name="data[global_shipping]" value="1" id="global_shipping" <?php if (isset($data['global_shipping']) && $data['global_shipping'] == 1){ echo 'checked="checked"'; } ?>/>
              </div>
            </div>
            <?php } ?>
            <?php if ($cod_surcharge == 1) { ?>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_cod; ?></label>
              <div class="col-sm-10">
                <input type="text" name="data[cod_cost]" value="<?php if (isset($data['cod_cost'])){ echo $data['cod_cost']; } ?>" placeholder="<?php echo $text_shipping_cod; ?>" class="form-control" />
              </div>
            </div>
            <?php } ?>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_type_nat; ?></label>
              <div class="col-sm-10">
                <select name="data[national][shipping_type]" class="form-control" id="shipping-type-national">
                  <?php echo $setting['shipping_types']['flat'] == 1 ? '<option value="flat"'.(isset($data['national']['shipping_type']) && $data['national']['shipping_type'] == 'flat' ? ' selected' : '').'>'.$text_shipping_flat.'</option>' : ''; ?>
                  <?php echo $setting['shipping_types']['calculated'] == 1 ? '<option value="calculated"'.(isset($data['national']['shipping_type']) && $data['national']['shipping_type'] == 'calculated' ? ' selected' : '').'>'.$text_shipping_calculated.'</option>' : ''; ?>
                  <?php echo $setting['shipping_types']['freight'] == 1 ? '<option value="freight"'.(isset($data['national']['shipping_type']) && $data['national']['shipping_type'] == 'freight' ? ' selected' : '').'>'.$text_shipping_freight.'</option>' : ''; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_shipping_promotion_discount; ?>"><?php echo $entry_shipping_promotion_discount; ?></span></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[promotional_shipping_discount]" value="0" />
                <input type="checkbox" name="data[promotional_shipping_discount]" value="1" id="promotional_shipping_discount" <?php if (isset($data['promotional_shipping_discount']) && $data['promotional_shipping_discount'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>

            <div id="national-container-flat" style="display:none;" class="shipping-national-container">
              <div class="form-group">
                <div class="col-sm-2">
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <p><label class="control-label text-right"><?php echo $text_shipping_nat; ?></label></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <p><a class="btn btn-primary" onclick="addShipping('national', 'flat');" id="add-national-flat"><i class="fa fa-plus-circle"></i> <?php echo $button_add; ?></a></p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-12" id="options-national-flat"><?php echo $html_national_flat; ?></div>
                  </div>
                </div>
              </div>
            </div>

            <?php if ($setting['shipping_types']['calculated'] == 1) { ?>
            <div id="national-container-calculated" style="display:none;" class="shipping-national-container">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_shipping_handling_nat; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="data[national][calculated][handling_fee]" id="national-handling-fee" class="form-control" value="<?php echo $data['national']['calculated']['handling_fee']; ?>" />
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
                      <p><a class="btn btn-primary" onclick="addShipping('national', 'calculated');" id="add-national-calculated"><i class="fa fa-plus-circle"></i> <?php echo $button_add; ?></a></p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-12" id="options-national-calculated"><?php echo $html_national_calculated; ?></div>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>

            <?php if ($setting['shipping_types']['freight'] == 1) { ?>
            <div id="national-container-freight" style="display:none;" class="shipping-national-container">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_shipping_in_desc; ?></label>
                <div class="col-sm-10">
                  <input type="hidden" name="data[national][freight][in_description]" value="0" />
                  <input type="checkbox" name="data[national][freight][in_description]" value="1" <?php if (isset($data['national']['freight']['in_description']) && $data['national']['freight']['in_description'] == 1){ echo 'checked="checked"'; } ?> />
                </div>
              </div>
            </div>
            <?php } ?>

            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $text_shipping_type_int; ?></label>
              <div class="col-sm-10">
                <select name="data[international][shipping_type]" class="form-control" id="shipping-type-international">
                  <?php echo $setting['shipping_types']['flat'] == 1 ? '<option value="flat"'.(isset($data['international']['shipping_type']) && $data['international']['shipping_type'] == 'flat' ? ' selected' : '').'>'.$text_shipping_flat.'</option>' : ''; ?>
                  <?php echo $setting['shipping_types']['calculated'] == 1 ? '<option value="calculated"'.(isset($data['international']['shipping_type']) && $data['international']['shipping_type'] == 'calculated' ? ' selected' : '').'>'.$text_shipping_calculated.'</option>' : ''; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_shipping_promotion_discount_international; ?>"><?php echo $entry_shipping_promotion_discount_international; ?></span></label>
              <div class="col-sm-10">
                <input type="hidden" name="data[promotional_shipping_discount_international]" value="0" />
                <input type="checkbox" name="data[promotional_shipping_discount_international]" value="1" id="promotional_shipping_discount_international" <?php if (isset($data['promotional_shipping_discount_international']) && $data['promotional_shipping_discount_international'] == 1){ echo 'checked="checked"'; } ?> />
              </div>
            </div>

            <div id="international-container-flat" style="display:none;" class="shipping-international-container">
              <div class="form-group">
                <div class="col-sm-2">
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <p><label class="control-label text-right"><?php echo $text_shipping_intnat; ?></label></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <p><a class="btn btn-primary" onclick="addShipping('international', 'flat');" id="add-international-flat"><i class="fa fa-plus-circle"></i> <?php echo $button_add; ?></a></p>
                    </div>
                  </div>
                </div>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-12" id="options-international-flat">
                      <?php echo $html_international_flat; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php if ($setting['shipping_types']['calculated'] == 1) { ?>
              <div id="international-container-calculated" style="display:none;" class="shipping-international-container">
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $text_shipping_handling_nat; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="data[international][calculated][handling_fee]" id="international-handling-fee" class="form-control" value="<?php echo $data['international']['calculated']['handling_fee']; ?>" />
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
                        <p><a class="btn btn-primary" onclick="addShipping('international', 'calculated');" id="add-international-calculated"><i class="fa fa-plus-circle"></i> <?php echo $button_add; ?></a></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-10">
                    <div class="row">
                      <div class="col-sm-12" id="options-international-calculated">
                        <?php echo $html_international_calculated; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  $(document).ready(function() {
    changeNationalType();
    changeInternationalType();
  });

  $('#shipping-type-national').bind('change', function() {
    changeNationalType();
  });

  $('#shipping-type-international').bind('change', function() {
    changeInternationalType();
  });

  function changeNationalType() {
    var shipping_type = $('#shipping-type-national').val();

    $('.shipping-national-container').hide();
    $('#national-container-'+shipping_type).fadeIn();
  }

  function changeInternationalType() {
    var shipping_type = $('#shipping-type-international').val();

    $('.shipping-international-container').hide();
    $('#international-container-'+shipping_type).fadeIn();
  }

  function addShipping(id, type) {
    if (id == 'national') {
        var loc = '0';
    } else {
        var loc = '1';
    }

    var count = $('#' + type + '_count_' + id).val();
    count = parseInt(count);

    $.ajax({
      url: 'index.php?route=extension/openbay/ebay/getShippingService&token=<?php echo $token; ?>&loc=' + loc + '&type=' + type,
      beforeSend: function(){
        $('#add-' + id + '-' + type).empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>').attr('disabled','disabled');
      },
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        html = '';
        html += '<div class="well" id="' + id + '_' + type + '_' + count + '">';
          html += '<div class="row form-group">';
            html += '<div class="col-sm-1 text-right">';
              html += '<label class="control-label"><?php echo $text_shipping_service; ?><label>';
            html += '</div>';
            html += '<div class="col-sm-11">';
              html += '<select name="data[' + id + '][' + type + '][service_id][' + count + ']" class="form-control">';
                $.each(data.service, function(key, val) {
                  html += '<option value="' + key + '">' + val.description + '</option>';
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
                  html += '<input type="checkbox" name="data[' + id + '][' + type + '][shipto][' + count + '][]" value="Worldwide" />';
                  html += ' <?php echo $text_shipping_worldwide; ?>';
                html += '</label>';
                <?php foreach($shipping_international_zones as $zone) { ?>
                  html += '<label class="checkbox-inline">';
                    html += '<input type="checkbox" name="data[' + id + '][' + type + '][shipto][' + count + '][]" value="<?php echo $zone['shipping_location']; ?>" />';
                    html += ' <?php echo $zone['description']; ?>';
                  html += '</label>';
                <?php } ?>
              html += '</div>';
            html += '</div>';
          }
          html += '<div class="row form-group">';
            if (type != 'calculated') {
              html += '<div class="col-sm-1 text-right">';
                html += '<label class="control-label"><?php echo $text_shipping_first; ?></label>';
              html += '</div>';
              html += '<div class="col-sm-3">';
                html += '<input type="text" name="data[' + id + '][' + type + '][price][' + count + ']" class="form-control" value="0.00" class="form-control" />';
              html += '</div>';
              html += '<div class="col-sm-2 text-right">';
                html += '<label class="control-label"><?php echo $text_shipping_add; ?></label>';
              html += '</div>';
              html += '<div class="col-sm-3">';
                html += '<input type="text" name="data[' + id + '][' + type + '][price_additional][' + count + ']" class="form-control" value="0.00" />';
              html += '</div>';
            }
            html += '<div class="col-sm-3 pull-right text-right">';
              html += '<a onclick="removeShipping(\'' + id + '\',\'' + count + '\',\''+type+'\');" class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_delete; ?></a>';
            html += '</div>';
          html += '</div>';
        html += '</div>';

        $('#options-' + id + '-' + type).append(html);
        $('#add-' + id + '-' + type).empty().html('<i class="fa fa-plus-circle"></i> <?php echo $button_add; ?>').removeAttr('disabled');
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#add-shipping-'+id).empty().html('<i class="fa fa-plus-circle"></i> <?php echo $button_add; ?>').removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });

    $('#' + type + '_count_' + id).val(count + 1);
  }

  function removeShipping(id, count, type) {
    $('#' + id + '_' + type + '_' + count).remove();
  }
//--></script>
<?php echo $footer; ?>