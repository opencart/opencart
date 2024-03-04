<?php 

class OCILob
{
    /**
     * @alias oci_lob_save
     * @tentative-return-type
     * @return bool
     */
    public function save(string $data, int $offset = 0)
    {
    }
    /**
     * @alias oci_lob_import
     * @tentative-return-type
     * @return bool
     */
    public function import(string $filename)
    {
    }
    /**
     * @alias oci_lob_import
     * @tentative-return-type
     * @return bool
     */
    public function saveFile(string $filename)
    {
    }
    /**
     * @alias oci_lob_load
     * @tentative-return-type
     * @return (string | false)
     */
    public function load()
    {
    }
    /**
     * @alias oci_lob_read
     * @tentative-return-type
     * @return (string | false)
     */
    public function read(int $length)
    {
    }
    /**
     * @alias oci_lob_eof
     * @tentative-return-type
     * @return bool
     */
    public function eof()
    {
    }
    /**
     * @alias oci_lob_tell
     * @tentative-return-type
     * @return (int | false)
     */
    public function tell()
    {
    }
    /**
     * @alias oci_lob_rewind
     * @tentative-return-type
     * @return bool
     */
    public function rewind()
    {
    }
    /**
     * @alias oci_lob_seek
     * @tentative-return-type
     * @return bool
     */
    public function seek(int $offset, int $whence = OCI_SEEK_SET)
    {
    }
    /**
     * @alias oci_lob_size
     * @tentative-return-type
     * @return (int | false)
     */
    public function size()
    {
    }
    /**
     * @alias oci_lob_write
     * @tentative-return-type
     * @return (int | false)
     */
    public function write(string $data, ?int $length = null)
    {
    }
    /**
     * @alias oci_lob_append
     * @tentative-return-type
     * @return bool
     */
    public function append(OCILob $from)
    {
    }
    /**
     * @alias oci_lob_truncate
     * @tentative-return-type
     * @return bool
     */
    public function truncate(int $length = 0)
    {
    }
    /**
     * @alias oci_lob_erase
     * @tentative-return-type
     * @return (int | false)
     */
    public function erase(?int $offset = null, ?int $length = null)
    {
    }
    /**
     * @alias oci_lob_flush
     * @return bool
     */
    public function flush(int $flag = 0) : bool
    {
    }
    /**
     * @alias ocisetbufferinglob
     * @tentative-return-type
     * @return bool
     */
    public function setBuffering(bool $mode)
    {
    }
    /**
     * @alias ocigetbufferinglob
     * @tentative-return-type
     * @return bool
     */
    public function getBuffering()
    {
    }
    /**
     * @alias oci_lob_export
     * @tentative-return-type
     * @return bool
     */
    public function writeToFile(string $filename, ?int $offset = null, ?int $length = null)
    {
    }
    /**
     * @alias oci_lob_export
     * @tentative-return-type
     * @return bool
     */
    public function export(string $filename, ?int $offset = null, ?int $length = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function writeTemporary(string $data, int $type = OCI_TEMP_CLOB)
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
     * @alias oci_free_descriptor
     * @tentative-return-type
     * @return bool
     */
    public function free()
    {
    }
}