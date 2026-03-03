<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Currency;
/**
 * Class ECB
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Currency
 */
class ECB extends \Opencart\System\Engine\Controller {
    /**
     * Currency
     *
     * @param string $default
     *
     * @return void
     */
    public function currency(string $default = ''): void {
        if ($this->config->get('currency_ecb_status')) {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($curl);

            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if ($status == 200) {
                $dom = new \DOMDocument('1.0', 'UTF-8');
                $dom->loadXml($response);

                $cube = $dom->getElementsByTagName('Cube')->item(0);

                // Compile all the rates into an array
                $currencies = [];

                $EUR = 'EUR';
                $currencies[$EUR] = 1.0000;

                foreach ($cube->getElementsByTagName('Cube') as $currency) {
                    if ($currency->getAttribute('currency')) {
                        $currencies[$currency->getAttribute('currency')] = $currency->getAttribute('rate');
                    }
                }

                if (count($currencies) > 1) {
                    $this->load->model('localisation/currency');

                    $results = $this->model_localisation_currency->getCurrencies();

                    $secondServiceJson = false;
                    foreach ($results as $result) {
                        if (isset($currencies[$result['code']])) {
                            $from = $currencies[$EUR];

                            if (!empty($currencies[$result['code']]) && !empty($currencies[$default])) {
                                $to = $currencies[$result['code']];

                                $this->model_localisation_currency->editValueByCode($result['code'], 1 / ($currencies[$default] * ($from / $to)));
                            } else {
                                try {
                                    if (!$secondServiceJson) {
                                        $ch = curl_init('https://api.exchangerate-api.com/v4/latest/'.$EUR);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        $json = curl_exec($ch);
                                        if ($json) {
                                            $secondServiceJson = json_decode($json, true);
                                        }
                                    }

                                    if ($secondServiceJson
                                        && !empty($secondServiceJson['rates'][$default])
                                        && !empty($secondServiceJson['rates'][$result['code']])
                                    ) {
                                        $to = $secondServiceJson['rates'][$result['code']];

                                        $this->model_localisation_currency->editValueByCode($result['code'], 1 / ($secondServiceJson['rates'][$default] * ($from / $to)));
                                    }
                                }catch (\Exception $e) {}
                            }
                        }
                    }
                }

                $this->model_localisation_currency->editValueByCode($default, 1.00000);

                $this->cache->delete('currency');
            }
        }
    }
}
