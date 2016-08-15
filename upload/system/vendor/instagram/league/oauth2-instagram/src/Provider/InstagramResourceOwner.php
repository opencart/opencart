<?php namespace League\OAuth2\Client\Provider;

class InstagramResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->response['data']['id'] ?: null;
    }

    /**
     * Get user imageurl
     *
     * @return string|null
     */
    public function getImageurl()
    {
        return $this->response['data']['profile_picture'] ?: null;
    }

    /**
     * Get user name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->response['data']['full_name'] ?: null;
    }

    /**
     * Get user nickname
     *
     * @return string|null
     */
    public function getNickname()
    {
        return $this->response['data']['username'] ?: null;
    }

    /**
     * Get user description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->response['data']['bio'] ?: null;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response['data'];
    }
}
