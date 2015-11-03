<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_fulfillment_list; ?></h3>
      </div>
      <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-left"><?php echo $column_seller_fulfillment_order_id; ?></th>
                <th class="text-left"><?php echo $column_displayable_order_id; ?></th>
                <th class="text-left"><?php echo $column_displayable_order_date; ?></th>
                <th class="text-left"><?php echo $column_shipping_speed_category; ?></th>
                <th class="text-left"><?php echo $column_fulfillment_order_status; ?></th>
                <td class="text-left"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($fulfillments)) { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
            <?php } else { ?>
              <?php foreach ($fulfillments as $fulfillment) { ?>
              <tr>
                <td class="text-left"><?php echo $fulfillment['seller_fulfillment_order_id']; ?></td>
                <td class="text-left"><?php echo $fulfillment['displayable_order_id']; ?></td>
                <td class="text-left"><?php echo $fulfillment['displayable_order_date_time']; ?></td>
                <td class="text-left"><?php echo $fulfillment['shipping_speed_category']; ?></td>
                <td class="text-left"><?php echo $fulfillment['fulfillment_order_status']; ?></td>
                <td class="text-right"><a href="<?php echo $fulfillment['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
              </tr>
              <?php } ?>
            <?php } ?>
            </tbody>
          </table>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>