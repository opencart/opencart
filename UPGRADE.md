# Upgrading from version 2.x.x or 3.x.x to the latest 3.0.x.x

* For existing installs only
* Don't use with previous versions

## For previous versions

If you have __1.5.x__ and want to upgrade __to 3.0.x__, You may try to [contact our dedicated support](https://dedicated.opencart.com/)


## Step by step

1. BACKUP YOUR EXISTING STORE FILES AND DATABASE!!
    * Backup your __database__ via your store
    `Admin -> System -> Backup`
    * Backup your __files__ using FTP file copy or use cPanel filemanager to create a zip of all the existing opencart files and folders

2. Prepare your old site for the upgrade
    * Clear all caches
    * If upgrading from 2.x.x: Disable all 3rd party extensions, you'll have to replace them later with versions suitable for the __latest version__ of OpenCart 3.0.x.x.
    * If upgrading from 3.0.x: Disable and uninstall any 3rd party extension which won't support the __latest version__ of OpenCart 3.0.x.x
    * No OpenCart 2 theme will work for OpenCart 3.0.x.x! You should set your store theme to default theme.

3. Download the __latest version__ of OpenCart 3.0.x.x and __upload all__ new files on top of your current install __except__ your `config.php` and `admin/config.php`.
    * Backup and Remove all of 2.x.x views files. Because OpenCart 3 uses TWIG which replaces the TPL format.

4. Change your PHP version to 8.1 or later. This is typically done through your hosting control panel, by modifying configuration files for a local server environment, or using command line tools, depending on your setup

5. Browse to `https://<yourstore>/install` Replacing `<yourstore>` with your actual site (and subdirectory if applicable).

6. You should see the OpenCart Upgrade script.
    * If you see the OpenCart Install page, then that means you overwrote your `config.php` files. Restore them from your backup first. Then try again.

7. Click "Upgrade". After a few seconds you should see the upgrade success page.
    * If you see any errors, report them immediately in the forum before continuing.

8. Clear any cookies in your browser

9. Goto the admin side of your store and press `Ctrl+F5` or `Ctrl+Shift+R` for several times to refresh your browser cache. That will prevent oddly shifted elements due to stylesheet changes. Login to your admin as the main administrator.

10. Goto `Admin -> Users -> User Groups` and Edit the Top Administrator group. Check All boxes.
    * This will ensure you have permissions for all the new files

11. Goto `Admin -> Extensions -> Extensions -> Theme` enabled and save the default theme again.

12. Goto `Admin -> System Settings`
    * Update any blank fields and click save.
    * Even if you do not see any new fields, click save anyway to update the database with any new field names.

13. Other Adjustments that may need to be made, depending on which version you are upgrading from and to. These are broken down by which version they were added in. So if you are not currently at that version, you may need to make changes

14. Load the store front and again press CTRL+F5 3x times to refresh your browser cache. That will prevent oddly shifted elements due to stylesheet. (If you skip step 11, you will get the error message.)

15. If you use vQmod you should first check for a new version at https://vqmod.com.
    * Be sure to download the version that is marked for "opencart".
    * You will also need to re-run the vQmod installer, even if you have the latest version. You should already have the installer on your site as it isn't meant to be deleted from the first time you run it. Simply browse to: `https://<yoursite.com>/vqmod/install` and you should see a success message. If you do not see a success message, follow the full install guide from the https://vQmod.com site.
    * Note that some of your vQmod scripts may need to be updated for the new core changes. So run through your site catalog and admin areas and check in FTP for the vqmod/vqmod.log file. If you see errors, then you will need to address them.


## Troubleshooting:

1. If you have any upgrade script errors, post them in the forum
2. If you have 3rd party addon errors, contact the mod author for an update.
3. If you find bugs, check the "official" bug thread for this version of Opencart


Many bugs may have already been reported and fixes will be offered in the first post of this thread.
You should always visit this thread immediately after a fresh upgrade to see if there are any immediate bug fixes
If nobody has reported your bug, then please report it.
