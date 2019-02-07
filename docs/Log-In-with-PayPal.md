> IMPORTANT: For store owners using OpenCart 2.0.0.0, this module has a small bug causing the Log in with PayPal button to produce an error. To fix, open catalog/controller/module/pp_login.php and change 'recurring' to 'profile' on line 37. Commit: https://github.com/opencart/opencart/commit/8a7aa15ead3a66ff246e90a10f71e9c2e2551df8. This is fixed on 2.0.0.1 onwards

### **Installation**

1. To install, log in to your OpenCart admin area and navigate to Extensions > Modules > Log In with PayPal. Click install (Plus icon).

### **Setup**

_PayPal_

1. Go to developer.paypal.com and log in using your PayPal account.
2. Click Dashboard and then click Create App. Fill in the App name, choose a Sandbox developer account to link to and click Create App. 
3. Scroll down to App Settings (you'll fill in the return URL shortly), tick Log In with PayPal and click Advanced Options for Log In with PayPal.
4. The following scopes have to be set for the module to work:

Personal Information
* Full name

Address information
* Email address
* Street address
* City
* State
* Country
* Zip code
* Phone

If you want to use Seamless Checkout, tick the Use Seamless Checkout box.

_OpenCart_

1. Navigate to Extensions > Modules > Log In with PayPal and click edit (Pencil icon)
2. Fill in the Client ID and Secret from the PayPal app you just created.
3. Fill out the rest of the form.
4. Copy the Return URL and paste this in to PayPal under app redirect URLs
5. Navigate to System > Design > Layouts and edit the layout where you want the button to appear.
6. Click the plus button under the Module table and choose Log In with PayPal. Click Save.

> Please note: Sandbox and Live use completely different Client ID and Secret credentials. Also, you have to set the return URL and scopes for both Sandbox and Live. Use the toggle in the top right corner of your PayPal app to flick between Live and Sandbox. Live credentials are only available for Premier or Business accounts.

**Common Problems**

When you edit your return URL in PayPal, sometimes this doesn't update straight away. Try again in a couple of hours and use a different browser.