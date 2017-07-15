# OpenCart change log

## [Unreleased]

## [v3.0.1.2] (Release date: 07.07.2017)

## [v3.0.1.1] (Release date: 04.07.2017)

## [v3.0.0.0] (Release date: 19.06.2017)

## [v2.3.0.2] (Release date: 01.08.2016)

## [v2.3.0.1] (Release date: 31.07.2016)

## [v2.3.0.0] (Release date: 30.07.2016)

## [v2.2.0.0] (Release date: 02.03.2016)

## [v2.1.0.2] (Release date: 30.12.2015)
#### Bugs fixed
* XSS issue resolved for zone_id. Thanks to www.netsparker.com for finding.

## [v2.1.0.1] (Release date: 06.10.2015)
#### Bugs fixed
* Use the ip address from data in Fraudlanbs module and update language strings

#### Changes
* Allow download of the error log

## [v2.1.0.0] (Developer Release date: 28.09.2015)
#### Bugs fixed
* Change post to session in order API (https://github.com/opencart/opencart/issues/2712)
* Maxmind error with unused method (https://github.com/opencart/opencart/issues/3069)
* Added missing $json var declarartion in sale/order API (https://github.com/opencart/opencart/commit/a2d529e9929d80f2b4dad09648476c2f75382d06)
* Customers orders report wrong information #3101 (https://github.com/opencart/opencart/commit/e30de743e09537ffdb03455f8a0768dc6342d89f)
* Replaced wrong model (affiliate/affiliate) with right one (account/customer) (https://github.com/opencart/opencart/commit/9e5aa27e04df686a7d687908fe4a0f0200d453b7)
* Fixed call to undefined methods and calling method on null object (https://github.com/opencart/opencart/commit/f9105bf21b9b3d77cfde69578a46f0e39e6bfdfc)
* Bootstrap info button showing blue border #3126 (https://github.com/opencart/opencart/issues/3126)
* Fixed missing DB_PORT when creating new DB object (https://github.com/opencart/opencart/commit/c990cec427f367dd54eed0c2a149e679c01c064d)
* Warning ini_set() A session is active (https://github.com/opencart/opencart/issues/3131)
* Password Decode Failure, Step 3 Installation (https://github.com/opencart/opencart/issues/3185)
* Notification Menu on Pending Orders #3180 (https://github.com/opencart/opencart/issues/3180)
* Encryption is not required for 'File' type option (https://github.com/opencart/opencart/commit/cdee8cbee943f4b0c1f91555829ed55a97afa595)
* updated the sql to drop tables if exist because the data still gets dumped in (https://github.com/opencart/opencart/commit/d0c5674bba7ff596b8cd0a75930cab0934348b27)
* Add "required" asterisk to locale field (https://github.com/opencart/opencart/commit/6d5149d6c52857820be259a8fc533d6c76cd461e)
* Fix worldpay validate modify permission. (https://github.com/opencart/opencart/commit/c70ae43253fee57a1d5ac26586d8552ab23d9625)
* library-image->watermark (https://github.com/opencart/opencart/issues/3119)
* Missing meta data in add language #3184 (https://github.com/opencart/opencart/issues/3184)
* Placeholder display issue in product edit -> special -> priority field #3157 (https://github.com/opencart/opencart/issues/3157)
* Remove forward slash from download #3246 (https://github.com/opencart/opencart/issues/3246)
* Print shipping list - wrong weight (https://github.com/opencart/opencart/issues/3155)
* reward points not added to returning customer (https://github.com/opencart/opencart/issues/3161)
* Wrong order status if clause #2722 (https://github.com/opencart/opencart/issues/2722)
* Gift voucher mails not sent automatically!! #3093 (https://github.com/opencart/opencart/issues/3093)
* Can't void order if in banip or fraud #2720 (https://github.com/opencart/opencart/issues/2720)
* Fixed broken layout when adding a new discount (https://github.com/opencart/opencart/commit/5763695e5b7faa00b29d01673a17bc59eb57f3d3)
* Fixed broken layout when adding a new image to a banner (https://github.com/opencart/opencart/commit/8035fc8038e9c49c16d6adae07a128da9ddeb167)
* Category canonical & breadcrumbs are incorrect - Generate from found category_id and not uri path #3179 (https://github.com/opencart/opencart/issues/3179)
* Document selection "lost" in filemanager when a filter is applied #3253 (https://github.com/opencart/opencart/issues/3253)
* Forgottern password check account has been approved #3266 (https://github.com/opencart/opencart/issues/3266)
* Wrong redirect on shipping and review form #3276 (https://github.com/opencart/opencart/issues/3276)
* Add an attribute without group #3274 (https://github.com/opencart/opencart/issues/3274)
* G2A - form bug fix (https://github.com/opencart/opencart/commit/e8b2fc5faea23afff44b1d2dace53715fd8ab8da)
* Added missing error_filetype text. (https://github.com/opencart/opencart/commit/65a18352c3ba39150bd8aa5d25f06c1aa0687110)
* Remove </form> #3305 (https://github.com/opencart/opencart/issues/3305)
* G2A Pay Module Not Working (vat totals) #3290 (https://github.com/opencart/opencart/issues/3290)
* Warning in order return page #3328 (https://github.com/opencart/opencart/issues/3328)
* Convert Limit Value To Integer (https://github.com/opencart/opencart/pull/3330)
* Manufacturer empty canonical #3333 (https://github.com/opencart/opencart/issues/3333)
* remove duplicate lines #3337 (https://github.com/opencart/opencart/pull/3337)
* Fix bluepay_redirect.php when releasing funds in the admin. (https://github.com/opencart/opencart/commit/169fc38c2d47a421943863331adba32034ae0586)
* Fixed usages of btn_ language tag to the correct button_ (https://github.com/opencart/opencart/commit/1ef7ba8745a3f2ac5f8059648f5a17f8183f22bc)
* Fixed calculated totals for Bluepay module. (https://github.com/opencart/opencart/commit/56361d47bfc85eb233eba16ffee3c39c8d659a65)
* HTTP_IMAGE is removed, starting from 1.5.?.? version, but still exists in cli_install.php file #3346 (https://github.com/opencart/opencart/issues/3346)
* Missing setTitle #3348 (https://github.com/opencart/opencart/issues/3348)
* Fix dspan to div #3350 (https://github.com/opencart/opencart/pull/3350)
* Bug fix: if $mail->reply_to is set, use that (instead of $mail->from) in the email headers #3023 (https://github.com/opencart/opencart/pull/3023)
* Remove PayPal Pro "Please Wait..." message after completion #3156 (https://github.com/opencart/opencart/pull/3156)
* Coupon per category does not work #3354 (https://github.com/opencart/opencart/issues/3354)
* stylesheet form-group fix (https://github.com/opencart/opencart/commit/86b16c0074db9d4a075b92efbab87e703eb848cd)
* Version 2.0.3.1 Error ----PP_EXPRESS Error #3377 (https://github.com/opencart/opencart/issues/3377)

#### Changes
* Changes to the order API system (https://github.com/opencart/opencart/tree/master/upload/catalog/controller/api)
* Pondicherry to Puducherry in oc_zone table
* Amazon checkout payment module removed. Replaced with newer Login and Pay module.
* Added data to the pre.order.history.add event trigger (https://github.com/opencart/opencart/commit/77830eec0dcfcecd99bc38150b37083c646396f6)
* Korea, Republic of > South Korea in country list (https://github.com/opencart/opencart/commit/dae5e0d42869ce1765661597d6677e4afcd3afcb)
* Modification to pre.order.history.add event trigger (https://github.com/opencart/opencart/commit/61aa9d7961cab8527f11ac293469f8e132c8d07c)
* Moved the country ajax to one central location (https://github.com/opencart/opencart/commit/652167f7447eb3004be4e579a5f0073af668991b)
* Removed testing code (https://github.com/opencart/opencart/commit/bcfd7d9541f7ecebb5e3de851a11b955c5df1d7f)
* Replaced old country method with new location (https://github.com/opencart/opencart/commit/a011d9ce6284d57966b4dcdefadeec4674e3af6c)
* $this->load->language > $this->language->load (https://github.com/opencart/opencart/commit/9f3ab03c355a6f06e4cfbb38e8f5bf1385a8170f)
* Added PHPUnit bootstrap.php file to .gitignore (https://github.com/opencart/opencart/commit/47e0b99f1d377680254d63b6f1c88a49f6ec1307)
* No need to tell the customer they already added the same product to the wish list (https://github.com/opencart/opencart/commit/0761b30bcd20513412c26eeccbab58867516541e)
* Removed restore cart and wishlist from customer class as you don't want your cart restored on each login (https://github.com/opencart/opencart/commit/0db2555371228c887a83bd271b4542cacc22603f)
* Better login token (https://github.com/opencart/opencart/commit/b79cdf365dc86491479d3568c86a3724739113da)
* API login moved also added a proper encryption key (https://github.com/opencart/opencart/commit/c1da341df8ad9f9ebac5f0d834e4f9d73eeeec8d)
* Prevent html content inside modules dropdown (https://github.com/opencart/opencart/commit/16825ac6af4ad580b92f1db4093bd31cd62be098)
* added a token generator. md5(mt_rand()) is not good enough (https://github.com/opencart/opencart/commit/564b4d07bbe501d1de2101d5c8bb054972c6745e)
* use mt_rand() instead of rand() (https://github.com/opencart/opencart/commit/16010252a27529a24d35f71477d6cf2a4f062ce0)
* changed the method name ijn payment gateways from action to order (https://github.com/opencart/opencart/commit/e9ea2833ac64709c232fac95cc68931fec33a447)
* affiliate code changes (https://github.com/opencart/opencart/commit/a0ff7c6a072751358f67289f387632ce39811a26)
* upload/admin/controller/sale/customer.php (https://github.com/opencart/opencart/commit/0a42c054e462854d6bf74088a329097c4d9c41f1)
* updated git ignore with storage folder changes (https://github.com/opencart/opencart/commit/5a702e15e1270277ada35f7a64be7bf6e348eea2)
* moved all the customer files to their own folder (https://github.com/opencart/opencart/commit/2bcf39e02a0e5aab513c1a90d4ed78b00984837c)
* Updated cli_install.php to include changes for storage folder and also db_port option (https://github.com/opencart/opencart/commit/21c76f5022e69efe99f267df7afb1a989e707917)
* changed unserialize to json_decode for the settings table (https://github.com/opencart/opencart/commit/141de8ac42ee10851d9d8736462e5e1418569be5)
* updated the salt to use token (https://github.com/opencart/opencart/commit/c775c554443da3c21d9393458db3ca1d3966d0c1)
* Changes session library class
* moved the general fields and store fields around as they are suited better under the different tabs (https://github.com/opencart/opencart/commit/3a254ea5053d00b96acd4fcab9827b2ccff6f23c)
* Update usps.php > OriginZip (https://github.com/opencart/opencart/commit/919a074368e9f781878cc26b4fd6f6076fca95e0)
* Update to Google recaptcha
* remove redundant conditions for product left & right sections (https://github.com/opencart/opencart/commit/cb69dc56895368b16b8ad92c83c8c95b85972177)
* add russian ruble to pp_standard (https://github.com/opencart/opencart/commit/a90e15c5b9b95de1588a97f99d628052e871bf60)
* added latest css bootstrap and font icons (https://github.com/opencart/opencart/commit/8031629ffd7345227130629902c232816a87044e)
* Updated PayPal Pro iFrame endpoint to new PayPal requirement. (https://github.com/opencart/opencart/commit/116ee0ad943d0f4443b73174f26583cd9b854705)
* image manager code rearrange (https://github.com/opencart/opencart/commit/2dea4545b0321e212afee558b7755d6ddb505d96)
* Google maps geocode language (https://github.com/opencart/opencart/commit/664c39c888c626a4b45726660779a1fb217a3369)
* Remove unused bootstrap files (https://github.com/opencart/opencart/commit/0d4fc2da477820e6981fc979e7e35cd398afd7f7)
* FedEx shipping module improvements #3019 (https://github.com/opencart/opencart/issues/3019)
* Amazon Login & Pay module update & fixes
* updated admin bootstrap and added the sass files for theme developers. (https://github.com/opencart/opencart/commit/67a69c8ff5d1da1d5e794fde5527144cd3dc3279)
* Removed "final" declaration from Action class to make it mockable in PHPUnit (https://github.com/opencart/opencart/commit/0bff4ddbc051034a87a940d79d7f1282d233838e)
* Change SEO Keyword Text to SEO URL (https://github.com/opencart/opencart/commit/06622c6c8937eef329368ff9851459cc0104dc9b)
* google_base.php missing "google_product_category" #3228 (https://github.com/opencart/opencart/issues/3228)
* Updated Sagepay logo to new branding. (https://github.com/opencart/opencart/commit/c3f2b2508aaff8da9353136e92a544cf1f15cf53)
* Fixed Bluepay transaction logging to use the correct standard for a payment type (payment not sale) (https://github.com/opencart/opencart/commit/ac24c81bbf1ec56183faa18e2396986f22d1a2d3)
* Improved UX for slow payment gateways/shipping methods as loading button resets when checkout step is properly loaded (https://github.com/opencart/opencart/commit/2b475e1d99bb7d1eb0fd109f17114ddde233382b)
* Ability to login at checkout step using enter key on the inputs (https://github.com/opencart/opencart/commit/7a6dd6d145995cf713d106b6ad08d8e408ac13b6)
* Adding config_login_attempts = 5 to install sql (https://github.com/opencart/opencart/commit/2431f8d67442b91d12c8dd812d92e17e52a615d5)

#### Added
* Latest OpenBay Pro release.
* add event *.admin.language.* (https://github.com/opencart/opencart/commit/6bdc37dd654f109a239b6744a0012f371b2fd590)
* added api IP table (https://github.com/opencart/opencart/commit/6933ac7c50a9bc3be2ca1f6328cc0c9566450d74)
* Friendly error when mPDO error occurs on installation of Opencart (https://github.com/opencart/opencart/commit/06f28dca66fb55f33a2c1753cfdec1e345348e4f)
* Added Croatia to the list of EU countries (https://github.com/opencart/opencart/commit/9e5d13e3b241fa7f4bdc94fd3facdab85eb5c635)
* Added Telangana as a state (https://github.com/opencart/opencart/commit/6ca2cdd46a04ba5468c63e1d61ecc1e47a471562)
* Moved the fraud handling into separate module type
* Moved analyitics into separate module type
* Added priority to event listener. Higher priority means more importance. (https://github.com/opencart/opencart/commit/e69ec5a94622fe3b3e9964e4a4ea650cffeb9d33)

## [v2.0.3.1] (Release date: 29.05.2015)
#### Bugs fixed
* Safari text colour change on carousel (https://github.com/opencart/opencart/commit/2adcf21fbdf2c2d0e08860daf4012e14e24ff664)
* Missing language strings during install for DB port (https://github.com/opencart/opencart/commit/c9f014f3a0441b05f4b2d9fad074a5b883695102)
* Missing permission for admin to fraud module area (https://github.com/opencart/opencart/commit/ea1ef5dd7716637defea742d1e78bab40471caea)
* Custom field correct sort order (https://github.com/opencart/opencart/commit/6e95c487cbb109ce7e9925d8d9a8073ea244c9a4)
* Fixed edit currency feature in add/edit order area (https://github.com/opencart/opencart/commit/3826c8df1bfef24938eb68cce7fb9d76779ef0bd)
* Missing totals $data key for sale/order (https://github.com/opencart/opencart/commit/783e089d54e091faa3abe1846afa483f0f7d3311)
* Insecure links in payment gateways (https://github.com/opencart/opencart/commit/9c8645156d62319b219e6e4e93c0e73991e87ac0)
* For element on information form (https://github.com/opencart/opencart/commit/6c126c8b9688bd3c43004325266d72f05857e472)
* Redirect link in authorizenet sim (https://github.com/opencart/opencart/commit/59336a1bc6dc95bd52404379b5fca16d0949bfbf)
* PayPal not found template ref (https://github.com/opencart/opencart/commit/455ee256b3ec0c2f08fc2907223560fa6733dce7)
* Google hangouts admin form (https://github.com/opencart/opencart/commit/19e8c66fca65a424e646885792bcbf8eabba7b37)
* Google base template layout (https://github.com/opencart/opencart/commit/cb6a7dab1b747a7200fa484a330c9a4783bf1a82)
* Updated readonly="readonly" to readonly on form elements (https://github.com/opencart/opencart/commit/f5e85dc3a8cc45c4cbef9ec42735bf74272e15a8)
* Google sitemap double amp encoding (https://github.com/opencart/opencart/commit/06fe3e5f3b3ff4f72ed74811fb6c32cea5a5776d)

## [v2.0.3.0] (Developer Release date: 20.05.2015)
#### Bugs fixed
* Mail config hostname variable (https://github.com/opencart/opencart/issues/2840)
* Order status id INT size in install.sql (https://github.com/opencart/opencart/issues/2820)
* Fix admin alert email for new customer. (https://github.com/opencart/opencart/issues/2847)
* Product review fixed when error message is returned.
* Pagination fix in sale/recurring (https://github.com/opencart/opencart/pull/2853)
* Product review fixed when error message is returned. Also added check to only attempt recapcha call if no other errors are set first which stops invalidating the saved token. (#2843 #2839)
* Fixed pagination issue on sale/recurring (https://github.com/opencart/opencart/pull/2853)
* Amazon - minimum order total bug (https://github.com/opencart/opencart/commit/6e1de669edcb4c16cad024c4ac1e71d16d6772fc)
* BUG when sending the e-mail to admin (https://github.com/opencart/opencart/commit/7974e4674258bfe3688c7b54c5c984ddc98d6cf9)
* Update modification.php - ensure modification mode is in correct state (https://github.com/opencart/opencart/pull/2895)
* Gift Certificate, Back end bug Fix (https://github.com/opencart/opencart/commit/6e6e262d4e3ae2ac24ea0dc758b6a9924bd098b5)
* Fixed Handling fee typoe on operator (https://github.com/opencart/opencart/commit/68dba1bbbc9f2fbeccbeac57ee7753b6e527a991)
* BUG when sending the e-mail to admin (https://github.com/opencart/opencart/pull/2888)
* Update cart.php - enforce minimum order qty of the product (https://github.com/opencart/opencart/commit/f1cefe1caf1a748b3c0a66877cb493642e220a22)
* Update bestseller.php - fix link (https://github.com/opencart/opencart/commit/c1e58f86b7625b9692f2b90048f0cde0fddfa8c6)
* Update coupon.php - remove current coupon if blank one is entered (https://github.com/opencart/opencart/commit/460a9c49b2c3834c30165eb28db60a7ce8b4622f)
* Added missing jQuery link to order invoice and order shipping. (https://github.com/opencart/opencart/pull/2949)
* ocMod regex operations are never trimmed (https://github.com/opencart/opencart/issues/2925)
* Bug fixes First Data (https://github.com/opencart/opencart/commit/2accd5e0ab2d26a6f44193754b4a9104f77450be)
* Fixed security issue on log filename (https://github.com/opencart/opencart/commit/b7242c495a40d3534cf8f24b1ebc038912220961)
* Update product.php - Syntax fixed (https://github.com/opencart/opencart/commit/414519f49e1f093c8b94ff0815899cab66ea9c8f)
* Amazon module - reverting cookie name to correct value and adding comments to warn against altering (https://github.com/opencart/opencart/commit/832707f0adc5695e1feb4ec40762eb2fbd493ba4)
* Fix when serialized settings are changed to/from serialized state (https://github.com/opencart/opencart/issues/3015)
* Full path disclosure due to Undefined index (https://github.com/opencart/opencart/pull/2998)

#### Changes
* Added pre & post triggers for customer log in
* Order modified success string changed (https://github.com/opencart/opencart/pull/2817)
* Changed the PayPal Express max amount calculation to be currency specific (https://github.com/opencart/opencart/issues/2836)
* Worldpay signup icon link changed to new URL.
* Order modified success string changed. (https://github.com/opencart/opencart/commit/faf463da8484d59cf7c2831aeabad106bbb08e15)
* Removed unused empty function (https://github.com/opencart/opencart/pull/2634 https://github.com/opencart/opencart/pull/2634)
* Changed the PayPal Express max amount calculation to be currency specific (https://github.com/opencart/opencart/commit/29f6c323d3d2e48e9929823b0f5b234285191208)
* Removing redundant uninstall code, deleting tables that were never created (https://github.com/opencart/opencart/commit/6b3e3a6c6d6a73b1544f923229199ce29b9d21aa)
* Remove unneeded array_merge calls (https://github.com/opencart/opencart/commit/a7d404250107cc63f77b1b30c9884a01d179d975)
* Startup file - ensure it's a file, include_once (https://github.com/opencart/opencart/commit/4f090a119c132284e28b12bad828c4f06650f7ac)
* Code standards (indents & whitespace)
* Mail system changes (Commits on Apr 6, 2015)
* Delete MoneyBrokers Icon, Replaces by Skrill Icon (https://github.com/opencart/opencart/commit/fbb33f70ef3fc31c52b484915e744e8652a76f97)
* Position correction of custom fields in admin panel (https://github.com/opencart/opencart/pull/2953)
* Zone name corrected (https://github.com/opencart/opencart/pull/2954)
* Lebanon regions added (https://github.com/opencart/opencart/commit/fa0c7a633a392f0eacf70356da92851f207d3ebf)
* Displays errors custom_field field (https://github.com/opencart/opencart/pull/2969)
* Allow config of custom database port during setup (https://github.com/opencart/opencart/issues/2967)
* Language change (https://github.com/opencart/opencart/commit/f56b30fb2f2dc5a799edc3b724bf3e840cd8af44)
* Update installer.php - force array type on glob() result (https://github.com/opencart/opencart/commit/f1e7bbf9e7d595989e218e95a252bd9d8ea1e5c8)
* Add check for modification folder write during install. (https://github.com/opencart/opencart/commit/9121f27e923a8a331688b8a8405fb4570f3eda33)
* Changed install language file name (default > english). (https://github.com/opencart/opencart/commit/d39c7110df16edd3c4faae1d095e05d5f01cd159)
* Return $language_id in language.php addLanguage method. (https://github.com/opencart/opencart/commit/e58cae10e1ca000b8207c7b745d19062b1fd3700)
* Language file standard, removed double quotes to single. (https://github.com/opencart/opencart/commit/24d791d482c3dea4a840de73b450e1a01360b2e2)
* Added loading text to form button. (https://github.com/opencart/opencart/commit/cea6ae647e3e513d36782654a9a5470bbde3bfa8)
* Preserve styling of wishlist top menu item (https://github.com/opencart/opencart/pull/2814)

#### Added
* Latest OpenBay Pro release.
* Added Jenkins automation install script for demo store (https://github.com/opencart/opencart/commit/710415264e5c4d530439b9a897cd115e8a582268)
* Fraud extension support (https://github.com/opencart/opencart/commit/f9085aa99f00e0895d813ed8aef92f8366a93d33)
* Fraud labs pro module added (https://github.com/opencart/opencart/commit/e62ae241f56ea2d3efb4f74ff3f840d2479ea28c)
* G2A Pay module (https://github.com/opencart/opencart/commit/a0df0385bdc5785128da5302433fd5a65675b3e4)

## [v2.0.2.0] (Release date: 31.03.2015)
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
* Compare does not cross original price when special price available (https://github.com/opencart/opencart/issues/2555)
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

## [v2.0.1.1] (Release date: 06.12.2014)

## [v2.0.1.0] (Release date: 30.11.2014)
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

## [v2.0.0.0] - (Release date: 01.10.2014)

[Unreleased]: https://github.com/opencart/opencart/compare/3.0.1.2...HEAD
[v3.0.1.2]: https://github.com/opencart/opencart/compare/3.0.1.1...3.0.1.2
[v3.0.1.1]: https://github.com/opencart/opencart/compare/3.0.0.0...3.0.1.1
[v3.0.0.0]: https://github.com/opencart/opencart/compare/2.3.0.2...3.0.0.0
[v2.3.0.2]: https://github.com/opencart/opencart/compare/2.3.0.1...2.3.0.2
[v2.3.0.1]: https://github.com/opencart/opencart/compare/2.3.0.0...2.3.0.1
[v2.3.0.0]: https://github.com/opencart/opencart/compare/2.2.0.0...2.3.0.0
[v2.2.0.0]: https://github.com/opencart/opencart/compare/2.1.0.2...2.2.0.0
[v2.1.0.2]: https://github.com/opencart/opencart/compare/2.1.0.1...2.1.0.2
[v2.1.0.1]: https://github.com/opencart/opencart/compare/2.1.0.0...2.1.0.1
[v2.1.0.0]: https://github.com/opencart/opencart/compare/2.0.3.1...2.1.0.0
[v2.0.3.1]: https://github.com/opencart/opencart/compare/2.0.3.0...2.0.3.1
[v2.0.3.0]: https://github.com/opencart/opencart/compare/2.0.2.0...2.0.3.0
[v2.0.2.0]: https://github.com/opencart/opencart/compare/2.0.1.1...2.0.2.0
[v2.0.1.1]: https://github.com/opencart/opencart/compare/2.0.1.0...2.0.1.1
[v2.0.1.0]: https://github.com/opencart/opencart/compare/2.0.0.0...2.0.1.0
[v2.0.0.0]: https://github.com/opencart/opencart/compare/2.0.0.0b3...2.0.0.0
