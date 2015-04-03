# OpenCart 2.0 change log

## v2.0.2.1 (Pending release)
#### Bugs fixed
* Mail config hostname variable (https://github.com/opencart/opencart/issues/2840)
* Order status id INT size in install.sql (https://github.com/opencart/opencart/issues/2820)
* Fix admin alert email for new customer.
* Product review fixed when error message is returned.
* Pagination fix in sale/recurring (https://github.com/opencart/opencart/pull/2853)

#### Changes
* Added pre & post triggers for customer log in
* Order modified success string changed (https://github.com/opencart/opencart/pull/2817)
* Changed the PayPal Express max amount calculation to be currency specific (https://github.com/opencart/opencart/issues/2836)
* Worldpay signup icon link changed to new URL.

#### Added


## v2.0.2.0 (Release date: 31.03.2015)
#### Bugs fixed
* Fix recurring profile copy button (instead of deleting them) (https://github.com/opencart/opencart/issues/2458, https://github.com/opencart/opencart/commit/be3811153da01d5db132e904adaceeb225449e6a)
* Owl carousel slider CSS fix (https://github.com/opencart/opencart/commit/870c406643328a6b50770eeb52a85b065812033e)
* Incorrect argument passed on customer group query (https://github.com/opencart/opencart/commit/f32d0dba4c3155be56489e2e9fab494d038352a4)
* PayPal logo saved for Express Checkout
* Price alignment fixed in product list (https://github.com/opencart/opencart/issues/2491)
* Incorrect successful registration contact us link incorrect (https://github.com/opencart/opencart/commit/6fb812139a5cf8298d3f61ce3674398b200efc90)
* Fedex language typos (https://github.com/opencart/opencart/issues/2532)
* Disable maintenance check for API requests (https://github.com/opencart/opencart/commit/99cc5a7e0aebbad291c1077569df857c259e4a4f)
* Summernote bold formatting (https://github.com/opencart/opencart/commit/f8aa94a0bb1d521750fdc7c5402c4ec949ad1dfa)
* OCMOD path fix when starting with a brace (https://github.com/opencart/opencart/issues/2561)
* getModule method not found bug, missing class load (https://github.com/danelsan/opencart/commit/2d239b12978f6574b984f5657ce300d6c7d82544)
* Added minimum order quantity to all product list pages (https://github.com/opencart/opencart/commit/8dc99c969af486f6a69b76e46ef7c1db91967b61)
* Cart not updated when adding product from a grid table or a related product (https://github.com/opencart/opencart/issues/2629)
* Post quantity value fixed when adding to cart (https://github.com/opencart/opencart/commit/35871c32a96cc51f963cffaa2596271cb6058532)
* Upgrade pages CSS fixed (https://github.com/opencart/opencart/commit/f4207b0b97f02026d151975d68133d08dcc375d3)
* Address book language string and menu positions in account area (https://github.com/opencart/opencart/issues/2574)
* Unable to use return key when searching products (https://github.com/opencart/opencart/commit/f3afa2638c3ff5abed66a5f340313b68c8bb970b)
* Ajax upload (https://github.com/opencart/opencart/issues/2610)
* Compare doeas not cross original price when special price available (https://github.com/opencart/opencart/issues/2555)
* Login attempts comparison (https://github.com/opencart/opencart/issues/2552)
* Affiliate Forgotten password (https://github.com/opencart/opencart/issues/2637)
* Skrill hash validation (https://github.com/opencart/opencart/commit/6e8db77668ddbe87f0100f4d614e62020a86dc94)
* Gift Vouchers, email sent to all even when only one is selected (https://github.com/opencart/opencart/issues/2581)
* Second level menu collapse (https://github.com/opencart/opencart/commit/7358c4b6b82280d18346c3581137887f029812be)
* Multiple pop over elements shown (https://github.com/opencart/opencart/issues/2544)
* DIR_UPLOAD missing from CLI installations (https://github.com/opencart/opencart/issues/2642)
* grouped product purchased with product id and not model (https://github.com/opencart/opencart/commit/3306753cee84646de743242d05cd520485d71cda)
* Sales Report Product Count (https://github.com/opencart/opencart/issues/2556)
* Customer report model incorrect query (https://github.com/opencart/opencart/issues/2554)
* Postcode fix for customer form in admin (https://github.com/opencart/opencart/commit/9a4e9a5b12c6c9d0990ed3b6001b515f4d06a491)
* Recent activity links not correct when a customer is waiting approval (https://github.com/opencart/opencart/issues/2658)
* Reports product viewed. wrong method used, corrected method was deleted (https://github.com/opencart/opencart/issues/2656)
* Remove tooltip fix (https://github.com/opencart/opencart/issues/2595)
* $totals variable in foreach loop corrected (https://github.com/opencart/opencart/commit/42cb638aa1a36bdccd429d2d72286f13ddb58960)
* Admin>Modules>Featured-Home Page : gives error if no products are added (https://github.com/opencart/opencart/issues/2660)
* $data['tax_class_id'] integer casting (https://github.com/opencart/opencart/commit/51028a8ddc708609850e8a1bdbb6f5ee68ba311a)
* Typo in filling addresses after login (https://github.com/opencart/opencart/commit/906732af9a018cc6f4f33e92aeb96dc1b51cb068)
* store product_reward entries only when points > 0 (https://github.com/opencart/opencart/commit/053b4be1e5d091d3036e0ba88f71f79fb3292cec)
* Fix default value of error_address (https://github.com/opencart/opencart/commit/818bf5180dcec97b53cb666f74c94631d7804d0a)
* .htaccess "download" wrong rule (https://github.com/opencart/opencart/issues/2684)
* product_recurrings and recurring should be product_recurring (https://github.com/opencart/opencart/commit/a18ebcd0e1b4ba790060d6ad9f792dd921e6b4a5)
* Admin review notification failing (https://github.com/opencart/opencart/issues/2708)
* Path Replacement (https://github.com/opencart/opencart/issues/2696)
* Added missing default admin permissions (https://github.com/opencart/opencart/issues/2685)
* Fixed a bug when product layouts were used in left and right columns. (https://github.com/opencart/opencart/commit/f1ebe9d0e5761502b3759c571ae63dee17d9ccdb)
* Fix event bug in manufacturer.php (https://github.com/opencart/opencart/commit/364954cc0ab1062b52ca2c8b53763fffa1a3efd2)
* Review button (in product page) cleared to new line (https://github.com/opencart/opencart/commit/ae9ab90791b3dd632bb2d300c3670f878df74296)
* Removed Sagepay direct cron debug code (https://github.com/opencart/opencart/commit/8a0fb9e9d86a16a3b896578b7f70d0a32bb30b10)
* Sagepay callback method when PayPal is chosen as payment type (https://github.com/opencart/opencart/pull/2761/commits)
* Recurring orders array fix in cart (https://github.com/opencart/opencart/pull/2760)
* IE copy product fix (https://github.com/opencart/opencart/commit/ece1c00ce60398791a6acc99cb282cb5817a21e6)
* Remove config_ignore_token (https://github.com/opencart/opencart/commit/9d295d302d84d6002e2582e49d4b6014c922bcb0)
* fix cart quantity input width on mobile (https://github.com/opencart/opencart/commit/c5c28c2506b556ed4514b4e8ade30201401078f6)
* removed double semi-colons (https://github.com/opencart/opencart/commit/ceb1102757ed3ef616c25b71b5c0f14252cd9f9f)

#### Changes
* "See All" text changed to "Show All"
* Store setting keys changed in config table (https://github.com/opencart/opencart/commit/9add29b838e6dba9bee4b584ba09fb876d22c1a4)
* Change to language check (https://github.com/opencart/opencart/commit/1b077a251beef73627a165716228ea8548da0bd7)
* Database name added in backup (https://github.com/opencart/opencart/commit/d95f97778dad81c416b6632d08f8599770f2f3f8)
* Bootstrap updated to latest version (https://github.com/opencart/opencart/commit/0977bf3b184aca19ff8ef7e50699fe7ecbb88eec)
* Royal Mail shipping module
* Google reCAPTCHA added to replace old captcha text system
* Added more zip and rar mime types for allowed upload file types (https://github.com/opencart/opencart/commit/cafc89ef3fc43c40ac33d14debdab663f76a4dd3)
* Removed unrequired check on PayPal standard order status (https://github.com/opencart/opencart/commit/ab30b979432fdf059fcbab502ff8f81991238d05)
* Maintenance mode should be triggered when important modifications are missing (https://github.com/opencart/opencart/issues/2647)
* Removed redundant product_attribute queries (https://github.com/opencart/opencart/commit/e8f4700b171d2f31fc04c38d1ce463194f521f17)
* Add HTTPS support during installation (https://github.com/opencart/opencart/commit/4a4b3ac92e9422c37f3d92f26da812e7180d07d9)
* Admin Tweak - Reverse Default Order of Reviews (https://github.com/opencart/opencart/commit/2387c782609877a4cc03af4042069ca6a51f9cb8)
* Lots of whitespace and CRLF>LF fixes applied.
* Mailing class changes
* Sort modules by name (https://github.com/opencart/opencart/commit/5b8d0d87a2dcb830d518be42082aaac948852128)
* Added exit() function to DB connection on failure (https://github.com/opencart/opencart/commit/c1a653236e901807a9a1111aeb5b052b932ce1d4)
* Added check if title is set in HTML module (https://github.com/opencart/opencart/commit/3e34003ab45da276baea17c773831abdc164e57f)

#### Added
* Latest OpenBay Pro release.
* Global payments gateway
* Amazon Login and Pay module
* PayPal merchant onboarding/signup added to Express Checkout

## v2.0.1.1 (Release date: 06.12.2014)

## v2.0.1.0 (Release date: 30.11.2014)
#### Bugs fixed
* Changed namespace issues in DB driver files.
* Added missing text_edit string in the settings area.
* Fixed scaling issue for admin progress status bars.
* Fixed image_width and image_height modules #1916
* Sale total dashboard fix (https://github.com/opencart/opencart/commit/6b44f708f8619081e91c3eedf59ad266f279c985)
* modification refresh does not clear files #1919
* Best seller module can now be enabled #1926 (https://github.com/opencart/opencart/commit/7ca7ab4a7bb3b35edbfc40eef12582c9602b1f90)
* Summernote fix for marketing #1935 (https://github.com/opencart/opencart/commit/41ba7adfd1a9572dc0143c3425a1cad1a677650d)
* Pagination closing tag fix #1933 (https://github.com/opencart/opencart/commit/fe4bb21f280087852ad77061d9f5d3b76e6d3bca)
* FTP settings variable correction #1930 (https://github.com/opencart/opencart/commit/17e450971396ecfa2e93eb513b79c7f0fdb6c38b)
* Fixed Secure Trading image in gateway list.
* Fixed missing review_guest check in product.tpl
* Added formatting to number format in pp express to ensure no comma is added for items over 1000 #2216
* Fixed issue that allows for unapproved logins with Log in With PayPal
* Changed missing image link in catalog recurring list template. Changed to button.

#### Changes
* Changed DB connection error response during install when using mysqli to use native message from mysqli
* Removed unused text in stats menu in the admin
* No module validation added (https://github.com/opencart/opencart/commit/d8fb60b2958223a69b042530220962d725cdf6a7)
* Typo fix #1929
* Module name strip_tags #1925
* FTP status check added (https://github.com/opencart/opencart/commit/0b7fcfc7d652dc0e67b3ef3109f1736c05842df1)
* Added check for compression size #1772
* Update moment.js #1896
* Updated Authorize.net affiliate link to correct OpenCart promotion page for users to get special offer.
* Added check for current category before getting child data to improve speed.
* Added DB indexes to OC URL table. REF: #2120

#### Added
* Check for URL alias #1915 (https://github.com/opencart/opencart/commit/69d6252f4157faf207e3db0504de20b34eab58ef)
* Latest OpenBay Pro release.
