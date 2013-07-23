<?php
class ModelAmazonusListing extends Model {

    private $tabs = array();

    public function search($search_string) {

        $search_params = array(
            'search_string' => $search_string,
        );

        $results = json_decode($this->amazonus->callWithResponse('productv3/search', $search_params), 1);

        $products = array();

        foreach ($results['Products'] as $result) {

            $price = '';

            if ($result['price']['amount'] && $result['price']['currency']) {
                $price = $result['price']['amount'] . ' ' . $result['price']['currency'];
            } else {
                $price = '-';
            }

            $link = 'http://www.amazon.com/gp/product/' . $result['asin'] . '/';

            $products[] = array(
                'name' => $result['name'],
                'asin' => $result['asin'],
                'image' => $result['image'],
                'price' => $price,
                'link' => $link,
            );
        }

        return $products;
    }

    public function getProductByAsin($asin) {
        $data = array(
            'asin' => $asin,
        );

        $results = json_decode($this->amazonus->callWithResponse('productv3/getProduct', $data), 1);

        return $results;
    }

    public function getBestPrice($asin, $condition) {
        $search_params = array(
            'asin' => $asin,
            'condition' => $condition,
        );

        $bestPrice = '';

        $result = json_decode($this->amazonus->callWithResponse('productv3/getPrice', $search_params), 1);
        
        if (isset($result['Price']['Amount']) && $result['Price']['Currency'] && $this->currency->has($result['Price']['Currency'])) {
            $bestPrice['amount'] = number_format($this->currency->convert($result['Price']['Amount'], $result['Price']['Currency'], $this->config->get('config_currency')), 2);
            $bestPrice['shipping'] = number_format($this->currency->convert($result['Price']['Shipping'], $result['Price']['Currency'], $this->config->get('config_currency')), 2);
            $bestPrice['currency'] = $result['Price']['Currency'];
        }

        return $bestPrice;
    }
    
    public function simpleListing($data) {
        $request = array(
            'asin' => $data['asin'],
            'sku' => $data['sku'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'sale' => array(
                'price' => $data['sale_price'],
                'from' => $data['sale_from'],
                'to' => $data['sale_to'],
            ),
            'condition' => $data['condition'],
            'condition_note' => $data['condition_note'],
            'start_selling' => $data['start_selling'],
            'restock_date' => $data['restock_date'],
            'response_url' => HTTPS_CATALOG . 'index.php?route=amazonus/listing',
            'product_id' => $data['product_id'],
        );

        $response = $this->amazonus->callWithResponse('productv3/simpleListing', $request); 
        $response = json_decode($response);
        if (empty($response)) {
            return array(
                'status' => 0,
                'message' => 'Problem connecting OpenBay: API'
            );
        }
        $response = (array) $response;

        if ($response['status'] === 1) {
            $this->db->query("
            REPLACE INTO `" . DB_PREFIX . "amazonus_product`
            SET `product_id` = " . (int) $data['product_id'] . ",
                `status` = 'uploaded',
                `version` = 3,
                `var` = ''
        ");
        }

        return $response;
    }

    public function getBrowseNodes($request){
        return $this->amazonus->callWithResponse('productv3/getBrowseNodes', $request);
    }

}