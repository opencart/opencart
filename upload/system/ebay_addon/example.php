<?php
/**
 * The name of your addon class must be identical to the file name. I.e. class example = exmaple.php
 */
class example
{
    function __construct()
    {
        /**
         * The contructor MUST contain the Name and Version.
         */
        $this->addonName        = 'Example addon';
        $this->addonVersion     = '123';
    }
}
?>
