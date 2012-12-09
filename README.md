opencart
========

A free shopping cart system. OpenCart is an open source PHP-based online e-commerce solution.
This version was forked from Daniel's opencart/opencart repo. All private validation methods
were changed to protected validation methods in the controller files. This makes it a lot easier
for 3rd party addons to extend controllers and then introduce their own additional variables
which may have to be validated, too.

We also recommend using the Override Engine 
(http://www.opencart.com/index.php?route=extension/extension/info&extension_id=8588)
for the creation of OpenCart addons when they have to extend controller or model files.
