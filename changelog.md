# OpenCart change log

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