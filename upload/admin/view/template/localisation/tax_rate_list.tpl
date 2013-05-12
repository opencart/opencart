<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-list"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="buttons"><a href="<?php echo $insert; ?>" class="btn"><i class="icon-plus"></i> <?php echo $button_insert; ?></a> <a onclick="$('#form').submit();" class="btn"><i class="icon-trash"></i> <?php echo $button_delete; ?></a></div>
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td width="1" class="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'tr.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'tr.rate') { ?>
                <a href="<?php echo $sort_rate; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rate; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_rate; ?>"><?php echo $column_rate; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'tr.type') { ?>
                <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_type; ?>"><?php echo $column_type; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'gz.name') { ?>
                <a href="<?php echo $sort_geo_zone; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_geo_zone; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_geo_zone; ?>"><?php echo $column_geo_zone; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'tr.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'tr.date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($tax_rates) { ?>
            <?php foreach ($tax_rates as $tax_rate) { ?>
            <tr>
              <td class="center"><?php if ($tax_rate['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $tax_rate['tax_rate_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $tax_rate['tax_rate_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $tax_rate['name']; ?></td>
              <td class="right"><?php echo $tax_rate['rate']; ?></td>
              <td class="left"><?php echo $tax_rate['type']; ?></td>
              <td class="left"><?php echo $tax_rate['geo_zone']; ?></td>
              <td class="left"><?php echo $tax_rate['date_added']; ?></td>
              <td class="left"><?php echo $tax_rate['date_modified']; ?></td>
              <td class="right"><?php foreach ($tax_rate['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
      <div class="results"><?php echo $results; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>