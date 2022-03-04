<?php

namespace Braintree;

/**
 * Creates an instance of AccountUpdaterDailyReport
 *
 * For attributes see our {@link https://developer.paypal.com/braintree/docs/reference/general/webhooks/account-updater/php#notification-kinds developer documentation}
 */
class AccountUpdaterDailyReport extends Base
{
    protected $_attributes = [];

    protected function _initialize($disputeAttribs)
    {
        $this->_attributes = $disputeAttribs;
    }

    /**
     * Creates an instance of an AccountUpdaterDailyReport from given attributes
     *
     * @param array $attributes to generate new AccountUpdaterDailyReport
     *
     * @return AccountUpdaterDailyReport
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        $display = [
            'reportDate', 'reportUrl'
            ];

        $displayAttributes = [];
        foreach ($display as $attrib) {
            $displayAttributes[$attrib] = $this->$attrib;
        }
        return __CLASS__ . '[' .
                Util::attributesToString($displayAttributes) . ']';
    }
}
