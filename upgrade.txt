///////////////////////////////////////
///    OpenCart V1.5.x TO 3.0.x     ///
///      Upgrade Instructions       ///
///     http://www.opencart.com     ///
///////////////////////////////////////

NOTE: THIS IS FOR UPGRADE ON EXISTING INSTALLS ONLY!
IF INSTALLING NEW, BE SURE TO READ THE INSTALL.TXT FILE INSTEAD

##########################################
THIS IS FOR UPGRADING EXISTING OPENCART 1.5.x STORES ONLY
THIS IS FOR UPGRADING EXISTING OPENCART 1.5.x STORES ONLY
THIS IS FOR UPGRADING EXISTING OPENCART 1.5.x STORES ONLY
##########################################

##########################################
DO NOT USE THIS UPGRADE TO CONVERT FROM 1.4.x to 1.5.x!!
DO NOT USE THIS UPGRADE TO CONVERT FROM 1.4.x to 1.5.x!!
DO NOT USE THIS UPGRADE TO CONVERT FROM 1.4.x to 1.5.x!!
If you have 1.4.x and want to upgrade to 1.5.x, You need to use the script here:
   http://forum.opencart.com/viewtopic.php?f=2&t=50292 
##########################################


1. BACKUP YOUR EXISTING STORE FILES AND DATABASE!!
- Backup your database via your store Admin->System->Backup
- Backup your files using FTP file copy or use cPanel filemanager to create a zip of all the existing opencart files and folders
 
2. Download the latest version of OpenCart and upload ALL new files on top of your current install EXCEPT your config.php and admin/config.php. If you are up to date with the last recent version and want to find just the new files that have changed since the last version, you can follow this guide to create a smaller changes-only patch. But for most, the full opencart zip will suffice.
Watch this video to understand how to properly upload folder using FTP: http://forum.opencart.com/viewtopic.php?f=20&t=9645#p46371

3. Browse to http://<yourstore.com>/install Replacing <yourstore.com> with your actual site (and subdirectory if applicable).
 
4. You should see the OpenCart Upgrade script.
- If you see the OpenCart Install page, then that means you overwrote your config.php files. Restore them from your backup first. Then try again.
 
5. Click "Upgrade". After a few seconds you should see the upgrade success page.
- If you see any errors, report them immediately in the forum before continuing.
 
6. Clear any cookies in your browser
 
7. Goto the admin side of your store and press Ctrl+F5 3x times to refresh your browser cache. That will prevent oddly shifted elements due to stylesheet changes. Login to your admin as the main administrator.
 
8. Goto Admin->Users->User Groups and Edit the Top Adminstrator group. Check All boxes.
- This will ensure you have permissions for all the new files
 
9. Goto Admin->System Settings
- Update any blank fields and click save. 
Even if you do not see any new fields, click save anyway to update the database with any new field names.

10. Other Adjustments that may need to be made, depending on which version you are upgrading from and to. These are broken down by which version they were added in. So if you are not currently at that version, you may need to make changes

##########################################
OPTIONAL 
##########################################

v151
   - Modules must all be reinstalled to update the database with the new serialized data method or you will see errors on the front end
v151.3
   - System Settings have new "Use Store Tax Address" and "Use Customer Tax Address" fields that need to be set for taxes to work. By default they are updated to use "Shipping Address" which will be what 99% of stores will use anyway.
v152
   - New "Product Count" field added to the category module. You may need to edit that module to set the new field
v153
   - UPS module gets a change for the unit select and is disabled by default to force you to resave the settings.
   - New Voucher Min/Max fields added to Admin->System->Settings. Default is 1 to 1000
   - New Customer Group selection option for registration. You can choose which fields to show in the Admin->Sales->Customer Groups section.  By default it is set to "Default" only in the Admin->System->Settings under the Option tab. 
   - New "Category Product Count" setting in the Admin->System->Settings under the Server tab. This is disabled by default and should only be used for small stores as it causes massive performance loss for larger stores with lots of categories.
 
11. Load the store front and again press CTRL+F5 3x times to refresh your browser cache. That will prevent oddly shifted elements due to stylesheet.

12. If you use vQmod (by now everyone should be) you should first check for a new version at http://vQmod.com. Be sure to download the version that is marked for "opencart".
You will also need to re-run the vQmod installer, even if you have the latest version. You should already have the installer on your site as it isn't meant to be deleted from the first time you run it. Simply browse to:
http://yoursite.com/vqmod/install and you should see a success message. If you do not see a success message, follow the full install guide from the http://vQmod.com site.

Note that some of your vQmod scripts may need to be updated for the new core changes. 
So run through your site catalog and admin areas and check in FTP for the vqmod/vqmod.log file. 
If you see errors, then you will need to address them.


Troubleshooting:
------------------------------
1. If you have any upgrade script errors, post them in the forum
2. If you have 3rd party addon errors, contact the mod author for an update.
3. If you find bugs, check the "official" bug thread for this version of Opencart
Many bugs may have already been reported and fixes will be offered in the first post of this thread.
You should always visit this thread immediately after a fresh upgrade to see if there are any immediate bug fixes
If nobody has reported your bug, then please report it.

An online version of this file can be found here:
http://www.opencart.com/index.php?route=documentation/documentation&path=98


That's It!
OpenCart Dev Team