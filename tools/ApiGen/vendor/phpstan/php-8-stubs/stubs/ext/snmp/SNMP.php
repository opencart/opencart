<?php 

class SNMP
{
    public function __construct(int $version, string $hostname, string $community, int $timeout = -1, int $retries = -1)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function close()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setSecurity(string $securityLevel, string $authProtocol = "", string $authPassphrase = "", string $privacyProtocol = "", string $privacyPassphrase = "", string $contextName = "", string $contextEngineId = "")
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function get(array|string $objectId, bool $preserveKeys = false)
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function getnext(array|string $objectId)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
     */
    public function walk(array|string $objectId, bool $suffixAsKey = false, int $maxRepetitions = -1, int $nonRepeaters = -1)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function set(array|string $objectId, array|string $type, array|string $value)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function getErrno()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function getError()
    {
    }
}