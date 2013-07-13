<?php echo $header; ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li> <a href="<?php echo $breadcrumb['href']; ?>"> <?php echo $breadcrumb['text']; ?> </a> </li>
  <?php } ?>
</ul>
<div class="row"><?php echo $column_left; ?>
  <div id="content" class="span9"><?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?></h1>
    <?php if ($orders) { ?>
    <table class="table table-bordered table-hover download-list">
      <thead>
        <tr>
          <td class="right"><?php echo $column_order_id; ?></td>
          <td class="left"><?php echo $column_status; ?></td>
          <td class="left"><strong><?php echo $column_date_added; ?></strong></td>
          <td class="right"><strong><?php echo $column_product; ?></strong></td>
          <td class="left"><strong><?php echo $column_customer; ?></strong></td>
          <td class="right"><strong><?php echo $column_total; ?></strong></td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order) { ?>
        <tr>
          <td class="right">#<?php echo $order['order_id']; ?></td>
          <td class="left"><?php echo $order['status']; ?></td>
          <td class="left"><?php echo $order['date_added']; ?></td>
          <td class="right"><?php echo $order['products']; ?></td>
          <td class="left"><?php echo $order['name']; ?></td>
          <td class="right"><?php echo $order['total']; ?></td>
          <td><a data-toggle="tooltip" class="tooltip-item" alt="<?php echo $button_view; ?>" title="<?php echo $button_view; ?>" href="<?php echo $order['href']; ?>"> <i class="icon-eye-open"></i> </a> <a data-toggle="tooltip" class="tooltip-item" alt="<?php echo $button_reorder; ?>" title="<?php echo $button_reorder; ?>" href="<?php echo $order['reorder']; ?>"><i class="icon-refresh"></i></a></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="pagination"><?php echo $pagination; ?></div>
    <?php } else { ?>
    <div class="content">
      <p><?php echo $text_empty; ?></p>
    </div>
    <?php } ?>
    <div class="buttons clearfix">
      <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
    </div>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
<?php echo $footer; ?>