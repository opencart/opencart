<?php 

class OCICollection
{
    /**
     * @alias oci_free_collection
     * @tentative-return-type
     * @return bool
     */
    public function free()
    {
    }
    /**
     * @alias oci_collection_append
     * @tentative-return-type
     * @return bool
     */
    public function append(string $value)
    {
    }
    /**
     * @alias oci_collection_element_get
     * @tentative-return-type
     * @return (string | float | null | false)
     */
    public function getElem(int $index)
    {
    }
    /**
     * @alias oci_collection_assign
     * @tentative-return-type
     * @return bool
     */
    public function assign(OCICollection $from)
    {
    }
    /**
     * @alias oci_collection_element_assign
     * @tentative-return-type
     * @return bool
     */
    public function assignElem(int $index, string $value)
    {
    }
    /**
     * @alias oci_collection_size
     * @tentative-return-type
     * @return (int | false)
     */
    public function size()
    {
    }
    /**
     * @alias oci_collection_max
     * @tentative-return-type
     * @return (int | false)
     */
    public function max()
    {
    }
    /**
     * @alias oci_collection_trim
     * @tentative-return-type
     * @return bool
     */
    public function trim(int $num)
    {
    }
}