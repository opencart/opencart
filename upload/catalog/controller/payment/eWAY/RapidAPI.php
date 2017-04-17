<?php

/**
 * Description of RapidAPI
 *
 * @author eWAY
 */
class RapidAPI {

    var $APIConfig;

    function __construct($live_mode=false, $user=null, $pass=null) {
        //Load the configuration
        $APIConfig = parse_ini_file("config.ini");
        if ($live_mode) {
            $APIConfig['PaymentService.Soap'] = 'https://api.ewaypayments.com/soap.asmx?WSDL';
            $APIConfig['PaymentService.POST.CreateAccessCode'] = 'https://api.ewaypayments.com/CreateAccessCode.xml';
            $APIConfig['PaymentService.POST.GetAccessCodeResult'] = 'https://api.ewaypayments.com/GetAccessCodeResult.xml';
            $APIConfig['PaymentService.REST'] = 'https://api.ewaypayments.com/AccessCode';
            $APIConfig['PaymentService.RPC'] = 'https://api.ewaypayments.com/json-rpc';
            $APIConfig['PaymentService.JSONPScript'] = 'https://api.ewaypayments.com/JSONP/v1/js';
        }
        if (isset($user) && strlen($user)) $APIConfig['Payment.Username'] = $user;
        if (isset($pass) && strlen($pass)) $APIConfig['Payment.Password'] = $pass;
        $this->APIConfig = $APIConfig;
    }

    /**
     * Description: Create Access Code
     * @param CreateAccessCodeRequest $request
     * @return StdClass An PHP Ojbect
     */
    public function CreateAccessCode($request) {

        //Is Debug Mode
        if ($this->APIConfig['ShowDebugInfo']) {
            echo "Request Ojbect for CreateAccessCode";
            var_dump($request);
        }

        //Convert An Object to Target Formats
        if ($this->APIConfig['Request:Method'] != "SOAP")
            if ($this->APIConfig['Request:Format'] == "XML")
                if ($this->APIConfig['Request:Method'] != "RPC")
                    $request = EwayParser::Obj2XML($request);
                else
                    $request = EwayParser::Obj2RPCXML("CreateAccessCode", $request);
            else {
                $i = 0;
                $tempClass = new stdClass;
                foreach ($request->Options->Option as $Option) {
                    $tempClass->Options[$i] = $Option;
                    $i++;
                }
                $request->Options = $tempClass->Options;
                $i = 0;
                $tempClass = new stdClass;
                foreach ($request->Items->LineItem as $LineItem) {
                    $tempClass->Items[$i] = $LineItem;
                    $i++;
                }
                $request->Items = $tempClass->Items;
                if ($this->APIConfig['Request:Method'] != "RPC")
                    $request = EwayParser::Obj2JSON($request);
                else
                    $request = EwayParser::Obj2JSONRPC("CreateAccessCode", $request);
            }
        else
            $request = EwayParser::Obj2ARRAY($request);

        //Is Debug Mode
        if ($this->APIConfig['ShowDebugInfo']) {
            echo "Request String for CreateAccessCode";
            var_dump($request);
        }

        $method = 'CreateAccessCode' . $this->APIConfig['Request:Method'];

        $response = $this->$method($request);

        //Is Debug Mode
        if ($this->APIConfig['ShowDebugInfo']) {
            echo "Response String for CreateAccessCode";
            var_dump($response);
        }

        //Convert Response Back TO An Object
        if ($this->APIConfig['Request:Method'] != "SOAP")
            if ($this->APIConfig['Request:Format'] == "XML")
                if ($this->APIConfig['Request:Method'] != "RPC")
                    $result = EwayParser::XML2Obj($response);
                else
                    $result = EwayParser::RPCXML2Obj($response);
            else
            if ($this->APIConfig['Request:Method'] != "RPC")
                $result = EwayParser::JSON2Obj($response);
            else
                $result = EwayParser::JSONRPC2Obj($response);
        else
            $result = $response;

        //Is Debug Mode
        if ($this->APIConfig['ShowDebugInfo']) {
            echo "Response Object for CreateAccessCode";
            var_dump($result);
        }

        return $result;
    }

    /**
     * Description: Get Result with Access Code
     * @param GetAccessCodeResultRequest $request
     * @return StdClass An PHP Ojbect
     */
    public function GetAccessCodeResult($request) {

        if ($this->APIConfig['ShowDebugInfo']) {
            echo "GetAccessCodeResult Request Object";
            var_dump($request);
        }

        //Convert An Object to Target Formats
        if ($this->APIConfig['Request:Method'] != "SOAP")
            if ($this->APIConfig['Request:Format'] == "XML")
                if ($this->APIConfig['Request:Method'] != "RPC")
                    $request = EwayParser::Obj2XML($request);
                else
                    $request = EwayParser::Obj2RPCXML("GetAccessCodeResult", $request);
            else
            if ($this->APIConfig['Request:Method'] != "RPC")
                $request = EwayParser::Obj2JSON($request);
            else
                $request = EwayParser::Obj2JSONRPC("GetAccessCodeResult", $request);
        else
            $request = EwayParser::Obj2ARRAY($request);

        //Build method name
        $method = 'GetAccessCodeResult' . $this->APIConfig['Request:Method'];

                //Is Debug Mode
        if ($this->APIConfig['ShowDebugInfo']) {
            echo "GetAccessCodeResult Request String";
            var_dump($request);
        }

        //Call to the method
        $response = $this->$method($request);

        //Is Debug Mode
        if ($this->APIConfig['ShowDebugInfo']) {
            echo "GetAccessCodeResult Response String";
            var_dump($response);
        }

        //Convert Response Back TO An Object
        if ($this->APIConfig['Request:Method'] != "SOAP")
            if ($this->APIConfig['Request:Format'] == "XML")
                if ($this->APIConfig['Request:Method'] != "RPC")
                    $result = EwayParser::XML2Obj($response);
                else {
                    $result = EwayParser::RPCXML2Obj($response);

                    //Tweak the Options Obj to $obj->Options->Option[$i]->Value instead of $obj->Options[$i]->Value
                    if (isset($result->Options)) {
                        $i = 0;
                        $tempClass = new stdClass;
                        foreach ($result->Options as $Option) {
                            if ( ! ( isset($tempClass->Option) && is_object($tempClass->Option[$i]) ) ) {
                                $tempClass->Option[$i] = new stdClass;
                            }
                            $tempClass->Option[$i]->Value = $Option->Value;
                            $i++;
                        }
                        $result->Options = $tempClass;
                    }
                } else {
                if ($this->APIConfig['Request:Method'] == "RPC")
                    $result = EwayParser::JSONRPC2Obj($response);
                else
                    $result = EwayParser::JSON2Obj($response);

                //Tweak the Options Obj to $obj->Options->Option[$i]->Value instead of $obj->Options[$i]->Value
                if (isset($result->Options)) {
                    $i = 0;
                    $tempClass = new stdClass;
                    foreach ($result->Options as $Option) {
                        if ( ! ( isset($tempClass->Option) && is_object($tempClass->Option[$i]) ) ) {
                            $tempClass->Option[$i] = new stdClass;
                        }
                        $tempClass->Option[$i]->Value = $Option->Value;
                        $i++;
                    }
                    $result->Options = $tempClass;
                }
            }
        else
            $result = $response;

        //Is Debug Mode
        if ($this->APIConfig['ShowDebugInfo']) {
            echo "GetAccessCodeResult Response Object";
            var_dump($result);
        }

        return $result;
    }

    /**
     * Description: Create Access Code Via SOAP
     * @param Array $request
     * @return StdClass An PHP Ojbect
     */
    public function CreateAccessCodeSOAP($request) {

        try {
            $client = new SoapClient($this->APIConfig["PaymentService.Soap"], array(
                        'trace' => false,
                        'exceptions' => true,
                        'login' => $this->APIConfig['Payment.Username'],
                        'password' => $this->APIConfig['Payment.Password'],
                    ));
            $result = $client->CreateAccessCode(array('request' => $request));
        } catch (Exception $e) {
            $lblError = $e->getMessage();
        }

        if (isset($lblError)) {
            echo "<h2>CreateAccessCode SOAP Error: $lblError</h2><pre>";
            die();
        }
        else
            return $result->CreateAccessCodeResult;
    }

    /**
     * Description: Get Result with Access Code Via SOAP
     * @param Array $request
     * @return StdClass An PHP Ojbect
     */
    public function GetAccessCodeResultSOAP($request) {

        try {
            $client = new SoapClient($this->APIConfig["PaymentService.Soap"], array(
                        'trace' => false,
                        'exceptions' => true,
                        'login' => $this->APIConfig['Payment.Username'],
                        'password' => $this->APIConfig['Payment.Password'],
                    ));
            $result = $client->GetAccessCodeResult(array('request' => $request));
        } catch (Exception $e) {
            $lblError = $e->getMessage();
        }

        if (isset($lblError)) {
            echo "<h2>GetAccessCodeResult SOAP Error: $lblError</h2><pre>";
            die();
        }
        else
            return $result->GetAccessCodeResultResult;
    }

    /**
     * Description: Create Access Code Via REST POST
     * @param XML/JSON Format $request
     * @return XML/JSON Format Response
     */
    public function CreateAccessCodeREST($request) {

        $response = $this->PostToRapidAPI($this->APIConfig["PaymentService.REST"] . "s", $request);

        return $response;
    }

    /**
     * Description: Get Result with Access Code Via REST GET
     * @param XML/JSON Format $request
     * @return XML/JSON Format Response
     */
    public function GetAccessCodeResultREST($request) {

        $response = $this->PostToRapidAPI($this->APIConfig["PaymentService.REST"] . "/" . $_GET['AccessCode'], $request, false);

        return $response;
    }

    /**
     * Description: Create Access Code Via HTTP POST
     * @param XML/JSON Format $request
     * @return XML/JSON Format Response
     */
    public function CreateAccessCodePOST($request) {

        $response = $this->PostToRapidAPI($this->APIConfig["PaymentService.POST.CreateAccessCode"], $request);

        return $response;
    }

    /**
     * Description: Get Result with Access Code Via HTTP POST
     * @param XML/JSON Format $request
     * @return XML/JSON Format Response
     */
    public function GetAccessCodeResultPOST($request) {

        $response = $this->PostToRapidAPI($this->APIConfig["PaymentService.POST.GetAccessCodeResult"], $request);

        return $response;
    }

    /**
     * Description: Create Access Code Via HTTP POST
     * @param XML/JSON Format $request
     * @return XML/JSON Format Response
     */
    public function CreateAccessCodeRPC($request) {

        $response = $this->PostToRapidAPI($this->APIConfig["PaymentService.RPC"], $request);

        return $response;
    }

    /**
     * Description: Get Result with Access Code Via HTTP POST
     * @param XML/JSON Format $request
     * @return XML/JSON Format Response
     */
    public function GetAccessCodeResultRPC($request) {

        $response = $this->PostToRapidAPI($this->APIConfig["PaymentService.RPC"], $request);

        return $response;
    }

    /*
     * Description A Function for doing a Curl GET/POST
     */

    private function PostToRapidAPI($url, $request, $IsPost = true) {

        $ch = curl_init($url);

        if ($this->APIConfig['Request:Format'] == "XML")
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        else
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));

        curl_setopt($ch, CURLOPT_USERPWD, $this->APIConfig['Payment.Username'] . ":" . $this->APIConfig['Payment.Password']);
        if ($IsPost)
            curl_setopt($ch, CURLOPT_POST, true);
        else
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        if (curl_errno($ch) != CURLE_OK) {
            echo "<h2>POST Error: " . curl_error($ch) . " URL: $url</h2><pre>";
            die();
        } else {
            curl_close($ch);
            return $response;
        }
    }

}

/**
 * Description of CreateAccessCodeRequest
 *
 *
 */
class CreateAccessCodeRequest {

    /**
     * @var Customer $Customer
     */
    public $Customer;

    /**
     * @var ShippingAddress $ShippingAddress
     */
    public $ShippingAddress;
    public $Items;
    public $Options;

    /**
     * @var Payment $Payment
     */
    public $Payment;
    public $RedirectUrl;
    public $Method;
    private $CustomerIP;
    private $DeviceID;

    function __construct() {

        $this->Customer = new EwayCustomer();
        $this->ShippingAddress = new EwayShippingAddress();
        $this->Payment = new EwayPayment();
        $this->CustomerIP = $_SERVER["SERVER_NAME"];
    }

}

/**
 * Description of Customer
 */
class EwayCustomer {

    public $TokenCustomerID;
    public $Reference;
    public $Title;
    public $FirstName;
    public $LastName;
    public $CompanyName;
    public $JobDescription;
    public $Street1;
    public $Street2;
    public $City;
    public $State;
    public $PostalCode;
    public $Country;
    public $Email;
    public $Phone;
    public $Mobile;
    public $Comments;
    public $Fax;
    public $Url;

}

class EwayShippingAddress {

    public $FirstName;
    public $LastName;
    public $Street1;
    public $Street2;
    public $City;
    public $State;
    public $Country;
    public $PostalCode;
    public $Email;
    public $Phone;
    public $ShippingMethod;

}

class EwayItems {

    public $LineItem = array();

}

class EwayLineItem {

    public $SKU;
    public $Description;

}

class EwayOptions {

    public $Option = array();

}

class EwayOption {

    public $Value;

}

class EwayPayment {

    public $TotalAmount;
    /// <summary>The merchant's invoice number</summary>
    public $InvoiceNumber;
    /// <summary>merchants invoice description</summary>
    public $InvoiceDescription;
    /// <summary>The merchant's invoice reference</summary>
    public $InvoiceReference;
    /// <summary>The merchant's currency</summary>
    public $CurrencyCode;

}

class GetAccessCodeResultRequest {

    public $AccessCode;

}

/*
 * Description A Class for conversion between different formats
 */

class EwayParser {

    public static function Obj2JSON($obj) {

        return json_encode($obj);
    }

    public static function Obj2JSONRPC($APIAction, $obj) {

        if ($APIAction == "CreateAccessCode") {
            //Tweak the request object in order to generate a valid JSON-RPC format for RapidAPI.
            $obj->Payment->TotalAmount = (int) $obj->Payment->TotalAmount;
        }

        $tempClass = new stdClass;
        $tempClass->id = 1;
        $tempClass->method = $APIAction;
        $tempClass->params->request = $obj;

        return json_encode($tempClass);
    }

    public static function Obj2ARRAY($obj) {
        //var_dump($obj);
        return get_object_vars($obj);
    }

    public static function Obj2XML($obj) {

        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->setIndent(TRUE);

        $xml->startElement(get_class($obj));
        $xml->writeAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $xml->writeAttribute("xmlns:xsd", "http://www.w3.org/2001/XMLSchema");

        self::getObject2XML($xml, $obj);

        $xml->endElement();

        $xml->endElement();

        return $xml->outputMemory(true);
    }

    public static function Obj2RPCXML($APIAction, $obj) {

        if ($APIAction == "CreateAccessCode") {
            //Tweak the request object in order to generate a valid XML-RPC format for RapidAPI.
            $obj->Payment->TotalAmount = (int) $obj->Payment->TotalAmount;

            $obj->Items = $obj->Items->LineItem;

            $obj->Options = $obj->Options->Option;

            $obj->Customer->TokenCustomerID = (float) (isset($obj->Customer->TokenCustomerID) ? $obj->Customer->TokenCustomerID : null);

            return str_replace("double>", "long>", xmlrpc_encode_request($APIAction, get_object_vars($obj)));
        }

        if ($APIAction == "GetAccessCodeResult") {
            return xmlrpc_encode_request($APIAction, get_object_vars($obj));
        }
    }

    public static function JSON2Obj($obj) {
        return json_decode($obj);
    }

    public static function JSONRPC2Obj($obj) {


        $tempClass = json_decode($obj);

        if (isset($tempClass->error)) {
            $tempClass->Errors = $tempClass->error->data;
            return $tempClass;
        }

        return $tempClass->result;
    }

    public static function XML2Obj($obj) {
        //Strip the empty JSON object
        return json_decode(str_replace("{}", "null", json_encode(simplexml_load_string($obj))));
    }

    public static function RPCXML2Obj($obj) {
        return json_decode(json_encode(xmlrpc_decode($obj)));
    }

    public static function HasProperties($obj) {
        if (is_object($obj)) {
            $reflect = new ReflectionClass($obj);
            $props = $reflect->getProperties();
            return !empty($props);
        }
        else
            return TRUE;
    }

    private static function getObject2XML(XMLWriter $xml, $data) {
        foreach ($data as $key => $value) {

            if ($key == "TokenCustomerID" && $value == "") {
                $xml->startElement("TokenCustomerID");
                $xml->writeAttribute("xsi:nil", "true");
                $xml->endElement();
            }

            if (is_object($value)) {
                $xml->startElement($key);
                self::getObject2XML($xml, $value);
                $xml->endElement();
                continue;
            } else if (is_array($value)) {
                self::getArray2XML($xml, $key, $value);
            }

            if (is_string($value)) {
                $xml->writeElement($key, $value);
            }
        }
    }

    private static function getArray2XML(XMLWriter $xml, $keyParent, $data) {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $xml->writeElement($keyParent, $value);
                continue;
            }

            if (is_numeric($key)) {
                $xml->startElement($keyParent);
            }

            if (is_object($value)) {
                self::getObject2XML($xml, $value);
            } else if (is_array($value)) {
                $this->getArray2XML($xml, $key, $value);
                continue;
            }

            if (is_numeric($key)) {
                $xml->endElement();
            }
        }
    }

}

?>
