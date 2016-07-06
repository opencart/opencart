{{ header }}
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  {% if success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ success }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  {% if error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row">{{ column_left }}
    {% if column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="{{ class }}">{{ content_top }}
      <h2>{{ heading_title }}</h2>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" colspan="2">{{ text_order_detail }}</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;">{% if invoice_no) { ?>
              <b>{{ text_invoice_no; ?></b> <?php echo $invoice_no }}<br />
              <?php } ?>
              <b>{{ text_order_id; ?></b> #<?php echo $order_id }}<br />
              <b>{{ text_date_added; ?></b> <?php echo $date_added }}</td>
            <td class="text-left" style="width: 50%;">{% if payment_method) { ?>
              <b>{{ text_payment_method; ?></b> <?php echo $payment_method }}<br />
              <?php } ?>
              {% if shipping_method) { ?>
              <b>{{ text_shipping_method; ?></b> <?php echo $shipping_method }}
              <?php } ?></td>
          </tr>
        </tbody>
      </table>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" style="width: 50%; vertical-align: top;">{{ text_payment_address }}</td>
            {% if shipping_address) { ?>
            <td class="text-left" style="width: 50%; vertical-align: top;">{{ text_shipping_address }}</td>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left">{{ payment_address }}</td>
            {% if shipping_address) { ?>
            <td class="text-left">{{ shipping_address }}</td>
            <?php } ?>
          </tr>
        </tbody>
      </table>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left">{{ column_name; }}</td>
              <td class="text-left">{{ column_model; }}</td>
              <td class="text-right">{{ column_quantity; }}</td>
              <td class="text-right">{{ column_price; }}</td>
              <td class="text-right">{{ column_total; }}</td>
              {% if products) { ?>
              <td style="width: 20px;"></td>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td class="text-left"><?php echo $product['name']; ?>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?></td>
              <td class="text-left"><?php echo $product['model']; ?></td>
              <td class="text-right"><?php echo $product['quantity']; ?></td>
              <td class="text-right"><?php echo $product['price']; ?></td>
              <td class="text-right"><?php echo $product['total']; ?></td>
              <td class="text-right" style="white-space: nowrap;">{% if product['reorder']) { ?>
                <a href="<?php echo $product['reorder']; ?>" data-toggle="tooltip" title="{{ button_reorder }}" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></a>
                <?php } ?>
                <a href="<?php echo $product['return']; ?>" data-toggle="tooltip" title="{{ button_return }}" class="btn btn-danger"><i class="fa fa-reply"></i></a></td>
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <td class="text-left"><?php echo $voucher['description']; ?></td>
              <td class="text-left"></td>
              <td class="text-right">1</td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
              <td class="text-right"><?php echo $voucher['amount']; ?></td>
              {% if products) { ?>
              <td></td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td colspan="3"></td>
              <td class="text-right"><b><?php echo $total['title']; ?></b></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
              {% if products) { ?>
              <td></td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tfoot>
        </table>
      </div>
      {% if comment) { ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left">{{ text_comment }}</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left">{{ comment }}</td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
      {% if histories) { ?>
      <h3>{{ text_history }}</h3>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left">{{ column_date_added; }}</td>
            <td class="text-left">{{ column_status; }}</td>
            <td class="text-left">{{ column_comment; }}</td>
          </tr>
        </thead>
        <tbody>
          {% if histories) { ?>
          <?php foreach ($histories as $history) { ?>
          <tr>
            <td class="text-left"><?php echo $history['date_added']; ?></td>
            <td class="text-left"><?php echo $history['status']; ?></td>
            <td class="text-left"><?php echo $history['comment']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="3" class="text-center">{{ text_no_results }}</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="{{ continue }}" class="btn btn-primary">{{ button_continue }}</a></div>
      </div>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}