<?php
namespace Braintree;

/**
 * Processor response types.
 *
 * @package    Braintree
 */
class ProcessorResponseTypes
{
   const APPROVED      = 'approved';
   const SOFT_DECLINED = 'soft_declined';
   const HARD_DECLINED = 'hard_declined';
}
class_alias('Braintree\ProcessorResponseTypes', 'Braintree_ProcessorResponseTypes');
