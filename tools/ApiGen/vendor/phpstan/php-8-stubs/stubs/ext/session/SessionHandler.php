<?php 

class SessionHandler implements \SessionHandlerInterface, \SessionIdInterface
{
    /**
     * @tentative-return-type
     * @return bool
     */
    public function open(string $path, string $name)
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
     * @return (string | false)
     */
    public function read(string $id)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function write(string $id, string $data)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function destroy(string $id)
    {
    }
    /**
     * @tentative-return-type
     * @return (int | false)
     */
    public function gc(int $max_lifetime)
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function create_sid()
    {
    }
}