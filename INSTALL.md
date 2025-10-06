# INSTALL

* This is for __new installation only__
* These instructions are for a manual installation using FTP, cPanel or other web hosting Control Panel.


If you are __upgrading your existing cart__, be sure to read the [upgrade instructions](UPGRADE.md) instead


## Linux Install

1. Upload all of the files and folders to your server from the "Upload" folder, place them in your web root. The web root is different on some servers, cPanel it should be ```public_html/``` and on Plesk it should be ```httpdocs/```.
2. Make sure your web user has the permission to read, write and execute all directories under the web root.
3. Rename config-dist.php to config.php and admin/config-dist.php to ```admin/config.php```
4. For Linux/Unix make sure the following folders and files are writable.

		chmod 0777 config.php
		chmod 0777 admin/config.php

5. Make sure you have installed a MySQL Database which has a user assigned to it
	* do not use your ```root``` username and ```root``` password
6. Visit the store homepage e.g. http://www.example.com or http://www.example.com/store/
7. You should be taken to the installer page. Follow the on screen instructions.
8. After successful install, delete the ```/install/``` directory from ftp.
9. If you have downloaded the compiled version with a folder called "vendor" - this should be uploaded above the webroot (so the same folder where the ```public_html``` or ```httpdocs``` is)

## Windows Install

1. Upload all the files and folders to your server from the "Upload" folder. This can be to anywhere of your choice. e.g. ```/wwwroot/store``` or ```/wwwroot```
2. Rename ```config-dist.php``` to ```config.php``` and ```admin/config-dist.php``` to ```admin/config.php```
3. For Windows make sure the following folders and files permissions allow Read and Write.

		config.php
		admin/config.php

4. Make sure you have installed a MySQL Database which has a user assigned to it
	* do not use your ```root``` username and ```root``` password
5. You should be taken to the installer page. Follow the on screen instructions.
6. After successful install, delete the ```/install/``` directory.

7. Make sure the following extensions are enabled in php.ini:

extension=curl;
extension=gd;
extension=zip;

## Local Install

There are many all-in-one web servers out there and most of them should work with OpenCart out of the box.

Some examples...

* https://www.apachefriends.org/
* http://www.ampps.com/
* http://www.usbwebserver.net
* http://www.wampserver.com/en/

## Notes

Godaddy Issues

If your hosting on godaddy you might need to rename the ```php.ini``` to ```user.ini```

It seems godaddy has started changing the industry standard names of files.

----------------------------

## Going live
When your site is ready to go live open file ```system/config/default.php``` 

**Find:**

`$_['error_display'] = true;`

**Replace with:**

`$_['error_display'] = false;`
