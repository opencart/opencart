<?php 

class IntlPartsIterator extends \IntlIterator
{
    /**
     * @tentative-return-type
     * @return IntlBreakIterator
     */
    public function getBreakIterator()
    {
    }
    /** @tentative-return-type */
    #[\Since('8.1')]
    public function getRuleStatus() : int
    {
    }
}