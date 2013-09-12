<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb): ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php endforeach;; ?>
  </div>
  <?php foreach($errors as $error): ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php endforeach; ?>
  <div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
            <h1><img src="view/image/payment.png" /><?php echo $heading_title; ?></h1>
      <div class="buttons">
          <a class="button" onclick="$('#form').submit()"><span><?php echo $button_save; ?></span></a>
          <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
      </div>
    </div>
    <div class="content">
      <form action="" method="POST" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td colspan="2"><p><?php echo $text_amazon_join ?></p></td>
          </tr>  
          <tr>
            <td><span class="required">*</span> <label for="amazon_checkout_merchant_id"><?php echo $text_merchant_id ?></label></td>
            <td><input name="amazon_checkout_merchant_id" value="<?php echo $amazon_checkout_merchant_id ?>" id="amazon_checkout_merchant_id" /></td>
          </tr>  
          <tr>
            <td><span class="required">*</span> <label for="amazon_checkout_access_key"><?php echo $text_access_key ?></label></td>
            <td><input name="amazon_checkout_access_key" value="<?php echo $amazon_checkout_access_key ?>" id="amazon_checkout_access_key" /></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <label for="amazon_checkout_access_secret"><?php echo $text_access_secret ?></label></td>
            <td><input name="amazon_checkout_access_secret" value="<?php echo $amazon_checkout_access_secret ?>" id="amazon_checkout_access_secret" /></td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_status"><?php echo $text_status ?></label></td>
            <td>
              <select name="amazon_checkout_status" id="amazon_checkout_status">
                <?php if ($amazon_checkout_status == 1): ?>
                  <option value="1" selected="selected"><?php echo $text_status_enabled ?></option>
                <?php else: ?>
                  <option value="1"><?php echo $text_status_enabled ?></option>
                <?php endif; ?>
                <?php if ($amazon_checkout_status == 0): ?>
                  <option value="0" selected="selected"><?php echo $text_status_disabled ?></option>
                <?php else: ?>
                  <option value="0"><?php echo $text_status_disabled ?></option>
                <?php endif; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_mode"><?php echo $text_checkout_mode ?></label></td>
            <td>
              <select name="amazon_checkout_mode" id="amazon_checkout_mode">
                <?php if ($amazon_checkout_mode == 'sandbox'): ?>
                  <option value="sandbox" selected="selected"><?php echo $text_sandbox ?></option>
                <?php else: ?>
                  <option value="sandbox"><?php echo $text_sandbox ?></option>
                <?php endif; ?>
                <?php if ($amazon_checkout_mode == 'live'): ?>
                  <option value="live" selected="selected"><?php echo $text_live ?></option>
                <?php else: ?>
                  <option value="live"><?php echo $text_live ?></option>
                <?php endif; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_marketplace"><?php echo $text_marketplace ?></label></td>
            <td>
              <select name="amazon_checkout_marketplace" id="amazon_checkout_marketplace">
                <?php if ($amazon_checkout_marketplace == 'uk'): ?>
                  <option value="uk" selected="selected"><?php echo $text_uk ?></option>
                <?php else: ?>
                  <option value="uk"><?php echo $text_uk ?></option>
                <?php endif; ?>
                <?php if ($amazon_checkout_marketplace == 'de'): ?>
                  <option value="de" selected="selected"><?php echo $text_germany ?></option>
                <?php else: ?>
                  <option value="de"><?php echo $text_germany ?></option>
                <?php endif; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_geo_zone"><?php echo $text_geo_zone ?></label></td>
            <td>
              <select name="amazon_checkout_geo_zone" id="amazon_checkout_geo_zone">
                <?php if ($amazon_checkout_geo_zone == 0): ?>
                  <option value="0" selected="selected"><?php echo $text_all_geo_zones ?></option>
                <?php else: ?>
                  <option value="0"><?php echo $text_all_geo_zones ?></option>
                <?php endif; ?>
      
                <?php foreach ($geo_zones as $geo_zone): ?>
                  <?php if ($amazon_checkout_geo_zone == $geo_zone['geo_zone_id']): ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name'] ?></option>
                  <?php else: ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name'] ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_order_default_status"><?php echo $text_default_order_status ?></label></td>
            <td>
              <select id="amazon_checkout_order_default_status" name="amazon_checkout_order_default_status">
                <?php foreach($order_statuses as $order_status): ?>
                  <?php if ($order_status['order_status_id'] == $amazon_checkout_order_default_status): ?>
                    <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                  <?php else: ?>
                    <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                  <?php endif; ?>
                <?php endforeach;?>
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_order_ready_status"><?php echo $text_ready_order_status ?></label></td>
            <td>
              <select id="amazon_checkout_order_ready_status" name="amazon_checkout_order_ready_status">
                <?php foreach($order_statuses as $order_status): ?>
                  <?php if ($order_status['order_status_id'] == $amazon_checkout_order_ready_status): ?>
                    <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                  <?php else: ?>
                    <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                  <?php endif; ?>
                <?php endforeach;?>
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_order_shipped_status"><?php echo $text_shipped_order_status ?></label></td>
            <td>
              <select id="amazon_checkout_order_shipped_status" name="amazon_checkout_order_shipped_status">
                <?php foreach($order_statuses as $order_status): ?>
                  <?php if ($order_status['order_status_id'] == $amazon_checkout_order_shipped_status): ?>
                    <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                  <?php else: ?>
                    <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                  <?php endif; ?>
                <?php endforeach;?>
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_order_canceled_status"><?php echo $text_canceled_order_status ?></label></td>
              <td>
                <select id="amazon_checkout_order_canceled_status" name="amazon_checkout_order_canceled_status">
                  <?php foreach($order_statuses as $order_status): ?>
                    <?php if ($order_status['order_status_id'] == $amazon_checkout_order_canceled_status): ?>
                      <option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
                    <?php else: ?>
                      <option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
                    <?php endif; ?>
                  <?php endforeach;?>
                </select>
              </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_minimum_total"><?php echo $text_minimum_total ?></label></td>
            <td><input name="amazon_checkout_minimum_total" value="<?php echo $amazon_checkout_minimum_total ?>" id="amazon_checkout_minimum_total" /></td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_sort_order"><?php echo $text_sort_order ?></label></td>
            <td><input name="amazon_checkout_sort_order" value="<?php echo $amazon_checkout_sort_order ?>" id="amazon_checkout_sort_order" /></td>
          </tr>
          <tr>
            <td>
                <label for="amazon_checkout_cron_job_token"><?php echo $text_cron_job_token ?></label><br />
                <span class="help"><?php echo $help_cron_job_token ?></span>
            </td>
            <td><input name="amazon_checkout_cron_job_token" value="<?php echo $amazon_checkout_cron_job_token ?>" id="amazon_checkout_cron_job_token" /></td>
          </tr>
          <tr>
            <td>
                <?php echo $text_cron_job_url ?><br />
                <span class="help"><?php echo $help_cron_job_url ?></span>
            </td>
            <td>
                <span id="cron-job-url"><?php echo $cron_job_url ?></span>
            </td>
          </tr>
          <tr>
            <td><?php echo $text_last_cron_job_run ?></td>
            <td><?php echo $last_cron_job_run ?></td>
          </tr>
          <tr>
              <td><?php echo $text_allowed_ips ?><br /><span class="help"><?php echo $help_allowed_ips ?></span></td>
              <td>
                  <input type="text" name="allowed-ip" />
                  <a class="button" id="add-ip"><?php echo $text_add ?></a>
              </td>
          </tr>
          <tr>
              <td></td>
              <td>
                  <div id="allowed-ips" class="scrollbox">
                      <?php $class = 'odd' ?>
                      <?php $count = 0 ?>
                      <?php foreach ($amazon_checkout_allowed_ips as $ip): ?>
                      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                          <div id="allowed-ip<?php echo $count++ ?>" class="<?php echo $class ?>"><?php echo $ip ?> <img src="view/image/delete.png" alt="" />
                          <input type="hidden" name="amazon_checkout_allowed_ips[]" value="<?php echo $ip ?>" />
                          </div>
                      <?php endforeach; ?>
                  </div>
              </td>
          </tr>
          <tr>
            <td colspan="2"><h2><?php echo $text_button_settings ?></h2></td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_button_colour"><?php echo $text_colour ?></label></td>
            <td>
              <select name="amazon_checkout_button_colour" id="amazon_checkout_button_colour">
                <?php foreach($button_colours as $value => $text): ?>
                  <?php if($value == $amazon_checkout_button_colour): ?>
                    <option selected="selected" value="<?php echo $value ?>"><?php echo $text ?></option>
                  <?php else: ?>
                    <option value="<?php echo $value ?>"><?php echo $text ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_button_background"><?php echo $text_background ?></label></td>
            <td>
              <select name="amazon_checkout_button_background" id="amazon_checkout_button_background">
                <?php foreach($button_backgrounds as $value => $text): ?>
                  <?php if($value == $amazon_checkout_button_background): ?>
                    <option selected="selected" value="<?php echo $value ?>"><?php echo $text ?></option>
                  <?php else: ?>
                    <option value="<?php echo $value ?>"><?php echo $text ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><label for="amazon_checkout_button_size"><?php echo $text_size ?></label></td>
            <td>
              <select name="amazon_checkout_button_size" id="amazon_checkout_button_size">
                <?php foreach($button_sizes as $value => $text): ?>
                  <?php if($value == $amazon_checkout_button_size): ?>
                    <option selected="selected" value="<?php echo $value ?>"><?php echo $text ?></option>
                  <?php else: ?>
                    <option value="<?php echo $value ?>"><?php echo $text ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
  </div>
<script type="text/javascript"><!--
    
var count = <?php echo $count ?>;
    
$('#add-ip').click(function(){
    var ip = $.trim($('input[name="allowed-ip"]').val());
    $('input[name="allowed-ip"]').val('');
    
    if (ip != '') {
        var html = '';
        html += '<div id="allowed-ip' + count++ + '" class="<?php echo $class ?>">' + ip;
        html += '<img src="view/image/delete.png" alt="" />';
        html += '<input type="hidden" name="amazon_checkout_allowed_ips[]" value="' + ip + '" />';
        html += '</div>';

        $('#allowed-ips').append(html);

        $('#allowed-ips div:odd').attr('class', 'odd');
        $('#allowed-ips div:even').attr('class', 'even');
    }
});

$('#allowed-ips img').click(function(){
    $(this).parent().remove();
    
    $('#allowed-ips div:odd').attr('class', 'odd');
	$('#allowed-ips div:even').attr('class', 'even');
});

$('input[name="amazon_checkout_cron_job_token"]').keyup(function(){
    $('#cron-job-url').html('<?php echo HTTPS_CATALOG ?>index.php?route=payment/amazon_checkout/cron&token=' + $(this).val());
});

//--></script>
<?php echo $footer; ?> 