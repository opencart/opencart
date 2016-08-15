<?php
/**
 * Created by PhpStorm.
 * User: Jack Wang
 * Date: 2016-08-23
 * Time: 17:57
 */
class ModelReportAmazonOrder extends Model {
    public function getListOrders($entry_data) {
        require_once(DIR_SYSTEM . 'orders/MarketplaceWebServiceOrders/Samples/.config.inc.php');

        $config = array(
            'ServiceURL' => SERVICE_URL,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        );

        require_once(DIR_SYSTEM . 'orders/MarketplaceWebServiceOrders/Client.php');
        $service = new MarketplaceWebServiceOrders_Client(
            AWS_ACCESS_KEY_ID,
            AWS_SECRET_ACCESS_KEY,
            APPLICATION_NAME,
            APPLICATION_VERSION,
            $config);

        // Mock
        require_once(DIR_SYSTEM . 'orders/MarketplaceWebServiceOrders/Mock.php');
        $service = new MarketplaceWebServiceOrders_Mock();

        require_once(DIR_SYSTEM . 'orders/MarketplaceWebServiceOrders/Model/ListOrdersRequest.php');
        $request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
        $request->setMarketplaceId(MARKETPLACE_ID);
        $request->setSellerId(MERCHANT_ID);

        if(isset($entry_data['filter_created_after'])) {
            $request->setCreatedAfter($entry_data['filter_created_after']);
        }

        if(isset($entry_data['filter_created_before'])) {
            $request->setCreatedBefore($entry_data['filter_created_before']);
        }

        if(isset($entry_data['filter_buyer_email'])) {
            $request->setCreatedBefore($entry_data['filter_buyer_email']);
        }

        try {
            $response = $service->ListOrders($request);

            $dom = new DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            //print_r("results:" . $dom->saveXML());
            //print_r(json_decode(json_encode((array) simplexml_load_string($dom->saveXML())), true));
            $results = json_decode(json_encode((array) simplexml_load_string($dom->saveXML())), true);
            $data['orders'][] = $this->array_multi2single($results);

            return $data['orders'];

            //echo("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
            //return $response->getResponseHeaderMetadata();
        } catch (MarketplaceWebServiceOrders_Exception $ex) {
            echo("Caught Exception: " . $ex->getMessage() . "\n");
            echo("Response Status Code: " . $ex->getStatusCode() . "\n");
            echo("Error Code: " . $ex->getErrorCode() . "\n");
            echo("Error Type: " . $ex->getErrorType() . "\n");
            echo("Request ID: " . $ex->getRequestId() . "\n");
            echo("XML: " . $ex->getXML() . "\n");
            echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
        }
    }

    public function getOrder($amazon_order_id) {
        require_once(DIR_SYSTEM . 'orders/MarketplaceWebServiceOrders/Samples/.config.inc.php');

        $config = array(
            'ServiceURL' => SERVICE_URL,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        );

        require_once(DIR_SYSTEM . 'orders/MarketplaceWebServiceOrders/Client.php');
        $service = new MarketplaceWebServiceOrders_Client(
            AWS_ACCESS_KEY_ID,
            AWS_SECRET_ACCESS_KEY,
            APPLICATION_NAME,
            APPLICATION_VERSION,
            $config
        );

        // Mock
        require_once(DIR_SYSTEM . 'orders/MarketplaceWebServiceOrders/Mock.php');
        $service = new MarketplaceWebServiceOrders_Mock();

        require_once(DIR_SYSTEM . 'orders/MarketplaceWebServiceOrders/Model/GetOrderRequest.php');
        $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();

        $request->setSellerId(MERCHANT_ID);
        $request->setAmazonOrderId($amazon_order_id);

        $response = $service->getOrder($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        $order = json_decode(json_encode((array) simplexml_load_string($dom->saveXML())), true);
        return $this->array_multi2single($order);
        //$response->getResponseHeaderMetadata();
    }

    function array_multi2single($array){
        static $result_array=array();
        foreach($array as $key => $value){
            if(is_array($value)){
                $this->array_multi2single($value);
            }
            else
                $result_array[$key]=$value;
        }
        return $result_array;
    }
}