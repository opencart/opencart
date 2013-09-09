<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $cols = 6; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $cols = 9; ?>
    <?php } else { ?>
    <?php $cols = 12; ?>
    <?php } ?>  
    <div id="content" class="col-sm-<?php echo $cols; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" colspan="2"><?php echo $text_return_detail; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;"><b><?php echo $text_return_id; ?></b> #<?php echo $return_id; ?><br />
              <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
            <td class="text-left" style="width: 50%;"><b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
              <b><?php echo $text_date_ordered; ?></b> <?php echo $date_ordered; ?></td>
          </tr>
        </tbody>
      </table>
      <h2><?php echo $text_product; ?></h2>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_product; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_model; ?></td>
            <td class="text-right" style="width: 33.3%;"><?php echo $column_quantity; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $product; ?></td>
            <td class="text-left"><?php echo $model; ?></td>
            <td class="text-right"><?php echo $quantity; ?></td>
          </tr>
        </tbody>
      </table>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_reason; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_opened; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $reason; ?></td>
            <td class="text-left"><?php echo $opened; ?></td>
            <td class="text-left"><?php echo $action; ?></td>
          </tr>
        </tbody>
      </table>
      <?php if ($comment) { ?>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $text_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $comment; ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
      <?php if ($histories) { ?>
      <h2><?php echo $text_history; ?></h2>
      <table class="list table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_date_added; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_status; ?></td>
            <td class="text-left" style="width: 33.3%;"><?php echo $column_comment; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($histories as $history) { ?>
          <tr>
            <td class="text-left"><?php echo $history['date_added']; ?></td>
            <td class="text-left"><?php echo $history['status']; ?></td>
            <td class="text-left"><?php echo $history['comment']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>