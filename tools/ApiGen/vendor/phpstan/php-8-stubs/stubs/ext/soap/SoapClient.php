<?php 

class SoapClient
{
    public function __construct(?string $wsdl, array $options = [])
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
    }
    /**
     * @param (SoapHeader | array | null) $inputHeaders
     * @param array $outputHeaders
     * @tentative-return-type
     * @return mixed
     */
    public function __soapCall(string $name, array $args, ?array $options = null, $inputHeaders = null, &$outputHeaders = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | null)
     */
    public function __getFunctions()
    {
    }
    /**
     * @tentative-return-type
     * @return (array | null)
     */
    public function __getTypes()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function __getLastRequest()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function __getLastResponse()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function __getLastRequestHeaders()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function __getLastResponseHeaders()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function __doRequest(string $request, string $location, string $action, int $version, bool $oneWay = false)
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function __setCookie(string $name, ?string $value = null)
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function __getCookies()
    {
    }
    /**
     * @param (SoapHeader | array | null) $headers
     * @tentative-return-type
     * @return bool
     */
    public function __setSoapHeaders($headers = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function __setLocation(?string $location = null)
    {
    }
}