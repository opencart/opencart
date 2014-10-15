/////////////////////////////////
///       OpenCart V2.0.x     ///
///    Install Instructions   ///
///  http://www.opencart.com  ///
/////////////////////////////////


NOTE: THIS IS FOR NEW INSTALL ONLY!
IF UPGRADING YOUR EXISTING CART, BE SURE TO READ THE UPGRADE.TXT FILE INSTEAD


-------
INSTALL
-------
These instructions are for a manual installation using FTP, cPanel or other web hosting Control Panel.

- Linux Install -

1. Upload all of the files and folders to your server from the "Upload" folder, place them in your web root. The web root is different on some servers, cPanel it should be public_html/ and on Plesk it should be httpdocs/.

2. Rename config-dist.php to config.php and admin/config-dist.php to admin/config.php

3. For Linux/Unix make sure the following folders and files are writeable.

		chmod 0755 or 0777 system/cache/
		chmod 0755 or 0777 system/logs/
		chmod 0755 or 0777 system/download/
		chmod 0755 or 0777 system/upload/
		chmod 0755 or 0777 image/
		chmod 0755 or 0777 image/cache/
		chmod 0755 or 0777 image/catalog/
		chmod 0755 or 0777 config.php
		chmod 0755 or 0777 admin/config.php

		If 0755 does not work try 0777.

4. Make sure you have installed a MySQL Database which has a user assigned to it
	DO NOT USE YOUR ROOT USERNAME AND ROOT PASSWORD

5. Visit the store homepage e.g. http://www.example.com or http://www.example.com/store/

6. You should be taken to the installer page. Follow the on screen instructions.

7. After successful install, delete the /install/ directory from ftp.


- Windows Install -

1. Upload all the files and folders to your server from the "Upload" folder. This can be to anywhere of your choice. e.g. /wwwroot/store or /wwwroot

2. Rename config-dist.php to config.php and admin/config-dist.php to admin/config.php

3. For Windows make sure the following folders and files permissions allow Read and Write.

		system/cache/
		system/logs/
		system/download/
		system/upload/
		image/
		image/cache/
		image/catalog/
		config.php
		admin/config.php

4. Make sure you have installed a MySQL Database which has a user assigned to it
	DO NOT USE YOUR ROOT USERNAME AND ROOT PASSWORD

5. You should be taken to the installer page. Follow the on screen instructions.

6. After successful install, delete the /install/ directory.



- Local Install -

There are many all-in-one web servers out there and most of them should work with OpenCart out of the box. Some examples...

http://www.apachefriends.org/en/xampp.html
http://www.ampps.com/
http://www.usbwebserver.net
http://www.wampserver.com/en/