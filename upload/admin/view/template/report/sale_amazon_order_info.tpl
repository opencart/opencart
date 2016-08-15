<!--Amazon Order Detail-->
<div class="container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_order_basic_info; ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_amazon_order_id; ?>:</span>
                    <span class=""><?php echo $amazon_order_id; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_seller_order_id; ?>:</span>
                    <span><?php echo $seller_order_id; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_purchase_date; ?>:</span>
                    <span><?php echo $purchase_date; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_last_update_date; ?>:</span>
                    <span><?php echo $last_update_date; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_order_status; ?>:</span>
                    <span><?php echo $order_status; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_fulfillment_channel; ?>:</span>
                    <span><?php echo $fulfillment_channel; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_sales_channel; ?>:</span>
                    <span><?php echo $sales_channel; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_order_channel; ?>:</span>
                    <span><?php echo $order_channel; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_ship_service_level; ?>:</span>
                    <span><?php echo $ship_service_level; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_number_of_items_shipped; ?>:</span>
                    <span><?php echo $number_of_items_shipped; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_number_of_items_unshipped; ?>:</span>
                    <span><?php echo $number_of_items_unshipped; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_payment_method; ?>:</span>
                    <span><?php echo $payment_method; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_marketplace_id; ?>:</span>
                    <span><?php echo $marketplaceId; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_buyer_email; ?>:</span>
                    <span><?php echo $buyer_email; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_buyer_name; ?>:</span>
                    <span><?php echo $buyer_name; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_shipment_service_level_category; ?>:</span>
                    <span><?php echo $shipment_service_level_category; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_shipped_by_amazon_tfm; ?>:</span>
                    <span><?php echo $shipped_by_amazon_tfm; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_tfm_shipment_status; ?>:</span>
                    <span><?php echo $tfm_shipment_status; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_cba_displayable_shipping_label; ?>:</span>
                    <span><?php echo $cba_displayable_shipping_label; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_order_type; ?>:</span>
                    <span><?php echo $order_type; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_earliest_ship_date; ?>:</span>
                    <span><?php echo $earliest_ship_date; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_latest_ship_date; ?>:</span>
                    <span><?php echo $latest_ship_date; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_earliest_delivery_date; ?>:</span>
                    <span><?php echo $earliest_delivery_date; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_latest_delivery_date; ?>:</span>
                    <span><?php echo $latest_delivery_date; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_is_business_order; ?>:</span>
                    <span><?php echo $is_business_order; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_purchase_order_number; ?>:</span>
                    <span><?php echo $purchase_order_number; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_is_prime; ?>:</span>
                    <span><?php echo $is_prime; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_is_premium_order; ?>:</span>
                    <span><?php echo $is_premium_order; ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-info-circle"></i> Shipping　Address</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_name; ?>:</span>
                    <span><?php echo $name; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_address_line1; ?>:</span>
                    <span><?php echo $address_line1; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_address_line2; ?>:</span>
                    <span><?php echo $address_line2; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_address_line3; ?>:</span>
                    <span><?php echo $address_line3; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_city; ?>:</span>
                    <span><?php echo $city; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_country; ?>:</span>
                    <span><?php echo $country; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_district; ?>:</span>
                    <span><?php echo $district; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_state_or_region; ?>:</span>
                    <span><?php echo $state_or_region; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_postal_code; ?>:</span>
                    <span><?php echo $postal_code; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_country_code; ?>:</span>
                    <span><?php echo $country_code; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_phone; ?>:</span>
                    <span><?php echo $phone; ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-info-circle"></i> Order Total</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_currency_code; ?>:</span>
                    <span><?php echo $currency_code; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_amount; ?>:</span>
                    <span><?php echo $amount; ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_payment_detail; ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <span><?php echo $text_currency_code; ?>:</span>
                    <span><?php echo $currency_code; ?></span>
                </div>
                <div class="col-md-6">
                    <span><?php echo $text_amount; ?>:</span>
                    <span><?php echo $amount; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>