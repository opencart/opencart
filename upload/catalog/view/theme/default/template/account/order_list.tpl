<?php echo $header; ?>
<div class="container order_container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row nomargin" style="background: #ffffff;"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($orders) { ?>

      <?php /*>
      <!--
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_order_id; ?></td>
              <td class="text-left"><?php echo $column_customer; ?></td>
              <td class="text-right"><?php echo $column_product; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="text-right">#<?php echo $order['order_id']; ?></td>
              <td class="text-left"><?php echo $order['name']; ?></td>
              <td class="text-right"><?php echo $order['products']; ?></td>
              <td class="text-left"><?php echo $order['status']; ?></td>
              <td class="text-right"><?php echo $order['total']; ?></td>
              <td class="text-left"><?php echo $order['date_added']; ?></td>
              <td class="text-right"><a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      -->
     <*/?>

      <div class="table-responsive">
        <table class="table" id="order_list">
          <thead>
            <tr class="text-center">
              <td>
                <div class="btn-group pull-left" id="order_date">
                  <?php if(!isset($order_month) || (int)$order_month == 3) { ?>
                  <a class="dropdown-toggle order_date" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-month="3"><?php echo $text_within_three_months; ?> <span class="caret"></span></a>
                  <?php } elseif((int)$order_month == -1) { ?>
                  <a class="dropdown-toggle order_date" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-month="-1"><?php echo $text_within_this_year; ?> <span class="caret"></span></a>
                  <?php } ?>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:void(0);" data-month="3"><?php echo $text_within_three_months; ?></a></li>
                    <li><a href="javascript:void(0);" data-month="-1"><?php echo $text_within_this_year; ?></a></a></li>
                  </ul>
                </div>
                <span class="pull-right"><?php echo $text_order_detail; ?></span>
              </td>
              <td style="width:95px;"><?php echo $text_receiver; ?></td>
              <td><?php echo $text_money; ?></td>
              <td style="width:100px;"><?php echo $text_all_status; ?></td>
              <td><?php echo $text_operation; ?></td>
            </tr>
          </thead>
          <?php foreach ($orders as $order) { ?>
          <tbody class="text-center">
            <tr>
              <td></td>
            </tr>
            <tr>
              <td>
                <span class="pull-left"><?php echo $order['date_added']; ?></span>
                <div class="text-right">
                  <span class="dealtime"><?php echo $text_order_no; ?> : </span>
                  <span><?php echo $order['order_no']; ?></span>
                </div>
              </td>
              <td colspan="5"></td>
            </tr>
            <tr>
              <td class="text-left">
                <?php $product_names = explode(",", $order['product_name']); ?>
                <?php $images = explode(",", $order['image']); ?>
                <?php $href = explode(",", $order['href']); ?>
                <?php $numbers = explode(",", $order['numbers']); ?>
                <?php foreach($product_names as $key => $product_name) { ?>
                <div class="row nomargin" style="width:340px;">
                  <div class="col-md-3">
                    <a href="<?php echo $href[$key]; ?>" class="pull-left">
                      <img src="<?php echo $images[$key]; ?>" alt="<?php echo $product_name; ?>" title="<?php echo $product_name; ?>" class="img-thumbnail">
                    </a>
                  </div>
                  <div class="col-md-7">
                    <a href="<?php echo $href[$key]; ?>">
                      <span class="product_name"><?php echo $product_name; ?></span>
                    </a>
                  </div>
                  <div class="col-md-2">
                    <span class="goods_number">x<?php echo $numbers[$key]; ?></span>
                  </div>
                </div>
                <?php } ?>
              </td>
              <td>
                <?php echo $order['name']; ?>
                <button type="button" class="btn btn-address" data-toggle="popover" data-trigger="hover" data-placement="left"
                        data-content="<?php echo $order['user_detail']; ?>">
                  <i class="fa fa-user"></i>
                </button>
              </td>
              <td class="text-center">
                <p class="amount">
                  <?php echo ($order['total']); ?></p>
                <hr />
                <p class="payment_type"><?php echo $order['payment_method']; ?></p>
              </td>
              <td>
                <p><span class="order-status <?php echo strtolower($order['status_name']); ?>"><?php echo $order['status_name']; ?></span></p>
                <p><a href="<?php echo $order['view']; ?>"><span class="order-detail"><?php echo $text_order_detail; ?></span></a></p>
              </td>
              <td>
                <!--Pending-->
                <?php if(isset($order_statuses['Pending']) && $order['status'] == $order_statuses['Pending']) { ?>
                  <p class="count-time">Remaining<br /> 13h 20min</p>
                <p>
                  <a href="<?php echo $order['pay_online']; ?>" class="btn-payment"><?php echo $text_payment; ?></a>
                </p>
                <p>
                  <a href="javascript:void(0);" onclick="fn_cancel_order(this);" data-id="<?php echo $order['order_id']; ?>">
                    <span class="order-canceled"><?php echo $text_order_canceled; ?></span></a>
                </p>
                <?php } ?>
                <!--Completed-->

                <?php /*>
                <!--
                <?php if(isset($order_statuses['Complete']) && $order['status'] == $order_statuses['Complete']) { ?>
                <?php } ?>
                -->
                <*/?>

                <?php if(isset($order_statuses['Canceled']) && $order['status'] == $order_statuses['Canceled']) { ?>
                  <a href="<?php echo $order['reorder_href']; ?>" class="btn-reorder">
                    <i class="fa fa-bolt"></i> Reorder
                  </a>
                <?php } ?>
              </td>
            </tr>
          </tbody>
          <?php } ?>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
<script>
  $(function () {
    $('button[data-toggle="popover"]').popover({
      html:true
    });

    $('#order_date > ul a').click(function () {
      var $this = $(this);
      $('#order_date > a').html($this.html() + ' <span class="caret"></span>');
      $('#order_date > a').attr('data-month',$this.data('month'));

      window.location.href = '<?php echo HTTPS_SERVER; ?>' + 'index.php?route=account/order&order_month=' + $this.data('month');
    });
  });

  function fn_cancel_order(obj) {
    var $this = $(obj);
    var order_id = $this.data('id');
    $.post('index.php?route=account/order/cancel',{ 'order_id':order_id }, function (result) {
      window.location.href = '<?php echo HTTPS_SERVER; ?>' + 'index.php?route=account/order';
    });
  }
</script>