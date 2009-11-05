<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="location='<?php echo $insert; ?>'" class="button"><span class="button_left button_insert"></span><span class="button_middle"><?php echo $button_insert; ?></span><span class="button_right"></span></a><a onclick="$('form').submit();" class="button"><span class="button_left button_delete"></span><span class="button_middle"><?php echo $button_delete; ?></span><span class="button_right"></span></a></div>
</div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
  <table class="list">
    <thead>
      <tr>
        <td width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
        <td class="left"><?php if ($sort == 'title') { ?>
          <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'code') { ?>
          <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
          <?php } ?></td>
        <td class="right"><?php if ($sort == 'value') { ?>
          <a href="<?php echo $sort_value; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_value; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_value; ?>"><?php echo $column_value; ?></a>
          <?php } ?></td>
        <td class="left"><?php if ($sort == 'date_modified') { ?>
          <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
          <?php } ?></td>
        <td class="right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($currencies) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($currencies as $currency) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td style="align: center;"><?php if ($currency['selected']) { ?>
          <input type="checkbox" name="selected[]" value="<?php echo $currency['currency_id']; ?>" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="selected[]" value="<?php echo $currency['currency_id']; ?>" />
          <?php } ?></td>
        <td class="left"><?php echo $currency['title']; ?></td>
        <td class="left"><?php echo $currency['code']; ?></td>
        <td class="right"><?php echo $currency['value']; ?></td>
        <td class="left"><?php echo $currency['date_modified']; ?></td>
        <td class="right"><?php foreach ($currency['action'] as $action) { ?>
          [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
          <?php } ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr class="even">
        <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</form>
<div class="pagination"><?php echo $pagination; ?></div>
<?php echo $footer; ?>