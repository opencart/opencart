<?php
/**
 * Created by PhpStorm.
 * User: Jack Wang
 * Date: 2016-08-23
 * Time: 17:32
 */
class ControllerReportSaleAmazonOrder extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('report/sale_amazon_order');
        $this->document->setTitle($this->language->get('heading_title'));

        if(isset($this->request->get['filter_created_after'])) {
            $filter_created_after = $this->request->get['filter_created_after'];
        } else {
            // default now();
            $filter_created_after = '2016-08-29T00:00:01Z';
        }

        if(isset($this->request->get['filter_created_before'])) {
            $filter_created_before = $this->request->get['filter_created_before'];
        } else {
            $filter_created_before = null;
        }

        if(isset($this->request->get['filter_buyer_email'])) {
            $filter_buyer_email = $this->request->get['filter_buyer_email'];
        } else {
            $filter_buyer_email = null;
        }

        $filter_data = array(
            'filter_created_after'  => $filter_created_after,
            'filter_created_before' => $filter_created_before,
            'filter_buyer_email'    =>  $filter_buyer_email
        );

        $url = '';
        $data['token'] = $this->session->data['token'];

        $this->load->model('report/amazon_order');

        $data['orders'] = array();
        $results = $this->model_report_amazon_order->getListOrders($filter_data);

        if(!empty($results)) {
            foreach ($results as $result) {
                $data['orders'][] = array(
                    'NextToken' =>  $result['NextToken'],
                    'CreatedBefore'   =>  $result['CreatedBefore'],
                    'LastUpdatedBefore'     =>  $result['LastUpdatedBefore'],
                    'AmazonOrderId'  =>  $result['AmazonOrderId'],
                    'SellerOrderId'      =>  $result['SellerOrderId'],
                    'PurchaseDate'   =>  empty($result['PurchaseDate']) ? $result['PurchaseDate'] : date_format(new DateTime($result['PurchaseDate']),"Y-m-d H:i:s"),
                    'LastUpdateDate'     =>  empty($result['LastUpdateDate']) ? $result['LastUpdateDate'] : date_format(new DateTime($result['LastUpdateDate']),"Y-m-d H:i:s"),
                    'OrderStatus'     =>  $result['OrderStatus'],
                    'FulfillmentChannel'    =>  $result['FulfillmentChannel'],
                    'SalesChannel'  =>  $result['SalesChannel'],
                    'OrderChannel'  =>  $result['OrderChannel'],
                    'ShipServiceLevel'  =>  $result['ShipServiceLevel'],
                    'Name'  =>  $result['Name'],
                    'AddressLine1'  =>  $result['AddressLine1'],
                    'AddressLine2'  =>  $result['AddressLine2'],
                    'AddressLine3'  =>  $result['AddressLine3'],
                    'City'  =>  $result['City'],
                    'County'  =>  $result['County'],
                    'District'  =>  $result['District'],
                    'StateOrRegion'  =>  $result['StateOrRegion'],
                    'PostalCode'  =>  $result['PostalCode'],
                    'CountryCode'  =>  $result['CountryCode'],
                    'Phone'  =>  $result['Phone'],
                    'CurrencyCode'  =>  $result['CurrencyCode'],
                    'Amount'  =>  $result['Amount'],
                    'NumberOfItemsShipped'  =>  $result['NumberOfItemsShipped'],
                    'NumberOfItemsUnshipped'  =>  $result['NumberOfItemsUnshipped'],
                    'CurrencyCode'  =>  $result['CurrencyCode'],
                    'Amount'  =>  $result['Amount'],
                    'PaymentMethod'  =>  $result['PaymentMethod'],
                    'PaymentMethod'  =>  $result['PaymentMethod'],
                    'MarketplaceId'  =>  $result['MarketplaceId'],
                    'BuyerEmail'  =>  $result['BuyerEmail'],
                    'BuyerName'  =>  $result['BuyerName'],
                    'ShipmentServiceLevelCategory'  =>  $result['ShipmentServiceLevelCategory'],
                    'ShippedByAmazonTFM'  =>  $result['ShippedByAmazonTFM'],
                    'TFMShipmentStatus'  =>  $result['TFMShipmentStatus'],
                    'CbaDisplayableShippingLabel'  =>  $result['CbaDisplayableShippingLabel'],
                    'OrderType'  =>  $result['OrderType'],
                    'EarliestShipDate'  =>  empty($result['EarliestShipDate']) ? $result['EarliestShipDate'] : date_format(new DateTime($result['EarliestShipDate']),"Y-m-d H:i:s"),
                    'LatestShipDate'  =>  empty($result['LatestShipDate']) ? $result['LatestShipDate'] : date_format(new DateTime($result['LatestShipDate']),"Y-m-d H:i:s"),
                    'EarliestDeliveryDate'  =>  empty($result['EarliestDeliveryDate']) ? $result['EarliestDeliveryDate'] : date_format(new DateTime($result['EarliestDeliveryDate']),"Y-m-d H:i:s"),
                    'LatestDeliveryDate'  =>  empty($result['LatestDeliveryDate']) ? $result['LatestDeliveryDate'] : date_format(new DateTime($result['LatestDeliveryDate']),"Y-m-d H:i:s"),
                    'IsBusinessOrder'  =>  $result['IsBusinessOrder'],
                    'PurchaseOrderNumber'  =>  $result['PurchaseOrderNumber'],
                    'IsPrime'  =>  $result['IsPrime'],
                    'IsPremiumOrder'  =>  $result['IsPremiumOrder'],
                    'RequestId'  =>  $result['RequestId'],
                    'view'  =>  $this->url->link('report/sale_amazon_order/amazon_order_info', 'token=' . $this->session->data['token'] . '&amazon_order_id=' . $result['AmazonOrderId'] . $url, true)
                );
            }
        }

        /*if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $order_total = 1;
        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('report/sale_amazon_order', 'token=' . $this->session->data['token'] . $url . '&page={page}',true);

        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
        */

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('report/sale_amazon_order', 'token=' . $this->session->data['token'] . $url, true)
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_all_status'] = $this->language->get('text_all_status');

        $data['column_amazon_order_id'] = $this->language->get('column_amazon_order_id');
        $data['column_seller_order_id'] = $this->language->get('column_seller_order_id');
        $data['column_purchase_date'] = $this->language->get('column_purchase_date');
        $data['column_last_update_date'] = $this->language->get('column_last_update_date');
        $data['column_order_status'] = $this->language->get('column_order_status');
        $data['column_fulfillment_channel'] = $this->language->get('column_fulfillment_channel');
        $data['column_sales_channel'] = $this->language->get('column_sales_channel');
        $data['column_order_channel'] = $this->language->get('column_order_channel');
        $data['column_ship_service_level'] = $this->language->get('column_ship_service_level');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_filter'] = $this->language->get('button_filter');

        $data['modal_title'] = $this->language->get('modal_title');
        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_group'] = $this->language->get('entry_group');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('report/sale_amazon_order',$data));
    }

    public function amazon_order_info() {
        $this->load->language('report/sale_amazon_order');

        $data['text_order_basic_info'] = $this->language->get('text_order_basic_info');
        $data['text_amazon_order_id'] = $this->language->get('text_amazon_order_id');
        $data['text_seller_order_id'] = $this->language->get('text_seller_order_id');
        $data['text_purchase_date'] = $this->language->get('text_purchase_date');
        $data['text_last_update_date'] = $this->language->get('text_last_update_date');
        $data['text_order_status'] = $this->language->get('text_order_status');
        $data['text_fulfillment_channel'] = $this->language->get('text_fulfillment_channel');
        $data['text_sales_channel'] = $this->language->get('text_sales_channel');
        $data['text_order_channel'] = $this->language->get('text_order_channel');
        $data['text_ship_service_level'] = $this->language->get('text_ship_service_level');
        $data['text_shipping_address'] = $this->language->get('text_shipping_address');
        $data['text_name'] = $this->language->get('text_name');
        $data['text_address_line1'] = $this->language->get('text_address_line1');
        $data['text_address_line2'] = $this->language->get('text_address_line2');
        $data['text_address_line3'] = $this->language->get('text_address_line3');
        $data['text_city'] = $this->language->get('text_city');
        $data['text_country'] = $this->language->get('text_country');
        $data['text_district'] = $this->language->get('text_district');
        $data['text_state_or_region'] = $this->language->get('text_state_or_region');
        $data['text_postal_code'] = $this->language->get('text_postal_code');
        $data['text_country_code'] = $this->language->get('text_country_code');
        $data['text_phone'] = $this->language->get('text_phone');
        $data['text_currency_code'] = $this->language->get('text_currency_code');
        $data['text_amount'] = $this->language->get('text_amount');
        $data['text_number_of_items_shipped'] = $this->language->get('text_number_of_items_shipped');
        $data['text_number_of_items_unshipped'] = $this->language->get('text_number_of_items_unshipped');
        $data['text_payment_execution_detail'] = $this->language->get('text_payment_execution_detail');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_marketplace_id'] = $this->language->get('text_marketplace_id');
        $data['text_buyer_email'] = $this->language->get('text_buyer_email');
        $data['text_buyer_name'] = $this->language->get('text_buyer_name');
        $data['text_shipment_service_level_category'] = $this->language->get('text_shipment_service_level_category');
        $data['text_shipped_by_amazon_tfm'] = $this->language->get('text_shipped_by_amazon_tfm');
        $data['text_tfm_shipment_status'] = $this->language->get('text_tfm_shipment_status');
        $data['text_cba_displayable_shipping_label'] = $this->language->get('text_cba_displayable_shipping_label');
        $data['text_order_type'] = $this->language->get('text_order_type');
        $data['text_earliest_ship_date'] = $this->language->get('text_earliest_ship_date');
        $data['text_latest_ship_date'] = $this->language->get('text_latest_ship_date');
        $data['text_earliest_delivery_date'] = $this->language->get('text_earliest_delivery_date');
        $data['text_latest_delivery_date'] = $this->language->get('text_latest_delivery_date');
        $data['text_is_business_order'] = $this->language->get('text_is_business_order');
        $data['text_purchase_order_number'] = $this->language->get('text_purchase_order_number');
        $data['text_is_prime'] = $this->language->get('text_is_prime');
        $data['text_is_premium_order'] = $this->language->get('text_is_premium_order');
        $data['text_payment_detail'] = $this->language->get('text_payment_detail');

        $this->load->model('report/amazon_order');

        $order_info = $this->model_report_amazon_order->getOrder($this->request->get['amazon_order_id']);

        if(!empty($order_info)) {
            $data['amazon_order_id']                  = $order_info['AmazonOrderId'];
            $data['seller_order_id']                  = $order_info['SellerOrderId'];
            $data['purchase_date']                   = empty($order_info['PurchaseDate']) ? $order_info['PurchaseDate'] : date_format(new DateTime($order_info['PurchaseDate']),"Y-m-d H:i:s");
            $data['last_update_date']                 = empty($order_info['LastUpdateDate']) ? $order_info['LastUpdateDate'] : date_format(new DateTime($order_info['LastUpdateDate']),"Y-m-d H:i:s");
            $data['order_status']                    = $order_info['OrderStatus'];
            $data['fulfillment_channel']             = $order_info['FulfillmentChannel'];
            $data['sales_channel']                   = $order_info['SalesChannel'];
            $data['order_channel']                   = $order_info['OrderChannel'];
            $data['ship_service_level']               = $order_info['ShipServiceLevel'];
            $data['name']                               = $order_info['Name'];
            $data['address_line1']                   = $order_info['AddressLine1'];
            $data['address_line2']                   = $order_info['AddressLine2'];
            $data['address_line3']                   = $order_info['AddressLine3'];
            $data['city']                           = $order_info['City'];
            $data['country']                         = $order_info['County'];
            $data['district']                       = $order_info['District'];
            $data['state_or_region']                  = $order_info['StateOrRegion'];
            $data['postal_code']                     = $order_info['PostalCode'];
            $data['country_code']                    = $order_info['CountryCode'];
            $data['phone']                          = $order_info['Phone'];
            $data['currency_code']                   = $order_info['CurrencyCode'];
            $data['amount']                         = $order_info['Amount'];
            $data['number_of_items_shipped']           = $order_info['NumberOfItemsShipped'];
            $data['number_of_items_unshipped']         = $order_info['NumberOfItemsUnshipped'];
            $data['currency_code']                   = $order_info['CurrencyCode'];
            $data['amount']                         = $order_info['Amount'];
            $data['payment_method']                  = $order_info['PaymentMethod'];
            $data['marketplaceId']                  = $order_info['MarketplaceId'];
            $data['buyer_email']                     = $order_info['BuyerEmail'];
            $data['buyer_name']                      = $order_info['BuyerName'];
            $data['shipment_service_level_category']   = $order_info['ShipmentServiceLevelCategory'];
            $data['shipped_by_amazon_tfm']             = $order_info['ShippedByAmazonTFM'];
            $data['tfm_shipment_status']              = $order_info['TFMShipmentStatus'];
            $data['cba_displayable_shipping_label']    = $order_info['CbaDisplayableShippingLabel'];
            $data['order_type']                      = $order_info['OrderType'];
            $data['earliest_ship_date']               = empty($order_info['EarliestShipDate']) ? $order_info['EarliestShipDate'] : date_format(new DateTime($order_info['EarliestShipDate']),"Y-m-d H:i:s");
            $data['latest_ship_date']                 = empty($order_info['LatestShipDate']) ? $order_info['LatestShipDate'] : date_format(new DateTime($order_info['LatestShipDate']),"Y-m-d H:i:s");
            $data['earliest_delivery_date']           = empty($order_info['EarliestDeliveryDate']) ? $order_info['EarliestDeliveryDate'] : date_format(new DateTime($order_info['EarliestDeliveryDate']),"Y-m-d H:i:s");
            $data['latest_delivery_date']             = empty($order_info['LatestDeliveryDate']) ? $order_info['LatestDeliveryDate'] : date_format(new DateTime($order_info['LatestDeliveryDate']),"Y-m-d H:i:s");
            $data['is_business_order']                = $order_info['IsBusinessOrder'];
            $data['purchase_order_number']            = $order_info['PurchaseOrderNumber'];
            $data['is_prime']                        = $order_info['IsPrime'];
            $data['is_premium_order']                 = $order_info['IsPremiumOrder'];
            $data['request_id']                      = $order_info['RequestId'];
        }

        $this->response->setOutput($this->load->view('report/sale_amazon_order_info', $data));
	}
}