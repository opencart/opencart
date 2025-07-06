<?php

namespace Cardinity\Method\Chargeback;

use Cardinity\Method\MethodInterface;
use Cardinity\Method\MethodResultCollectionInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GetAll implements MethodResultCollectionInterface
{
    private $limit;
    private $payment_id;

    /**
     * GetAll chargebacks
     *
     * @param integer $limit number of items to return default 10
     * @param [type] $payment_id [optional] parent payment_id of chargebacks
     */
    public function __construct($limit = 10, $payment_id = null)
    {
        $this->limit = $limit;
        $this->payment_id = $payment_id;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getAction()
    {
        if($this->payment_id == null){
            return 'payments/chargebacks';
        } else {
            return sprintf('payments/%s/chargebacks/', $this->payment_id);
        }
    }

    public function getMethod()
    {
        return MethodInterface::GET;
    }

    public function createResultObject()
    {
        return new Chargeback();
    }

    public function getAttributes()
    {
        return [
            'limit' => $this->getLimit()
        ];
    }

    public function getValidationConstraints()
    {
        return new Assert\Collection([
            'limit' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\Type(['type' => 'integer']),
            ]),
        ]);
    }
}
