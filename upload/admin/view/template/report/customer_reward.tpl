<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <div style="background: #E7EFEF; border: 1px solid #C6D7D7; padding: 10px; margin-bottom: 15px; overflow: auto;">
        <div style="float: left; width: 19%;"><?php echo $entry_date_start; ?>
          <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" />
        </div>
        <div style="float: left; width: 19%;"><?php echo $entry_date_end; ?>
          <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" />
        </div>
        <div style="float: left; width: 19%;"><?php echo $entry_group; ?>
          <select name="filter_group">
            <?php foreach ($groups as $groups) { ?>
            <?php if ($groups['value'] == $filter_group) { ?>
            <option value="<?php echo $groups['value']; ?>" selected="selected"><?php echo $groups['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $groups['value']; ?>"><?php echo $groups['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div style="float: right; width: 19%; text-align: right;"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></div>
      </div>    
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_name; ?></td>
            <td class="left"><?php echo $column_email; ?></td>
            <td class="left"><?php echo $column_customer_group; ?></td>
            <td class="left"><?php echo $column_status; ?></td>
            <td class="right"><?php echo $column_orders; ?></td>
            <td class="right"><?php echo $column_points; ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($customers) { ?>
          <?php foreach ($customers as $customer) { ?>
          <tr>
            <td class="left"><?php echo $customer['name']; ?></td>
            <td class="left"><?php echo $customer['email']; ?></td>
            <td class="left"><?php echo $customer['customer_group']; ?></td>
            <td class="left"><?php echo $customer['status']; ?></td>
            <td class="right"><?php echo $customer['orders']; ?></td>
            <td class="right"><?php echo $customer['points']; ?></td>
            <td class="right"><?php foreach ($customer['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>