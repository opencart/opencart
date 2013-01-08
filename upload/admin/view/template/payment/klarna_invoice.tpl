<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general ?></a><a href="#tab-log"><?php echo $tab_log ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td colspan="2"><a onclick="window.open('https://merchants.klarna.com/signup?locale=en&partner_id=d5c87110cebc383a826364769047042e777da5e8&utm_campaign=Platform&utm_medium=Partners&utm_source=Opencart');" ><img src="view/image/payment/klarna_banner.gif" /></a></td>
            </tr>
          </table>
          <div id="vtabs" class="vtabs"><a href="#tab-germany"><?php echo $text_germany; ?></a><a href="#tab-netherlands"><?php echo $text_netherlands; ?></a><a href="#tab-denmark"><?php echo $text_denmark; ?></a><a href="#tab-sweden"><?php echo $text_sweden; ?></a><a href="#tab-norway"><?php echo $text_norway; ?></a><a href="#tab-finland"><?php echo $text_finland; ?></a></div>
          <div id="tab-germany" class="vtabs-content">
            <table class="form">
              <tr>
                <td><?php echo $entry_merchant; ?></td>
                <td><input type="text" name="klarna_invoice_germany_merchant" value="<?php echo $klarna_invoice_germany_merchant; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_secret; ?></td>
                <td><input type="text" name="klarna_invoice_germany_secret" value="<?php echo $klarna_invoice_germany_secret; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_server; ?></td>
                <td><select name="klarna_invoice_germany_server">
                    <?php if ($klarna_invoice_germany_server == 'live') { ?>
                    <option value="live" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="live"><?php echo $text_live; ?></option>
                    <?php } ?>
                    <?php if ($klarna_invoice_germany_server == 'beta') { ?>
                    <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                    <?php } else { ?>
                    <option value="beta"><?php echo $text_beta; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_total; ?></td>
                <td><input type="text" name="klarna_invoice_germany_total" value="<?php echo $klarna_invoice_germany_total; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_pending_status; ?></td>
                <td><select name="klarna_invoice_germany_pending_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_germany_pending_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_accepted_status; ?></td>
                <td><select name="klarna_invoice_germany_accepted_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_germany_accepted_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_geo_zone; ?></td>
                <td><select name="klarna_invoice_germany_geo_zone_id">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $klarna_invoice_germany_geo_zone_id) {  ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="klarna_invoice_germany_status">
                    <?php if ($klarna_invoice_germany_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_sort_order ?></td>
                <td><input type="text" name="klarna_invoice_germany_sort_order" value="<?php echo $klarna_invoice_germany_sort_order; ?>" /></td>
              </tr>
            </table>
          </div>
          <div id="tab-netherlands" class="vtabs-content">
            <table class="form">
              <tr>
                <td><?php echo $entry_merchant; ?></td>
                <td><input type="text" name="klarna_invoice_netherlands_merchant" value="<?php echo $klarna_invoice_netherlands_merchant; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_secret; ?></td>
                <td><input type="text" name="klarna_invoice_netherlands_secret" value="<?php echo $klarna_invoice_netherlands_secret; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_server; ?></td>
                <td><select name="klarna_invoice_netherlands_server">
                    <?php if ($klarna_invoice_netherlands_server == 'live') { ?>
                    <option value="live" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="live"><?php echo $text_live; ?></option>
                    <?php } ?>
                    <?php if ($klarna_invoice_netherlands_server == 'beta') { ?>
                    <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                    <?php } else { ?>
                    <option value="beta"><?php echo $text_beta; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_total; ?></td>
                <td><input type="text" name="klarna_invoice_netherlands_total" value="<?php echo $klarna_invoice_netherlands_total; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_pending_status; ?></td>
                <td><select name="klarna_invoice_netherlands_pending_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_netherlands_pending_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_accepted_status; ?></td>
                <td><select name="klarna_invoice_netherlands_accepted_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_netherlands_accepted_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_geo_zone; ?></td>
                <td><select name="klarna_invoice_netherlands_geo_zone_id">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $klarna_invoice_netherlands_geo_zone_id) {  ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="klarna_invoice_netherlands_status">
                    <?php if ($klarna_invoice_netherlands_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_sort_order ?></td>
                <td><input type="text" name="klarna_invoice_netherlands_sort_order" value="<?php echo $klarna_invoice_netherlands_sort_order; ?>" /></td>
              </tr>
            </table>
          </div>
          <div id="tab-denmark" class="vtabs-content">
            <table class="form">
              <tr>
                <td><?php echo $entry_merchant; ?></td>
                <td><input type="text" name="klarna_invoice_denmark_merchant" value="<?php echo $klarna_invoice_denmark_merchant; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_secret; ?></td>
                <td><input type="text" name="klarna_invoice_denmark_secret" value="<?php echo $klarna_invoice_denmark_secret; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_server; ?></td>
                <td><select name="klarna_invoice_denmark_server">
                    <?php if ($klarna_invoice_denmark_server == 'live') { ?>
                    <option value="live" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="live"><?php echo $text_live; ?></option>
                    <?php } ?>
                    <?php if ($klarna_invoice_denmark_server == 'beta') { ?>
                    <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                    <?php } else { ?>
                    <option value="beta"><?php echo $text_beta; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>

              <tr>
                <td><?php echo $entry_total; ?></td>
                <td><input type="text" name="klarna_invoice_denmark_total" value="<?php echo $klarna_invoice_denmark_total; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_pending_status; ?></td>
                <td><select name="klarna_invoice_denmark_pending_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_denmark_pending_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_accepted_status; ?></td>
                <td><select name="klarna_invoice_denmark_accepted_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_denmark_accepted_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_geo_zone; ?></td>
                <td><select name="klarna_invoice_denmark_geo_zone_id">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $klarna_invoice_denmark_geo_zone_id) {  ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="klarna_invoice_denmark_status">
                    <?php if ($klarna_invoice_denmark_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_sort_order ?></td>
                <td><input type="text" name="klarna_invoice_denmark_sort_order" value="<?php echo $klarna_invoice_denmark_sort_order; ?>" /></td>
              </tr>
            </table>
          </div>
          <div id="tab-sweden" class="vtabs-content">
            <table class="form">
              <tr>
                <td><?php echo $entry_merchant; ?></td>
                <td><input type="text" name="klarna_invoice_sweden_merchant" value="<?php echo $klarna_invoice_sweden_merchant; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_secret; ?></td>
                <td><input type="text" name="klarna_invoice_sweden_secret" value="<?php echo $klarna_invoice_sweden_secret; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_server; ?></td>
                <td><select name="klarna_invoice_sweden_server">
                    <?php if ($klarna_invoice_sweden_server == 'live') { ?>
                    <option value="live" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="live"><?php echo $text_live; ?></option>
                    <?php } ?>
                    <?php if ($klarna_invoice_sweden_server == 'beta') { ?>
                    <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                    <?php } else { ?>
                    <option value="beta"><?php echo $text_beta; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_total; ?></td>
                <td><input type="text" name="klarna_invoice_sweden_total" value="<?php echo $klarna_invoice_sweden_total; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_pending_status; ?></td>
                <td><select name="klarna_invoice_sweden_pending_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_sweden_pending_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_accepted_status; ?></td>
                <td><select name="klarna_invoice_sweden_accepted_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_sweden_accepted_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_geo_zone; ?></td>
                <td><select name="klarna_invoice_sweden_geo_zone_id">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $klarna_invoice_sweden_geo_zone_id) {  ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="klarna_invoice_sweden_status">
                    <?php if ($klarna_invoice_sweden_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_sort_order ?></td>
                <td><input type="text" name="klarna_invoice_sweden_sort_order" value="<?php echo $klarna_invoice_sweden_sort_order; ?>" /></td>
              </tr>
            </table>
          </div>
          <div id="tab-norway" class="vtabs-content">
            <table class="form">
              <tr>
                <td><?php echo $entry_merchant; ?></td>
                <td><input type="text" name="klarna_invoice_norway_merchant" value="<?php echo $klarna_invoice_norway_merchant; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_secret; ?></td>
                <td><input type="text" name="klarna_invoice_norway_secret" value="<?php echo $klarna_invoice_norway_secret; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_server; ?></td>
                <td><select name="klarna_invoice_norway_server">
                    <?php if ($klarna_invoice_norway_server == 'live') { ?>
                    <option value="live" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="live"><?php echo $text_live; ?></option>
                    <?php } ?>
                    <?php if ($klarna_invoice_norway_server == 'beta') { ?>
                    <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                    <?php } else { ?>
                    <option value="beta"><?php echo $text_beta; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_total; ?></td>
                <td><input type="text" name="klarna_invoice_norway_total" value="<?php echo $klarna_invoice_norway_total; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_pending_status; ?></td>
                <td><select name="klarna_invoice_norway_pending_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_norway_pending_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_accepted_status; ?></td>
                <td><select name="klarna_invoice_norway_accepted_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_norway_accepted_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_geo_zone; ?></td>
                <td><select name="klarna_invoice_norway_geo_zone_id">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $klarna_invoice_norway_geo_zone_id) {  ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="klarna_invoice_norway_status">
                    <?php if ($klarna_invoice_norway_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_sort_order ?></td>
                <td><input type="text" name="klarna_invoice_norway_sort_order" value="<?php echo $klarna_invoice_norway_sort_order; ?>" /></td>
              </tr>
            </table>
          </div>
          <div id="tab-finland" class="vtabs-content">
            <table class="form">
              <tr>
                <td><?php echo $entry_merchant; ?></td>
                <td><input type="text" name="klarna_invoice_finland_merchant" value="<?php echo $klarna_invoice_finland_merchant; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_secret; ?></td>
                <td><input type="text" name="klarna_invoice_finland_secret" value="<?php echo $klarna_invoice_finland_secret; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_server; ?></td>
                <td><select name="klarna_invoice_finland_server">
                    <?php if ($klarna_invoice_finland_server == 'live') { ?>
                    <option value="live" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="live"><?php echo $text_live; ?></option>
                    <?php } ?>
                    <?php if ($klarna_invoice_finland_server == 'beta') { ?>
                    <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                    <?php } else { ?>
                    <option value="beta"><?php echo $text_beta; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_total; ?></td>
                <td><input type="text" name="klarna_invoice_finland_total" value="<?php echo $klarna_invoice_finland_total; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_pending_status; ?></td>
                <td><select name="klarna_invoice_finland_pending_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_finland_pending_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_accepted_status; ?></td>
                <td><select name="klarna_invoice_finland_accepted_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $klarna_invoice_finland_accepted_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_geo_zone; ?></td>
                <td><select name="klarna_invoice_finland_geo_zone_id">
                    <option value="0"><?php echo $text_all_zones; ?></option>
                    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $klarna_invoice_finland_geo_zone_id) {  ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="klarna_invoice_finland_status">
                    <?php if ($klarna_invoice_finland_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_sort_order ?></td>
                <td><input type="text" name="klarna_invoice_finland_sort_order" value="<?php echo $klarna_invoice_finland_sort_order; ?>" /></td>
              </tr>
            </table>
          </div>
        </div>
        <div id="tab-log">
          <table class="form">
            <tr>
              <td><textarea wrap="off" style="width: 98%; height: 300px; padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF; overflow: scroll;"><?php echo $log ?></textarea></td>
            </tr>
            <tr>
              <td style="text-align: right;"><a href="<?php echo $clear; ?>" class="button"><?php echo $button_clear ?></a></td>
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