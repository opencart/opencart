<?php

namespace Cardinity\Method;

class Error extends ResultObject
{
    /** @type string URL to error’s documentation page */
    private $type;

    /** @type string Title of an error */
    private $title;

    /** @type string HTTP response code */
    private $status;

    /** @type string Human readable information about the error */
    private $detail;

    /** @type array Optional. In case of validation errors all the fields with incorrect information are returned. */
    private $errors = [];

    /**
     * Gets the value of type.
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     * @param mixed $type the type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Gets the value of title.
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the value of title.
     * @param mixed $title the title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Gets the value of status.
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the value of status.
     * @param mixed $status the status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Gets the value of detail.
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Sets the value of detail.
     * @param mixed $detail the detail
     * @return void
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /**
     * Gets the value of errors.
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets the value of errors.
     * @param array $errors the errors
     * @return void
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }
}
