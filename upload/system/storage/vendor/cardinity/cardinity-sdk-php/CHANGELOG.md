# Cardinity Client PHP Library

## v3.0.3

### Added
- Added `Method\Validators\CallbackUrlConstraint` custom validation constraint class
- Added `Method\Validators\CallbackUrlConstraintValidator` custom constraint validation class
- Added `getInsideArray` method to `Method\Payment\Create` class
- Added `getNotificationUrlConstraints` method to `Method\Payment\Create` for `notification_url` parameter
- Added `getIpAddressConstraints` method to `Method\Payment\Create` for `ip_address` parameter.
- Added unit tests for new methods.
- Added `getStatementDescriptorDuffix` method to `Method\Payment\Payment` for `statement_descriptor_prefix` parameter.
- Added `setStatementDescriptorDuffix` method to `Method\Payment\Payment` for `statement_descriptor_prefix` parameter.


### Updated
- Updated `getThreeDS2DataConstraints` method of `Method\Payment\Create` class
- Updated `getBrowserInfoConstraints` method of `Method\Payment\Create` class.
- Updated `getValidationConstraints` method of `Method\Payment\Create` class

## v3.0.2

### Changed

- Changed `getThreeds2data` method to `getThreeds2Data` of class `Method\Payment\Payment`
- Changed `setThreeds2data` method to `setThreeds2Data` of class `Method\Payment\Payment`
- Changed `unserialize` method of `Method\ResultObject` class
- Changed `propertyName` method of `Method\ResultObject` class


## v3.0.1

### Fixed

- Removed redundant comma in `Method\Payment\Create.php` line 84.

## v3.0.0

### Added
- Added `threeDS2AuthorizationInformation` property to `Payment` class
- Added `getThreeds2data` method to `Payment` class
- Added `setThreeds2data` method to `Payment` class
- Added `isThreedsV1` method to `Payment` class
- Added `isThreedsV2` method to `Payment` class
- Added `getThreeDS2DataConstraints` method to `Create` class
- Added `getBrowserInfoConstraints` method to `Create` class
- Added `getAdressConstraints` method to `Create` class
- Added `getCardHolderInfoConstraints` method to `Create` class
- Added `buildElement` method to `Create` class
- Added `paymentId` property to `Finalize` class
- Added `finalizeKey` property to `Finalize` class
- Added `Method\Payment\ThreeDS2Data` parameters
- Added `Method\Payment\TreeDS2AthorizationInformation` class

### Changed
- Updated `php` to version 7.2.x
- Updated `symfony/validator` to versin 5.x
- Updated `phpspec/phpspec` to version 6.2
- Updated `phpunit/phpunit` to version 8.5
- Updated `symfony/yaml` to version 4.4
- Refactored `Create` class to build validation parameters using `buildElement` method
- Updated `getValidationConstraints` method of `Create` class
- Updated `getPaymentInstrumentConstraints` method of `Create` class
- Updated `__construct` method of `Finalize` class
- Updated `getAttributes` method of `Finalize` class
- Updated `getValidationConstraints` method of `Finalize` class

## v2.1.0

### Added
- Added `isDeclined` method to `Payment` class
- Added `isApproved` and `isDeclined` methods to `Refund` class
- Added `isApproved` and `isDeclined` methods to `Settlement` class
- Added `isApproved` and `isDeclined` methods to `VoidPayment` class

## v2.0.0

### Changed
- Renamed `Cardinity\Method\Void` to `Cardinity\Method\VoidPayment`
- Renamed `Void.php` to `VoidPayment.php`

### Removed
- Removed `ResultObjectPropertyNotFound.php`
