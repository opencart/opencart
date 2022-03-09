<?php
namespace Braintree;

class WebhookTesting
{
    public static function sampleNotification($kind, $id, $sourceMerchantId = null)
    {
        return Configuration::gateway()->webhookTesting()->sampleNotification($kind, $id, $sourceMerchantId);
    }
}
class_alias('Braintree\WebhookTesting', 'Braintree_WebhookTesting');
