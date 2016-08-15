<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <div class="well">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="input-created-after">Credted After</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_created_after" value="" placeholder="Created After" data-date-format="YYYY-MM-DD" id="input-created-after" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-created-before">Credted Before</label>
                                <div class="input-group date">
                                    <input type="text" name="filter_created_before" value="" placeholder="Created Before" data-date-format="YYYY-MM-DD" id="input-created-before" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="input-buyer-email">Buyer Email</label>
                                <input type="text" name="filter_buyer_email" value="" placeholder="Buyer Email" id="input-buyer-email" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td class="text-left"><?php echo $column_amazon_order_id; ?></td>
                                <td class="text-left"><?php echo $column_seller_order_id; ?></td>
                                <td class="text-right"><?php echo $column_purchase_date; ?></td>
                                <td class="text-right"><?php echo $column_last_update_date; ?></td>
                                <td class="text-right"><?php echo $column_order_status; ?></td>
                                <td class="text-right"><?php echo $column_fulfillment_channel; ?></td>
                                <td class="text-right"><?php echo $column_sales_channel; ?></td>
                                <td class="text-right"><?php echo $column_order_channel; ?></td>
                                <td class="text-right"><?php echo $column_ship_service_level; ?></td>
                                <td class="text-right"><?php echo $column_name; ?></td>
                                <td class="text-right"><?php echo $column_action; ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($orders) { ?>
                            <?php foreach ($orders as $order) { ?>
                            <tr>
                                <td class="text-left"><?php echo $order['AmazonOrderId']; ?></td>
                                <td class="text-left"><?php echo $order['SellerOrderId']; ?></td>
                                <td class="text-right"><?php echo $order['PurchaseDate']; ?></td>
                                <td class="text-right"><?php echo $order['LastUpdateDate']; ?></td>
                                <td class="text-right"><?php echo $order['OrderStatus']; ?></td>
                                <td class="text-right"><?php echo $order['FulfillmentChannel']; ?></td>
                                <td class="text-right"><?php echo $order['SalesChannel']; ?></td>
                                <td class="text-right"><?php echo $order['OrderChannel']; ?></td>
                                <td class="text-right"><?php echo $order['ShipServiceLevel']; ?></td>
                                <td class="text-right"><?php echo $order['Name']; ?></td>
                                <td class="text-right">
                                    <a href="#modal" data-toggle="modal" title="" data-action="<?php echo $order['view'] ?>" class="btn btn-info" data-original-title="View"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!--<div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>-->
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $('.table a[data-toggle="modal"]').click(function () {
                $('#modal .modal-body').load($(this).data('action'));
            });

            $('.date').datetimepicker({
                pickTime: false
            });

            $('#button-filter').on('click', function () {
                var url = 'index.php?route=report/sale_amazon_order&token=<?php echo $token ?>';

                var filter_created_after = $('input[name="filter_created_after"]').val();

                if(filter_created_after) {
                    url += '&filter_created_after=' + encodeURIComponent(filter_created_after);
                }

                var filter_created_before = $('input[name="filter_created_before"]').val();

                if(filter_created_before) {
                    url += '&filter_created_before=' + encodeURIComponent(filter_created_before);
                }

                var filter_buyer_email = $('input[name="filter_buyer_email"]').val();
                if(filter_buyer_email) {
                    url += '&filter_buyer_email=' + encodeURIComponent(filter_buyer_email);
                }

                location = url;
            });
        });
    </script>
</div>
<!--Modal-->
<div id="modal" class="modal fade modal-lg" tabindex="1" role="dialog" aria-labelledby="myLargeModalLabel" style="margin: 0 auto;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $modal_title; ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>