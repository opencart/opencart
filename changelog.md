# OpenCart change log

## v1.5.6.4 (23rd April 2014)
#### Bugs fixed
* Fixed issue with images containing the <? (used in xml etc) string in the source and not uploading. Now only checks long php tag [**view**](https://github.com/opencart/opencart/commit/b04cbbcc40308c47e2d77460358b6450e9ee0e5b)
* Fixed language string for "error_file_type" in admin filemanger/upload method [**view**](https://github.com/opencart/opencart/commit/b04cbbcc40308c47e2d77460358b6450e9ee0e5b)
* Fixed upgrade issues caused by PHP consider 0 equal to null [**view**](https://github.com/opencart/opencart/commit/d6d5473e4d69e9f9f0679e3445899a3bc37a86f1)
* Updated SQL query in getTotalEmailsByProductsOrdered and getEmailsByProductsOrdered to use the limits in the correct method [**view**](https://github.com/opencart/opencart/commit/c5feafce559c81a44ab11315365750ed9b4a0cb1)

#### Changes
* Updated SQL mode in install SQL file and MySQLi adapter to accommodate servers that have strict mode enabled by default [**view**](https://github.com/opencart/opencart/commit/70298dd3c21430935659745436fe1f8709483718)
* Removed Amazon payments xls file from system folder as it can be downloaded online and has no place in the code framework [**view**](https://github.com/opencart/opencart/commit/70298dd3c21430935659745436fe1f8709483718)
* Improved currency update method to include curl timeouts, to handle occasional timeout issues [**view**](https://github.com/opencart/opencart/commit/bda066fdbde486107337ca1069bcba0dabecc67f)

#### Added
* Nothing

## v1.5.6.3 (15th April 2014)
#### Bugs fixed
* Image manager issue where the thumbnails failed to load [**view**](https://github.com/opencart/opencart/commit/b84978b5ca6683d4c62951256ef25f68a685ce92)
* PayPal Express totals calculation, fixed incorrect variable names [**view**](https://github.com/opencart/opencart/commit/78c4e9ac0f8fe51c69a1ddc1fb443dae2a5934f7)
* Fixed report for coupons to search the history table instead for start/end filters [**view**](https://github.com/opencart/opencart/commit/580ed0482f804a37e13fcab5363a38a76ce4feb9)

#### Changes
* Allow arrays to be written to the log file [**view**](https://github.com/opencart/opencart/commit/1e430128cd055498666fd5a2be2267b933a4b2c8)
* Check uploaded files for php content, reduce the risk of image files containing php and running on insecure servers [**view**](https://github.com/opencart/opencart/commit/c11ef46681b2c2d87b6c3fd7b1394f53b6b72e45)

#### Added
* Nothing

## v1.5.6.2 (10th April 2014)
#### Bugs fixed
- **PayPal Express Checkout** Added error handling if no shipping options for weight based shipping are found [**view**](https://github.com/opencart/opencart/commit/201004c7dcbec43d17477a099fc8522f56537c00)
- **PayPal Express Checkout** Fixed message when cart totals do not match order amounts [**view**](https://github.com/opencart/opencart/commit/1bf9db4306223760ba00a1a6bd8524cb1f96128b)
- **PayPal Express Checkout** Fixed state issue, changed variable from PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE to PAYMENTREQUEST_0_SHIPTOSTATE [**view**](https://github.com/opencart/opencart/commit/ff2705e21aa3062db53a4a39e5651d231af20e9c)
- **PayPal Express Checkout** Added fix for to allow for download only items and PayPal guest checkout. Guest checkout with no shipping option will not return any address data for the checkout so exceptions for no payment address data have been handled [**view**](https://github.com/opencart/opencart/commit/0a94a4073743cced97e0944d702425c7c57cf866)
- Fix for from and mail-to mail headers [**view**](https://github.com/opencart/opencart/commit/03bc37b4303bc3a9e9b6d9d34d604f0126aa61d1)

#### Changes
* **Klarna Account** gateway minimum amount update [**view 1**](https://github.com/opencart/opencart/commit/0c579e27168db635e43a0d9d2562c4bb5b26f464) [**view 2**](https://github.com/opencart/opencart/commit/d4ee47aedd456828c656075da6aecc345365d4ff)
* **PayPal Express Checkout** wrapped logging calls with if statements to check if debug is enabled in the settings [**view**](https://github.com/opencart/opencart/commit/765ea85956ca1d50aea5c8108c7c39a6dd4ad765)
* **PayPal Express Checkout** Updated SQL check for zone information to include code and title, covers differences between data response for different country formats [**view**](https://github.com/opencart/opencart/commit/395d4cc04c26b12dfa9a427142a9890876ceecf7)
* Cookies changed to HttpOnly [**view**](https://github.com/opencart/opencart/commit/53c376abb238a5d0bb14aa5e1a39f0601a4c6b9f)
* Changed UTF8 helper functions to new code [**view**](https://github.com/opencart/opencart/commit/d55aa27958895ed4f3141d4cffc94c7589aae48c)
* Updated OpenBay Pro to latest v2334 [**view**](https://github.com/opencart/opencart/pull/1327)

#### Added
* New checks during install to check for iconv function or mbstring extension as used by UTF8 helper [**view**](https://github.com/opencart/opencart/commit/8f4a58899e5ca0316b3e3be49a1171ccf3b0db26)